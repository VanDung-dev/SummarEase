from flask import Blueprint, request, jsonify
from .utils.text_cleaner import clean_text
from .utils.summarizer import textrank_summarize, generate_title
from .utils.file_handler import extract_text
from .utils.database import save_summary_to_db
from .utils.gemini_summarizer import gemini_summarize, gemini_summarize_file, gemini_summarize_url
from .utils.language_detector import detect_language
import tempfile
import os
import logging
from urllib.parse import urlparse

# Khởi tạo Blueprint cho các tuyến API tóm tắt văn bản
summarize_bp = Blueprint("summarize", __name__)
logger = logging.getLogger(__name__)

# Đường dẫn đến tệp từ dừng
STOP_WORDS_PATH = os.path.join(os.path.dirname(__file__), "stopwords.txt")


@summarize_bp.route("/summarize", methods=["POST"])
def summarize_text():
    """
    Tóm tắt văn bản đầu vào sử dụng thuật toán TextRank.

    Đầu vào (JSON payload):
        - text (str): Nội dung văn bản cần tóm tắt (bắt buộc).
        - ratio (float, tùy chọn): Tỷ lệ tóm tắt (mặc định: 0.2, giá trị từ 0.0 đến 1.0).
        - language (str, tùy chọn): Ngôn ngữ của văn bản ("vietnamese" hoặc "english", mặc định: "vietnamese").
        - user_id (int): ID của người dùng (bắt buộc).

    Trả về:
        - JSON chứa trạng thái, nội dung tóm tắt, tỷ lệ tóm tắt và ngôn ngữ.

    Ngoại lệ:
        - 400: Nếu yêu cầu không đúng định dạng JSON, văn bản rỗng, ngôn ngữ không hợp lệ hoặc thiếu user_id.
        - 500: Nếu xảy ra lỗi trong quá trình xử lý.
    """
    try:
        if not request.is_json:
            return jsonify({"error": "Yêu cầu phải có Content-Type là application/json"}), 400

        data = request.get_json()
        raw_text = data.get("text", "")
        ratio = float(data.get("ratio", 0.2))
        language = data.get("language", "vietnamese")
        # Lấy user_id từ request - bắt buộc phải có
        user_id = data.get("user_id")
        
        # Kiểm tra user_id có được cung cấp không
        if user_id is None:
            return jsonify({"error": "Thiếu user_id trong yêu cầu"}), 400

        if not raw_text.strip():
            return jsonify({"error": "Nội dung văn bản không được để trống"}), 400
        if language.lower() not in ["vietnamese", "english"]:
            return jsonify({"error": f"Ngôn ngữ không được hỗ trợ: {language}"}), 400

        language = detect_language(raw_text)
        cleaned_text = clean_text(raw_text)
        result = textrank_summarize(
            cleaned_text,
            ratio=ratio,
            language=language,
            stop_words_path=STOP_WORDS_PATH
        )

        # Kiểm tra kết quả trả về
        if not isinstance(result, dict) or "summary" not in result or "highlighted_summary" not in result or "keywords" not in result:
            return jsonify({"error": "Lỗi xử lý văn bản: Kết quả không hợp lệ"}), 500

        # Chuẩn bị dữ liệu để lưu vào DB
        summary_data = {
            "summary": result["summary"],
            "highlighted_summary": result["highlighted_summary"],
            "ratio": ratio,
            "keywords": result["keywords"]
        }
        
        document_data = {
            "content": raw_text,
            "title": result.get("title", "Untitled"),
            "file_name": None,
            "file_type": "text",
            "user_id": user_id  # Sử dụng user_id từ request
        }
        
        # Lưu vào cơ sở dữ liệu
        save_success = save_summary_to_db(summary_data, document_data)
        if not save_success:
            logger.warning("Không thể lưu kết quả tóm tắt vào cơ sở dữ liệu")

        return jsonify({
            "status": "success",
            "language": language,
            "title": result.get("title", generate_title(result["summary"], result["keywords"], language)),
            "keywords": result["keywords"],
            "ratio": ratio,
            "summary": result["highlighted_summary"]
        })

    except ValueError as ve:
        logger.error(f"Lỗi giá trị khi tóm tắt văn bản: {str(ve)}")
        return jsonify({"error": "Lỗi giá trị", "details": str(ve)}), 400
    except Exception as e:
        logger.error(f"Lỗi khi tóm tắt văn bản: {str(e)}")
        return jsonify({"error": "Lỗi xử lý văn bản", "details": str(e)}), 500


@summarize_bp.route("/summarize-file", methods=["POST"])
def summarize_file():
    """
    Tóm tắt văn bản trích xuất từ tệp được tải lên hoặc URL.

    Đầu vào (form data):
        - file: Tệp được tải lên hoặc URL (hỗ trợ: PDF, DOCX, TXT, DOC, RTF, ODT, EPUB, MD, MARKDOWN, hoặc URL).
        - ratio (float, tùy chọn): Tỷ lệ tóm tắt (mặc định: 0.2, giá trị từ 0.0 đến 1.0).
        - language (str, tùy chọn): Ngôn ngữ của văn bản ("vietnamese" hoặc "english", mặc định: "vietnamese").
        - user_id (int): ID của người dùng (bắt buộc).

    Trả về:
        - JSON chứa trạng thái, tên tệp/URL, nội dung tóm tắt, tỷ lệ tóm tắt và ngôn ngữ.

    Ngoại lệ:
        - 400: Nếu không có tệp/URL, tệp/URL rỗng, định dạng không hỗ trợ hoặc ngôn ngữ không hợp lệ.
        - 500: Nếu xảy ra lỗi trong quá trình xử lý.
    """
    global filename
    temp_path = None
    filename = None
    try:
        # Lấy user_id từ form data - bắt buộc phải có
        user_id_str = request.form.get('user_id') or request.form.get('user_id')
        if not user_id_str:
            return jsonify({"error": "Thiếu user_id trong yêu cầu"}), 400
        
        try:
            user_id = int(user_id_str)
        except ValueError:
            return jsonify({"error": "user_id phải là một số nguyên"}), 400
            
        # Kiểm tra xem dữ liệu gửi lên là URL hay file
        if 'file' in request.form:
            source = request.form['file'].strip()
            if not source:
                return jsonify({"error": "URL không được để trống"}), 400

            parsed = urlparse(source)
            if parsed.scheme not in ['http', 'https'] or not parsed.netloc:
                return jsonify({"error": f"URL không hợp lệ: {source}"}), 400

            filename = source
            temp_path = None
            source = source

        elif 'file' in request.files:
            file = request.files['file']
            if file.filename == '':
                return jsonify({"error": "Tệp chưa được chọn"}), 400

            filename = file.filename
            allowed_extensions = {'pdf', 'docx', 'txt', 'epub', 'md', 'markdown'}
            file_ext = file.filename.rsplit('.', 1)[1].lower() if '.' in file.filename else ''

            # Lưu tệp trước khi kiểm tra tiện ích mở rộng
            temp_path = os.path.join(tempfile.gettempdir(), file.filename)
            file.save(temp_path)

            # Kiểm tra xem tiện ích mở rộng có được phép không
            if file_ext not in allowed_extensions:
                return jsonify({"error": "Định dạng tệp không được hỗ trợ"}), 400

            source = temp_path

        else:
            return jsonify({"error": "Không tìm thấy tệp hoặc URL trong yêu cầu"}), 400

        # Lấy tỷ lệ tóm tắt và ngôn ngữ từ biểu mẫu
        ratio = float(request.form.get('ratio', 0.2))
        language = request.form.get('language', 'vietnamese')

        # Kiểm tra ngôn ngữ hợp lệ
        if language.lower() not in ["vietnamese", "english"]:
            return jsonify({"error": f"Ngôn ngữ không được hỗ trợ: {language}"}), 400

        # Trích xuất và xử lý văn bản
        raw_text = extract_text(source)
        if not raw_text.strip():
            return jsonify({"error": "Nội dung tệp hoặc URL trống"}), 400

        language = detect_language(raw_text)
        cleaned_text = clean_text(raw_text)
        result = textrank_summarize(
            cleaned_text,
            ratio=ratio,
            language=language,
            stop_words_path=STOP_WORDS_PATH
        )

        # Kiểm tra kết quả trả về
        if not isinstance(result, dict) or "summary" not in result or "highlighted_summary" not in result or "keywords" not in result:
            return jsonify({"error": "Lỗi xử lý văn bản: Kết quả không hợp lệ"}), 500

        # Chuẩn bị dữ liệu để lưu vào DB
        summary_data = {
            "summary": result["summary"],
            "highlighted_summary": result["highlighted_summary"],
            "ratio": ratio,
            "keywords": result["keywords"]
        }
        
        file_type = filename.rsplit('.', 1)[1].lower() if '.' in filename else 'unknown'
        document_data = {
            "content": raw_text,
            "title": result.get("title", f"Summary of {filename}"),
            "file_name": filename,
            "file_type": file_type,
            "user_id": user_id  # Sử dụng user_id từ form data
        }
        
        # Lưu vào cơ sở dữ liệu
        save_success = save_summary_to_db(summary_data, document_data)
        if not save_success:
            logger.warning("Không thể lưu kết quả tóm tắt vào cơ sở dữ liệu")

        return jsonify({
            "status": "success",
            "language": language,
            "title": result.get("title", generate_title(result["summary"], result["keywords"], language)),
            "keywords": result["keywords"],
            "ratio": ratio,
            "summary": result["highlighted_summary"]
        })

    except ValueError as ve:
        logger.error(f"Lỗi giá trị khi xử lý {filename if 'filename' in locals() else 'nguồn'}: {str(ve)}")
        return jsonify({"error": "Lỗi giá trị", "details": str(ve)}), 400
    except Exception as e:
        logger.error(f"Lỗi khi xử lý {filename if 'filename' in locals() else 'nguồn'}: {str(e)}")
        return jsonify({"error": "Xử lý tệp hoặc URL thất bại", "details": str(e)}), 500
    finally:
        # Đảm bảo dọn dẹp xảy ra trong mọi trường hợp
        if temp_path and os.path.exists(temp_path):
            try:
                os.remove(temp_path)
            except Exception as e:
                logger.warning(f"Could not remove temporary file {temp_path}: {str(e)}")


@summarize_bp.route("/summarize-gemini", methods=["POST"])
def summarize_text_gemini():
    """
    Tóm tắt văn bản đầu vào sử dụng Gemini API.

    Đầu vào (JSON payload):
        - text (str): Nội dung văn bản cần tóm tắt (bắt buộc).
        - ratio (float, tùy chọn): Tỷ lệ tóm tắt (mặc định: 0.2, giá trị từ 0.0 đến 1.0).
        - language (str, tùy chọn): Ngôn ngữ của văn bản ("vietnamese" hoặc "english", mặc định: "vietnamese").
        - user_id (int): ID của người dùng (bắt buộc).

    Trả về:
        - JSON chứa trạng thái, nội dung tóm tắt, tỷ lệ tóm tắt và ngôn ngữ.

    Ngoại lệ:
        - 400: Nếu yêu cầu không đúng định dạng JSON, văn bản rỗng, ngôn ngữ không hợp lệ hoặc thiếu user_id.
        - 500: Nếu xảy ra lỗi trong quá trình xử lý với Gemini API.
    """
    try:
        if not request.is_json:
            return jsonify({"error": "Yêu cầu phải có Content-Type là application/json"}), 400

        data = request.get_json()
        raw_text = data.get("text", "")
        ratio = float(data.get("ratio", 0.2))
        language = data.get("language", "vietnamese")
        # Lấy user_id từ request - bắt buộc phải có
        user_id = data.get("user_id")
        
        # Kiểm tra user_id có được cung cấp không
        if user_id is None:
            return jsonify({"error": "Thiếu user_id trong yêu cầu"}), 400

        if not raw_text.strip():
            return jsonify({"error": "Nội dung văn bản không được để trống"}), 400
        if language.lower() not in ["vietnamese", "english"]:
            return jsonify({"error": f"Ngôn ngữ không được hỗ trợ: {language}"}), 400

        language = detect_language(raw_text)
        # Gọi Gemini API để tóm tắt văn bản
        result = gemini_summarize(
            raw_text,
            ratio=ratio,
            language=language
        )

        # Kiểm tra kết quả trả về
        if not isinstance(result, dict) or "summary" not in result:
            return jsonify({"error": "Lỗi xử lý văn bản với Gemini API: Kết quả không hợp lệ"}), 500

        # Chuẩn bị dữ liệu để lưu vào DB
        summary_data = {
            "summary": result["summary"],
            "highlighted_summary": result["highlighted_summary"],
            "ratio": ratio,
            "keywords": result["keywords"]
        }
        
        document_data = {
            "content": raw_text,
            "title": result.get("title", f"Tóm tắt văn bản bằng Gemini"),
            "file_name": None,
            "file_type": "text",
            "user_id": user_id  # Sử dụng user_id từ request
        }
        
        # Lưu vào cơ sở dữ liệu
        save_success = save_summary_to_db(summary_data, document_data)
        if not save_success:
            logger.warning("Không thể lưu kết quả tóm tắt vào cơ sở dữ liệu")

        return jsonify({
            "status": "success",
            "language": language,
            "title": result.get("title", f"Tóm tắt văn bản bằng Gemini"),
            "keywords": result["keywords"],
            "ratio": ratio,
            "summary": result["summary"]
        })

    except ValueError as ve:
        logger.error(f"Lỗi giá trị khi tóm tắt văn bản với Gemini: {str(ve)}")
        return jsonify({"error": "Lỗi giá trị", "details": str(ve)}), 400
    except Exception as e:
        logger.error(f"Lỗi khi tóm tắt văn bản với Gemini: {str(e)}")
        return jsonify({"error": "Lỗi xử lý văn bản với Gemini API", "details": str(e)}), 500


@summarize_bp.route("/summarize-file-gemini", methods=["POST"])
def summarize_file_gemini():
    """
    Tóm tắt nội dung tệp được tải lên sử dụng Gemini API.

    Đầu vào (form data):
        - file: Tệp được tải lên (hỗ trợ: PDF, DOCX, TXT, EPUB, MD, MARKDOWN).
        - ratio (float, tùy chọn): Tỷ lệ tóm tắt (mặc định: 0.2, giá trị từ 0.0 đến 1.0).
        - language (str, tùy chọn): Ngôn ngữ của văn bản ("vietnamese", "english" hoặc "auto" để tự động xác định, mặc định: "vietnamese").
        - user_id (int): ID của người dùng (bắt buộc).

    Trả về:
        - JSON chứa trạng thái, nội dung tóm tắt, tỷ lệ tóm tắt và ngôn ngữ.

    Ngoại lệ:
        - 400: Nếu không có tệp, tệp rỗng, định dạng không hỗ trợ, ngôn ngữ không hợp lệ hoặc thiếu user_id.
        - 500: Nếu xảy ra lỗi trong quá trình xử lý với Gemini API.
    """
    temp_path = None
    filename = None
    try:
        # Lấy user_id từ form data - bắt buộc phải có
        user_id_str = request.form.get('user_id') or request.form.get('user_id')
        if not user_id_str:
            return jsonify({"error": "Thiếu user_id trong yêu cầu"}), 400
        
        try:
            user_id = int(user_id_str)
        except ValueError:
            return jsonify({"error": "user_id phải là một số nguyên"}), 400

        # Kiểm tra xem có file được tải lên không
        if 'file' not in request.files:
            return jsonify({"error": "Không tìm thấy tệp trong yêu cầu"}), 400

        file = request.files['file']
        if file.filename == '':
            return jsonify({"error": "Tệp chưa được chọn"}), 400

        filename = file.filename
        allowed_extensions = {'pdf', 'docx', 'txt', 'epub', 'md', 'markdown'}
        file_ext = file.filename.rsplit('.', 1)[1].lower() if '.' in file.filename else ''

        # Lưu tệp trước khi kiểm tra tiện ích mở rộng
        temp_path = os.path.join(tempfile.gettempdir(), file.filename)
        file.save(temp_path)

        # Kiểm tra xem tiện ích mở rộng có được phép không
        if file_ext not in allowed_extensions:
            return jsonify({"error": "Định dạng tệp không được hỗ trợ"}), 400

        # Lấy tỷ lệ tóm tắt và ngôn ngữ từ biểu mẫu
        ratio = float(request.form.get('ratio', 0.2))
        language = request.form.get('language', 'vietnamese')

        # Kiểm tra ngôn ngữ hợp lệ
        if language.lower() not in ["vietnamese", "english", "auto"]:
            return jsonify({"error": f"Ngôn ngữ không được hỗ trợ: {language}"}), 400

        # Gọi Gemini API để tóm tắt nội dung file
        result = gemini_summarize_file(
            temp_path,
            ratio=ratio,
            language=language
        )

        # Kiểm tra kết quả trả về
        if not isinstance(result, dict) or "summary" not in result:
            return jsonify({"error": "Lỗi xử lý văn bản với Gemini API: Kết quả không hợp lệ"}), 500

        # Đọc nội dung thô của file để lưu vào DB
        try:
            with open(temp_path, 'r', encoding='utf-8') as f:
                raw_text = f.read()
        except UnicodeDecodeError:
            # Nếu không phải UTF-8, thử các encoding khác hoặc giữ nguyên là binary
            with open(temp_path, 'r', encoding='utf-8', errors='ignore') as f:
                raw_text = f.read()

        # Chuẩn bị dữ liệu để lưu vào DB
        summary_data = {
            "summary": result["summary"],
            "highlighted_summary": result["highlighted_summary"],
            "ratio": ratio,
            "keywords": result["keywords"]
        }
        
        file_type = filename.rsplit('.', 1)[1].lower() if '.' in filename else 'unknown'
        document_data = {
            "content": raw_text,
            "title": result.get("title", f"Tóm tắt {filename} bằng Gemini"),
            "file_name": filename,
            "file_type": file_type,
            "user_id": user_id  # Sử dụng user_id từ form data
        }
        
        # Lưu vào cơ sở dữ liệu
        save_success = save_summary_to_db(summary_data, document_data)
        if not save_success:
            logger.warning("Không thể lưu kết quả tóm tắt vào cơ sở dữ liệu")

        return jsonify({
            "status": "success",
            "language": language,
            "title": result.get("title", f"Tóm tắt {filename} bằng Gemini"),
            "keywords": result["keywords"],
            "ratio": ratio,
            "summary": result["summary"]
        })

    except ValueError as ve:
        logger.error(f"Lỗi giá trị khi tóm tắt {filename if 'filename' in locals() else 'tệp'} với Gemini: {str(ve)}")
        return jsonify({"error": "Lỗi giá trị", "details": str(ve)}), 400
    except Exception as e:
        logger.error(f"Lỗi khi tóm tắt {filename if 'filename' in locals() else 'tệp'} với Gemini: {str(e)}")
        return jsonify({"error": "Lỗi xử lý tệp với Gemini API", "details": str(e)}), 500
    finally:
        # Đảm bảo dọn dẹp xảy ra trong mọi trường hợp
        if temp_path and os.path.exists(temp_path):
            try:
                os.remove(temp_path)
            except Exception as e:
                logger.warning(f"Could not remove temporary file {temp_path}: {str(e)}")


@summarize_bp.route("/summarize-url-gemini", methods=["POST"])
def summarize_url_gemini():
    """
    Tóm tắt nội dung từ URL sử dụng Gemini API.

    Đầu vào (form data):
        - url: URL của trang web cần tóm tắt.
        - ratio (float, tùy chọn): Tỷ lệ tóm tắt (mặc định: 0.2, giá trị từ 0.0 đến 1.0).
        - language (str, tùy chọn): Ngôn ngữ của văn bản ("vietnamese", "english" hoặc "auto" để tự động xác định, mặc định: "vietnamese").
        - user_id (int): ID của người dùng (bắt buộc).

    Trả về:
        - JSON chứa trạng thái, nội dung tóm tắt, tỷ lệ tóm tắt và ngôn ngữ.

    Ngoại lệ:
        - 400: Nếu không có URL, URL không hợp lệ, ngôn ngữ không hợp lệ hoặc thiếu user_id.
        - 500: Nếu xảy ra lỗi trong quá trình xử lý với Gemini API.
    """
    try:
        # Lấy user_id từ form data - bắt buộc phải có
        user_id_str = request.form.get('user_id') or request.form.get('user_id')
        if not user_id_str:
            return jsonify({"error": "Thiếu user_id trong yêu cầu"}), 400
        
        try:
            user_id = int(user_id_str)
        except ValueError:
            return jsonify({"error": "user_id phải là một số nguyên"}), 400

        # Kiểm tra xem có URL được cung cấp không
        url = request.form.get('url')
        if not url:
            return jsonify({"error": "Không tìm thấy URL trong yêu cầu"}), 400

        # Kiểm tra URL hợp lệ
        parsed = urlparse(url)
        if parsed.scheme not in ['http', 'https'] or not parsed.netloc:
            return jsonify({"error": f"URL không hợp lệ: {url}"}), 400

        # Lấy tỷ lệ tóm tắt và ngôn ngữ từ biểu mẫu
        ratio = float(request.form.get('ratio', 0.2))
        language = request.form.get('language', 'vietnamese')

        # Kiểm tra ngôn ngữ hợp lệ
        if language.lower() not in ["vietnamese", "english", "auto"]:
            return jsonify({"error": f"Ngôn ngữ không được hỗ trợ: {language}"}), 400

        # Extract text from URL before language detection
        raw_text = extract_text(url)
        if not raw_text.strip():
            return jsonify({"error": "Nội dung từ URL trống"}), 400
        language = detect_language(raw_text)
        # Gọi Gemini API để tóm tắt nội dung từ URL
        result = gemini_summarize_url(
            url,
            ratio=ratio,
            language=language
        )

        # Kiểm tra kết quả trả về
        if not isinstance(result, dict) or "summary" not in result:
            return jsonify({"error": "Lỗi xử lý văn bản với Gemini API: Kết quả không hợp lệ"}), 500

        # Chuẩn bị dữ liệu để lưu vào DB
        summary_data = {
            "summary": result["summary"],
            "highlighted_summary": result["highlighted_summary"],
            "ratio": ratio,
            "keywords": result["keywords"]
        }
        
        document_data = {
            "content": f"Content from URL: {url}",
            "title": result.get("title", f"Tóm tắt {url} bằng Gemini"),
            "file_name": url,
            "file_type": "url",
            "user_id": user_id  # Sử dụng user_id từ form data
        }
        
        # Lưu vào cơ sở dữ liệu
        save_success = save_summary_to_db(summary_data, document_data)
        if not save_success:
            logger.warning("Không thể lưu kết quả tóm tắt vào cơ sở dữ liệu")

        return jsonify({
            "status": "success",
            "language": language,
            "title": result.get("title", f"Tóm tắt {url} bằng Gemini"),
            "keywords": result["keywords"],
            "ratio": ratio,
            "summary": result["summary"]
        })

    except ValueError as ve:
        logger.error(f"Lỗi giá trị khi tóm tắt URL với Gemini: {str(ve)}")
        return jsonify({"error": "Lỗi giá trị", "details": str(ve)}), 400
    except Exception as e:
        logger.error(f"Lỗi khi tóm tắt URL với Gemini: {str(e)}")
        return jsonify({"error": "Lỗi xử lý URL với Gemini API", "details": str(e)}), 500
