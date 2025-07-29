import pytest
import sys
from unittest.mock import patch, mock_open
from pathlib import Path

# Thêm thư mục gốc vào PYTHONPATH
root_dir = Path(__file__).parent.parent
sys.path.insert(0, str(root_dir))

try:
    from app.utils.summarizer import load_stop_words, textrank_summarize
except ImportError as e:
    print(f"Lỗi import: {e}")
    print(f"Current sys.path: {sys.path}")
    raise


def test_load_stop_words_success():
    """Kiểm tra hàm load_stop_words khi tệp tồn tại."""
    mock_file_content = "là\ncủa\nvà\n"
    with patch('builtins.open', mock_open(read_data=mock_file_content)):
        stop_words = load_stop_words('stopwords.txt')
        assert stop_words == {'là', 'của', 'và'}


def test_load_stop_words_file_not_found():
    """Kiểm tra hàm load_stop_words khi tệp không tồn tại."""
    with patch('builtins.open', side_effect=FileNotFoundError):
        with pytest.raises(ValueError, match="Không tìm thấy tệp từ dừng: stopwords.txt"):
            load_stop_words('stopwords.txt')


def test_textrank_summarize_empty_text():
    """Kiểm tra hàm textrank_summarize với văn bản rỗng."""
    with pytest.raises(ValueError, match="Nội dung văn bản không được để trống"):
        textrank_summarize(
            text="",
            ratio=0.2,
            language="vietnamese",
            stop_words_path="stopwords.txt"
        )


def test_textrank_summarize_invalid_language():
    """Kiểm tra hàm textrank_summarize với ngôn ngữ không hợp lệ."""
    with pytest.raises(ValueError, match="Ngôn ngữ không được hỗ trợ: french"):
        textrank_summarize(
            text="Đây là văn bản",
            ratio=0.2,
            language="french",
            stop_words_path="stopwords.txt"
        )


def test_textrank_summarize_english():
    """Kiểm tra hàm textrank_summarize với văn bản tiếng Anh."""
    with patch('app.utils.summarizer.PlaintextParser') as mock_parser, \
            patch('app.utils.summarizer.TextRankSummarizer') as mock_summarizer:
        mock_parser_instance = mock_parser.from_string.return_value
        mock_parser_instance.document.sentences = ['Sentence 1.', 'Sentence 2.', 'Sentence 3.']
        mock_summarizer_instance = mock_summarizer.return_value
        mock_summarizer_instance.return_value = ['Sentence 1.']

        result = textrank_summarize(
            text="Sentence 1. Sentence 2. Sentence 3.",
            ratio=0.3,
            language="english",
            stop_words_path="stopwords.txt"
        )

        assert result["summary"] == "Sentence 1."
        mock_parser.from_string.assert_called_once()
        mock_summarizer.assert_called_once()
        mock_summarizer_instance.assert_called_once_with(mock_parser_instance.document, 1)


def test_textrank_summarize_success_vietnamese():
    """Kiểm tra hàm textrank_summarize với văn bản tiếng Việt hợp lệ."""
    with patch('app.utils.summarizer.PlaintextParser') as mock_parser, \
         patch('app.utils.summarizer.TextRankSummarizer') as mock_summarizer, \
         patch('app.utils.summarizer.load_stop_words', return_value={'và', 'trên'}):

        mock_parser_instance = mock_parser.from_string.return_value
        mock_parser_instance.document.sentences = ['Câu 1.', 'Câu 2.', 'Câu 3.']
        mock_summarizer_instance = mock_summarizer.return_value
        mock_summarizer_instance.return_value = ['Câu 1.', 'Câu 2.']

        result = textrank_summarize(
            text="Câu 1. Câu 2. Câu 3.",
            ratio=0.6,
            language="vietnamese",
            stop_words_path="stopwords.txt"
        )

        assert result["summary"] == "Câu 1. Câu 2."
        mock_parser.from_string.assert_called_once()
        mock_summarizer.assert_called_once()
        mock_summarizer_instance.assert_called_once_with(mock_parser_instance.document, 1)
