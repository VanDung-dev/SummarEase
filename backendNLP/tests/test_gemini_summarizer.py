import pytest
import sys
from unittest.mock import patch, Mock
from pathlib import Path

# Thêm thư mục gốc vào PYTHONPATH
root_dir = Path(__file__).parent.parent
sys.path.insert(0, str(root_dir))

try:
    from app.utils.gemini_summarizer import gemini_summarize
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
    
    mock_response = Mock()
    mock_response.json.return_value = {
        "candidates": [{
            "content": {
                "parts": [{
                    "text": "Đây là bản tóm tắt văn bản bằng tiếng Việt."
                }]
            }
        }]
    }
    mock_response.raise_for_status.return_value = None
    mock_post.return_value = mock_response

    # Gọi hàm kiểm thử
    result = gemini_summarize(
        text="Đây là văn bản cần tóm tắt bằng Gemini API.",
        ratio=0.3,
        language="vietnamese"
    )

    # Kiểm tra kết quả
    assert "summary" in result
    assert "Đây là bản tóm tắt" in result["summary"]
    assert result["title"] == "Tóm tắt văn bản - vietnamese"
    assert isinstance(result["keywords"], list)
    
    # Kiểm tra mock được gọi đúng cách
    mock_getenv.assert_called_once_with("GEMINI_API_KEY")
    mock_post.assert_called_once()


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