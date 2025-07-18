from flask import Blueprint, request, jsonify
from .utils.text_cleaner import clean_text
from .utils.summarizer import textrank_summarize
from .utils.file_handler import extract_text
import tempfile
import os
import logging
from urllib.parse import urlparse  # Thêm import này để kiểm tra URL

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

    Trả về:
        - JSON chứa trạng thái, nội dung tóm tắt, tỷ lệ tóm tắt và ngôn ngữ.

    Ngoại lệ:
        - 400: Nếu yêu cầu không đúng định dạng JSON, văn bản rỗng hoặc ngôn ngữ không hợp lệ.
        - 500: Nếu xảy ra lỗi trong quá trình xử lý.
    """
    try:
        if not request.is_json:
            return jsonify({"error": "Yêu cầu phải có Content-Type là application/json"}), 400
        data = request.get_json()
        raw_text = data.get("text", "")
        ratio = float(data.get("ratio", 0.2))
        language = data.get("language", "vietnamese")
        if not raw_text.strip():
            return jsonify({"error": "Nội dung văn bản không được để trống"}), 400
        if language.lower() not in ["vietnamese", "english"]:
            return jsonify({"error": f"Ngôn ngữ không được hỗ trợ: {language}"}), 400
        cleaned_text = clean_text(raw_text)
        summary = textrank_summarize(cleaned_text, ratio=ratio, language=language, stop_words_path=STOP_WORDS_PATH)
        return jsonify({
            "status": "success",
            "summary": summary,
            "ratio": ratio,
            "language": language
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

    Trả về:
        - JSON chứa trạng thái, tên tệp/URL, nội dung tóm tắt, tỷ lệ tóm tắt và ngôn ngữ.

    Ngoại lệ:
        - 400: Nếu không có tệp/URL, tệp/URL rỗng, định dạng không hỗ trợ hoặc ngôn ngữ không hợp lệ.
        - 500: Nếu xảy ra lỗi trong quá trình xử lý.
    """
    try:
        # Kiểm tra xem dữ liệu gửi lên là URL hay file
        if 'file' in request.form:
            source = request.form['file'].strip()
            if not source:
                return jsonify({"error": "URL không được để trống"}), 400
            # Kiểm tra xem URL có hợp lệ không
            parsed = urlparse(source)
            if parsed.scheme not in ['http', 'https'] or not parsed.netloc:
                return jsonify({"error": f"URL không hợp lệ: {source}"}), 400
            filename = source  # Sử dụng URL làm tên nguồn
            temp_path = None  # Không cần tệp tạm cho URL
        elif 'file' in request.files:
            file = request.files['file']
            if file.filename == '':
                return jsonify({"error": "Tệp chưa được chọn"}), 400
            # Xác thực định dạng tệp
            allowed_extensions = {'pdf', 'docx', 'txt', 'doc', 'rtf', 'odt', 'epub', 'md', 'markdown'}
            file_ext = file.filename.rsplit('.', 1)[1].lower() if '.' in file.filename else ''
            if file_ext not in allowed_extensions:
                # Xử lý xóa tệp tạm nếu có
                temp_path = os.path.join(tempfile.gettempdir(), file.filename)
                file.save(temp_path)
                os.remove(temp_path)  # Xóa tệp tạm ngay lập tức
                return jsonify({"error": "Định dạng tệp không được hỗ trợ"}), 400
            # Lưu tệp tạm thời
            temp_path = os.path.join(tempfile.gettempdir(), file.filename)
            file.save(temp_path)
            source = temp_path
            filename = file.filename
        else:
            return jsonify({"error": "Không tìm thấy tệp hoặc URL trong yêu cầu"}), 400

        # Lấy tỷ lệ tóm tắt và ngôn ngữ từ biểu mẫu
        ratio = float(request.form.get('ratio', 0.2))
        language = request.form.get('language', 'vietnamese')

        # Kiểm tra ngôn ngữ hợp lệ
        if language.lower() not in ["vietnamese", "english"]:
            return jsonify({"error": f"Ngôn ngữ không được hỗ trợ: {language}"}), 400

        try:
            # Trích xuất và tóm tắt văn bản
            raw_text = extract_text(source)
            if not raw_text.strip():
                return jsonify({"error": "Nội dung tệp hoặc URL trống"}), 400
            cleaned_text = clean_text(raw_text)
            summary = textrank_summarize(cleaned_text, ratio=ratio, language=language, stop_words_path=STOP_WORDS_PATH)
            return jsonify({
                "status": "success",
                "filename": filename,
                "summary": summary,
                "ratio": ratio,
                "language": language
            })
        finally:
            # Xóa tệp tạm nếu có (chỉ áp dụng cho file tải lên)
            if 'temp_path' in locals() and temp_path and os.path.exists(temp_path):
                os.remove(temp_path)

    except ValueError as ve:
        logger.error(f"Lỗi giá trị khi xử lý {filename if 'filename' in locals() else 'nguồn'}: {str(ve)}")
        return jsonify({"error": "Lỗi giá trị", "details": str(ve)}), 400
    except Exception as e:
        logger.error(f"Lỗi khi xử lý {filename if 'filename' in locals() else 'nguồn'}: {str(e)}")
        return jsonify({"error": "Xử lý tệp hoặc URL thất bại", "details": str(e)}), 500