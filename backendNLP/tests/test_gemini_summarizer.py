import pytest
import sys
from unittest.mock import patch, Mock
from pathlib import Path

# Thêm thư mục gốc vào PYTHONPATH
root_dir = Path(__file__).parent.parent
sys.path.insert(0, str(root_dir))

try:
    from app.utils.gemini_summarizer import gemini_summarize, gemini_summarize_url
except ImportError as e:
    print(f"Lỗi import: {e}")
    print(f"Current sys.path: {sys.path}")
    raise


@patch('app.utils.gemini_summarizer.os.getenv')
@patch('app.utils.gemini_summarizer.requests.post')
def test_gemini_summarize_success(mock_post, mock_getenv):
    """Kiểm tra hàm gemini_summarize khi gọi API thành công."""
    # Thiết lập mock
    mock_getenv.return_value = "fake-api-key"
    
    # Mock response cho tiêu đề
    mock_title_response = Mock()
    mock_title_response.json.return_value = {
        "candidates": [{
            "content": {
                "parts": [{
                    "text": "Tiêu đề mẫu cho văn bản"
                }]
            }
        }]
    }
    
    # Mock response cho nội dung tóm tắt
    mock_summary_response = Mock()
    mock_summary_response.json.return_value = {
        "candidates": [{
            "content": {
                "parts": [{
                    "text": "Đây là bản tóm tắt văn bản bằng tiếng Việt."
                }]
            }
        }]
    }
    
    mock_response = Mock()
    mock_response.raise_for_status.return_value = None
    
    # Thiết lập mock để trả về các response khác nhau cho các lần gọi khác nhau
    mock_post.side_effect = [mock_title_response, mock_summary_response]

    # Gọi hàm kiểm thử
    result = gemini_summarize(
        text="Đây là văn bản cần tóm tắt bằng Gemini API.",
        ratio=0.3,
        language="vietnamese"
    )

    # Kiểm tra kết quả
    assert "summary" in result
    assert "Đây là bản tóm tắt" in result["summary"]
    assert result["title"] == "Tiêu đề mẫu cho văn bản"
    assert isinstance(result["keywords"], list)
    
    # Kiểm tra mock được gọi đúng cách
    mock_getenv.assert_called_once_with("GEMINI_API_KEY")
    assert mock_post.call_count == 2


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


@patch('app.utils.gemini_summarizer.requests.get')
@patch('app.utils.gemini_summarizer.os.getenv')
@patch('app.utils.gemini_summarizer.requests.post')
def test_gemini_summarize_url_success(mock_post, mock_getenv, mock_get):
    """Kiểm tra hàm gemini_summarize_url khi gọi API thành công."""
    # Thiết lập mock cho requests.get
    mock_response_get = Mock()
    mock_response_get.text = """
    <html>
        <head><title>Test Page</title></head>
        <body>
            <h1>Tiêu đề trang web</h1>
            <p>Đây là đoạn văn bản cần tóm tắt bằng Gemini API từ một trang web.</p>
            <p>Đây là đoạn văn bản thứ hai để kiểm tra việc trích xuất nội dung.</p>
        </body>
    </html>
    """
    mock_response_get.raise_for_status.return_value = None
    mock_get.return_value = mock_response_get
    
    # Thiết lập mock cho API key
    mock_getenv.return_value = "fake-api-key"
    
    # Mock response cho tiêu đề
    mock_title_response = Mock()
    mock_title_response.json.return_value = {
        "candidates": [{
            "content": {
                "parts": [{
                    "text": "Tiêu đề mẫu cho văn bản từ URL"
                }]
            }
        }]
    }
    
    # Mock response cho nội dung tóm tắt
    mock_summary_response = Mock()
    mock_summary_response.json.return_value = {
        "candidates": [{
            "content": {
                "parts": [{
                    "text": "Đây là bản tóm tắt văn bản từ trang web bằng tiếng Việt."
                }]
            }
        }]
    }
    
    mock_response = Mock()
    mock_response.raise_for_status.return_value = None
    
    # Thiết lập mock để trả về các response khác nhau cho các lần gọi khác nhau
    mock_post.side_effect = [mock_title_response, mock_summary_response]

    # Gọi hàm kiểm thử
    result = gemini_summarize_url(
        url="https://example.com/test-page",
        ratio=0.3,
        language="vietnamese"
    )

    # Kiểm tra kết quả
    assert "summary" in result
    assert "Đây là bản tóm tắt" in result["summary"]
    assert result["title"] == "Tiêu đề mẫu cho văn bản từ URL"
    assert isinstance(result["keywords"], list)
    
    # Kiểm tra mock được gọi đúng cách
    mock_get.assert_called_once_with("https://example.com/test-page")
    mock_getenv.assert_called_once_with("GEMINI_API_KEY")
    assert mock_post.call_count == 2


@patch('app.utils.gemini_summarizer.requests.get')
def test_gemini_summarize_url_request_error(mock_get):
    """Kiểm tra hàm gemini_summarize_url khi có lỗi tải trang web."""
    mock_get.side_effect = Exception("Lỗi kết nối")
    
    with pytest.raises(Exception, match="Lỗi khi xử lý nội dung từ URL: Lỗi kết nối"):
        gemini_summarize_url(
            url="https://example.com/bad-url",
            ratio=0.2,
            language="vietnamese"
        )