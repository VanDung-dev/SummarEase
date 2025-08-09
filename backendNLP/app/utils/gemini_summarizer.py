import os
import requests
import json
from typing import Dict, Any
import logging

logger = logging.getLogger(__name__)


def gemini_summarize(text: str, ratio: float = 0.2, language: str = "vietnamese") -> Dict[str, Any]:
    """
    Tóm tắt văn bản sử dụng Gemini API.

    Args:
        text (str): Văn bản cần tóm tắt
        ratio (float): Tỷ lệ tóm tắt (0.0 - 1.0)
        language (str): Ngôn ngữ của văn bản

    Returns:
        dict: Kết quả tóm tắt với các trường summary, keywords, title
    """
    # Lấy API key từ biến môi trường
    api_key = os.getenv("GEMINI_API_KEY")
    if not api_key:
        raise ValueError("GEMINI_API_KEY không được thiết lập trong biến môi trường")

    # URL của Gemini API với model gemini-1.5-flash
    url = f"https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={api_key}"

    # Tạo prompt cho Gemini để tóm tắt
    if language.lower() == "auto":
        summarize_prompt = f"""
        Hãy xác định ngôn ngữ của văn bản sau và tóm tắt văn bản đó với độ dài bằng {ratio:.0%} văn bản gốc.
        Trong văn bản tóm tắt, hãy đánh dấu các từ khóa quan trọng bằng cách bao quanh chúng bằng dấu ** (định dạng markdown).
        Ví dụ: Đây là **tóm tắt** của văn bản về **machine learning**.
        Chỉ trả về phần tóm tắt đã được đánh dấu từ khóa, không thêm bất kỳ thông tin nào khác.
        
        Văn bản cần tóm tắt:
        {text}
        """
    else:
        summarize_prompt = f"""
        Hãy tóm tắt văn bản sau bằng tiếng {language} với độ dài bằng {ratio:.0%} văn bản gốc.
        Trong văn bản tóm tắt, hãy đánh dấu các từ khóa quan trọng bằng cách bao quanh chúng bằng dấu ** (định dạng markdown).
        Ví dụ: Đây là **tóm tắt** của văn bản về **machine learning**.
        Chỉ trả về phần tóm tắt đã được đánh dấu từ khóa, không thêm bất kỳ thông tin nào khác.
        
        Văn bản cần tóm tắt:
        {text}
        """

    # Dữ liệu gửi đến API để tóm tắt
    summarize_payload = {
        "contents": [{
            "parts": [{
                "text": summarize_prompt
            }]
        }]
    }

    # Tạo prompt cho Gemini để tạo tiêu đề
    if language.lower() == "auto":
        title_prompt = f"""
        Hãy xác định ngôn ngữ của văn bản sau và đề xuất một tiêu đề ngắn gọn, phù hợp bằng ngôn ngữ đó.
        Tiêu đề không cần đánh dấu từ khóa, chỉ cần trả về tiêu đề rõ ràng, súc tích và không chứa bất kỳ định dạng markdown nào.
        Ví dụ: Nhận diện thực thể tiếng Việt với BERT và CRF
        Chỉ trả về tiêu đề, không thêm bất kỳ thông tin nào khác.
        
        Văn bản:
        {text}
        """
    else:
        title_prompt = f"""
        Dựa vào nội dung văn bản sau, hãy đề xuất một tiêu đề ngắn gọn, phù hợp bằng tiếng {language}.
        Tiêu đề không cần đánh dấu từ khóa, chỉ cần trả về tiêu đề rõ ràng, súc tích và không chứa bất kỳ định dạng markdown nào.
        Ví dụ: Nhận diện thực thể tiếng Việt với BERT và CRF
        Chỉ trả về tiêu đề, không thêm bất kỳ thông tin nào khác.
        
        Văn bản:
        {text}
        """

    # Dữ liệu gửi đến API để tạo tiêu đề
    title_payload = {
        "contents": [{
            "parts": [{
                "text": title_prompt
            }]
        }]
    }

    headers = {
        "Content-Type": "application/json"
    }

    try:
        # Gửi yêu cầu đến Gemini API để tạo tiêu đề
        title_response = requests.post(url, headers=headers, data=json.dumps(title_payload))
        title_response.raise_for_status()
        title_result = title_response.json()
        
        if "candidates" not in title_result or not title_result["candidates"]:
            title_text = f"Tóm tắt văn bản - {language}"
        else:
            candidate = title_result["candidates"][0]
            if "content" not in candidate or "parts" not in candidate["content"]:
                title_text = f"Tóm tắt văn bản - {language}"
            else:
                title_text = candidate["content"]["parts"][0]["text"].strip()

        # Gửi yêu cầu đến Gemini API để tóm tắt văn bản
        summary_response = requests.post(url, headers=headers, data=json.dumps(summarize_payload))
        summary_response.raise_for_status()
        summary_result = summary_response.json()
        
        if "candidates" not in summary_result or not summary_result["candidates"]:
            raise ValueError("Gemini API không trả về kết quả hợp lệ")
        
        candidate = summary_result["candidates"][0]
        if "content" not in candidate or "parts" not in candidate["content"]:
            raise ValueError("Gemini API trả về kết quả không hợp lệ")
        
        summary_text = candidate["content"]["parts"][0]["text"]
        
        # Trả về kết quả theo định dạng giống như textrank_summarize
        return {
            "summary": summary_text.strip(),
            "highlighted_summary": summary_text.strip(),
            "keywords": [],  # Gemini không trả về từ khóa riêng biệt
            "title": title_text
        }

    except requests.exceptions.RequestException as e:
        logger.error(f"Lỗi khi gọi Gemini API: {str(e)}")
        raise Exception(f"Lỗi kết nối đến Gemini API: {str(e)}")
    except KeyError as e:
        logger.error(f"Lỗi xử lý kết quả từ Gemini API: {str(e)}")
        raise Exception(f"Lỗi xử lý kết quả từ Gemini API: {str(e)}")
    except Exception as e:
        logger.error(f"Lỗi không xác định khi gọi Gemini API: {str(e)}")
        raise Exception(f"Lỗi khi gọi Gemini API: {str(e)}")


def gemini_summarize_file(file_path: str, ratio: float = 0.2, language: str = "vietnamese") -> Dict[str, Any]:
    """
    Tóm tắt nội dung của một file sử dụng Gemini API.
    
    Args:
        file_path (str): Đường dẫn đến file cần tóm tắt
        ratio (float): Tỷ lệ tóm tắt (0.0 - 1.0)
        language (str): Ngôn ngữ của văn bản
        
    Returns:
        dict: Kết quả tóm tắt với các trường summary, keywords, title
    """
    # Kiểm tra xem file có tồn tại không
    if not os.path.exists(file_path):
        raise FileNotFoundError(f"File không tồn tại: {file_path}")
    
    # Đọc nội dung file
    try:
        # Đọc file dưới dạng bytes để có thể xử lý nhiều loại file
        with open(file_path, 'rb') as file:
            file_content = file.read()
        
        # Nếu là file text thông thường
        try:
            text = file_content.decode('utf-8')
        except UnicodeDecodeError:
            # Nếu không phải UTF-8, thử các encoding khác hoặc giữ nguyên là binary
            text = file_content.decode('utf-8', errors='ignore')
            
        # Nếu file rỗng
        if not text.strip():
            raise ValueError("File rỗng hoặc không thể đọc nội dung")
            
    except Exception as e:
        logger.error(f"Lỗi khi đọc file {file_path}: {str(e)}")
        raise Exception(f"Lỗi khi đọc file: {str(e)}")
    
    # Sử dụng hàm gemini_summarize hiện tại để xử lý nội dung text
    return gemini_summarize(text, ratio, language)