import pytest
import sys
import os
from unittest.mock import patch, Mock
from pathlib import Path

# Thêm thư mục gốc vào PYTHONPATH
root_dir = Path(__file__).parent.parent
sys.path.insert(0, str(root_dir))

try:
    from app.utils.gemini_summarizer import gemini_summarize, gemini_summarize_file
except ImportError as e:
    print(f"Lỗi import: {e}")
    print(f"Current sys.path: {sys.path}")
    raise


@patch('app.utils.gemini_summarizer.os.getenv')
@patch('app.utils.gemini_summarizer.requests.post')
@patch('app.utils.gemini_summarizer.extract_keywords')
@patch('app.utils.gemini_summarizer.generate_title')
def test_gemini_summarize_success(mock_generate_title, mock_extract_keywords, mock_post, mock_getenv):
    """Kiểm tra hàm gemini_summarize khi gọi API thành công."""
    # Thiết lập mock
    mock_getenv.return_value = "fake-api-key"
    mock_extract_keywords.return_value = ["bản tóm tắt", "văn bản"]
    mock_generate_title.return_value = "Tiêu đề mẫu cho văn bản"
    
    # Mock response cho nội dung tóm tắt
    mock_summary_response = Mock()
    mock_summary_response.json.return_value = {
        "candidates": [{
            "content": {
                "parts": [{
                    "text": "Đây là **bản tóm tắt** văn bản bằng tiếng Việt."
                }]
            }
        }]
    }
    
    mock_response = Mock()
    mock_response.raise_for_status.return_value = None
    
    # Thiết lập mock để trả về response
    mock_post.return_value = mock_summary_response

    # Gọi hàm kiểm thử
    result = gemini_summarize(
        text="Đây là văn bản cần tóm tắt bằng Gemini API.",
        ratio=0.3,
        language="vietnamese"
    )

    # Kiểm tra kết quả
    assert "summary" in result
    assert "Đây là **bản tóm tắt**" in result["summary"]
    assert result["title"] == "Tiêu đề mẫu cho văn bản"
    assert result["keywords"] == ["bản tóm tắt", "văn bản"]
    assert result["highlighted_summary"] == "Đây là **bản tóm tắt** văn bản bằng tiếng Việt."
    
    # Kiểm tra mock được gọi đúng cách
    mock_getenv.assert_called_once_with("GEMINI_API_KEY")
    mock_post.assert_called_once()
    mock_extract_keywords.assert_called_once_with("Đây là văn bản cần tóm tắt bằng Gemini API.", "vietnamese")
    mock_generate_title.assert_called_once_with("Đây là **bản tóm tắt** văn bản bằng tiếng Việt.", ["bản tóm tắt", "văn bản"], "vietnamese")


@patch('app.utils.gemini_summarizer.os.getenv')
def test_gemini_summarize_missing_api_key(mock_getenv):
    """Kiểm tra hàm gemini_summarize khi thiếu API key."""
    mock_getenv.return_value = None
    
    with pytest.raises(ValueError, match="GEMINI_API_KEY không được thiết lập"):
        gemini_summarize(
            text="Đây là văn bản cần tóm tắt.",
            ratio=0.2,
            language="vietnamese"
        )


@patch('app.utils.gemini_summarizer.os.getenv')
@patch('app.utils.gemini_summarizer.requests.post')
def test_gemini_summarize_api_error(mock_post, mock_getenv):
    """Kiểm tra hàm gemini_summarize khi có lỗi từ API."""
    mock_getenv.return_value = "fake-api-key"
    mock_post.side_effect = Exception("Lỗi kết nối")
    
    with pytest.raises(Exception, match="Lỗi khi gọi Gemini API: Lỗi kết nối"):
        gemini_summarize(
            text="Đây là văn bản cần tóm tắt.",
            ratio=0.2,
            language="vietnamese"
        )


@patch('app.utils.gemini_summarizer.os.getenv')
@patch('app.utils.gemini_summarizer.requests.post')
@patch('app.utils.gemini_summarizer.extract_keywords')
@patch('app.utils.gemini_summarizer.generate_title')
@patch('app.utils.gemini_summarizer.extract_text')
def test_gemini_summarize_file_success(mock_extract_text, mock_generate_title, mock_extract_keywords, mock_post, mock_getenv):
    """Kiểm tra hàm gemini_summarize_file khi gọi API thành công."""
    # Thiết lập mock cho API key
    mock_getenv.return_value = "fake-api-key"
    mock_extract_text.return_value = "Đây là đoạn văn bản cần tóm tắt bằng Gemini API từ một file."
    mock_extract_keywords.return_value = ["bản tóm tắt", "file"]
    mock_generate_title.return_value = "Tiêu đề mẫu cho văn bản từ file"
    
    # Mock response cho nội dung tóm tắt
    mock_summary_response = Mock()
    mock_summary_response.json.return_value = {
        "candidates": [{
            "content": {
                "parts": [{
                    "text": "Đây là **bản tóm tắt** văn bản từ file bằng tiếng Việt."
                }]
            }
        }]
    }
    
    mock_response = Mock()
    mock_response.raise_for_status.return_value = None
    
    # Thiết lập mock để trả về response
    mock_post.return_value = mock_summary_response

    # Tạo file test giả lập
    with open('test.txt', 'w', encoding='utf-8') as f:
        f.write("Đây là đoạn văn bản cần tóm tắt bằng Gemini API từ một file.")
    
    try:
        # Gọi hàm kiểm thử
        result = gemini_summarize_file(
            file_path="test.txt",
            ratio=0.3,
            language="vietnamese"
        )

        # Kiểm tra kết quả
        assert "summary" in result
        assert "Đây là **bản tóm tắt**" in result["summary"]
        assert result["title"] == "Tiêu đề mẫu cho văn bản từ file"
        assert result["keywords"] == ["bản tóm tắt", "file"]
        assert result["highlighted_summary"] == "Đây là **bản tóm tắt** văn bản từ file bằng tiếng Việt."
        
        # Kiểm tra mock được gọi đúng cách
        mock_getenv.assert_called_once_with("GEMINI_API_KEY")
        mock_post.assert_called_once()
        mock_extract_text.assert_called_once_with("test.txt")
        mock_extract_keywords.assert_called_once_with("Đây là đoạn văn bản cần tóm tắt bằng Gemini API từ một file.", "vietnamese")
        mock_generate_title.assert_called_once_with("Đây là **bản tóm tắt** văn bản từ file bằng tiếng Việt.", ["bản tóm tắt", "file"], "vietnamese")
    finally:
        # Dọn dẹp file test
        if os.path.exists('test.txt'):
            os.remove('test.txt')


def test_gemini_summarize_file_request_error():
    """Kiểm tra hàm gemini_summarize_file khi có lỗi đọc file."""
    with pytest.raises(FileNotFoundError, match="File không tồn tại: non-existent-file.txt"):
        gemini_summarize_file(
            file_path="non-existent-file.txt",
            ratio=0.2,
            language="vietnamese"
        )