from flask import Blueprint, request, jsonify
from .utils.text_cleaner import clean_text
from .utils.summarizer import textrank_summarize
from .utils.file_handler import extract_text
import tempfile
import os
import logging

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
        # Kiểm tra định dạng yêu cầu
        if not request.is_json:
            return jsonify({"error": "Yêu cầu phải có Content-Type là application/json"}), 400

        # Lấy dữ liệu từ yêu cầu JSON
        data = request.get_json()
        raw_text = data.get("text", "")
        ratio = float(data.get("ratio", 0.2))
        language = data.get("language", "vietnamese")

        # Kiểm tra nội dung văn bản
        if not raw_text.strip():
            return jsonify({"error": "Nội dung văn bản không được để trống"}), 400

        # Kiểm tra ngôn ngữ hợp lệ
        if language.lower() not in ["vietnamese", "english"]:
            return jsonify({"error": f"Ngôn ngữ không được hỗ trợ: {language}"}), 400

        # Làm sạch và tóm tắt văn bản
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
    Tóm tắt văn bản trích xuất từ tệp được tải lên.

    Đầu vào (form data):
        - file: Tệp được tải lên (hỗ trợ: PDF, DOCX, TXT, DOC, RTF, ODT, EPUB, MD, MARKDOWN).
        - ratio (float, tùy chọn): Tỷ lệ tóm tắt (mặc định: 0.2, giá trị từ 0.0 đến 1.0).
        - language (str, tùy chọn): Ngôn ngữ của văn bản ("vietnamese" hoặc "english", mặc định: "vietnamese").

    Trả về:
        - JSON chứa trạng thái, tên tệp, nội dung tóm tắt, tỷ lệ tóm tắt và ngôn ngữ.

    Ngoại lệ:
        - 400: Nếu không có tệp, tệp rỗng, định dạng không hỗ trợ hoặc ngôn ngữ không hợp lệ.
        - 500: Nếu xảy ra lỗi trong quá trình xử lý tệp.
    """
    try:
        # Kiểm tra sự tồn tại của tệp trong yêu cầu
        if 'file' not in request.files:
            return jsonify({"error": "Không tìm thấy tệp trong yêu cầu"}), 400

        file = request.files['file']
        if file.filename == '':
            return jsonify({"error": "Tệp chưa được chọn"}), 400

        # Luôn lưu tệp tạm thời trước khi kiểm tra định dạng
        temp_path = os.path.join(tempfile.gettempdir(), file.filename)
        file.save(temp_path)

        # Xác thực định dạng tệp
        allowed_extensions = {'pdf', 'docx', 'txt', 'doc', 'rtf', 'odt', 'epub', 'md', 'markdown'}
        file_ext = file.filename.rsplit('.', 1)[1].lower() if '.' in file.filename else ''
        if file_ext not in allowed_extensions:
            os.remove(temp_path)  # Xóa tệp trước khi trả lỗi
            return jsonify({"error": "Định dạng tệp không được hỗ trợ"}), 400

        # Lấy tỷ lệ tóm tắt và ngôn ngữ từ biểu mẫu
        ratio = float(request.form.get('ratio', 0.2))
        language = request.form.get('language', 'vietnamese')

        # Kiểm tra ngôn ngữ hợp lệ
        if language.lower() not in ["vietnamese", "english"]:
            return jsonify({"error": f"Ngôn ngữ không được hỗ trợ: {language}"}), 400

        try:
            # Trích xuất và tóm tắt văn bản từ tệp
            raw_text = extract_text(temp_path)
            if not raw_text.strip():
                return jsonify({"error": "Nội dung tệp trống"}), 400

            cleaned_text = clean_text(raw_text)
            summary = textrank_summarize(cleaned_text, ratio=ratio, language=language, stop_words_path=STOP_WORDS_PATH)

            return jsonify({
                "status": "success",
                "filename": file.filename,
                "summary": summary,
                "ratio": ratio,
                "language": language
            })

        finally:
            # Xóa tệp tạm sau khi xử lý
            if os.path.exists(temp_path):
                os.remove(temp_path)

    except ValueError as ve:
        logger.error(f"Lỗi giá trị khi xử lý tệp: {str(ve)}")
        return jsonify({"error": "Lỗi giá trị", "details": str(ve)}), 400
    except Exception as e:
        logger.error(f"Lỗi khi xử lý tệp: {str(e)}")
        return jsonify({"error": "Xử lý tệp thất bại", "details": str(e)}), 500