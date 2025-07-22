from sumy.parsers.plaintext import PlaintextParser
from sumy.summarizers.text_rank import TextRankSummarizer
from .tokenizer import VietnameseTokenizer, EnglishTokenizer
import os

def load_stop_words(file_path: str) -> set:
    """
    Tải danh sách từ dừng từ tệp văn bản.

    Đầu vào:
        - file_path (str): Đường dẫn đến tệp chứa danh sách từ dừng.

    Trả về:
        - set: Tập hợp các từ dừng.

    Ngoại lệ:
        - ValueError: Nếu tệp từ dừng không tồn tại.
    """
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            return set(line.strip() for line in f if line.strip())
    except FileNotFoundError:
        raise ValueError(f"Không tìm thấy tệp từ dừng: {file_path}")

def textrank_summarize(text: str, ratio: float = 0.2, language: str = "vietnamese", stop_words_path: str = None) -> str:
    """
    Tóm tắt văn bản sử dụng thuật toán TextRank từ thư viện Sumy.

    Đầu vào:
        - text (str): Văn bản cần tóm tắt.
        - ratio (float): Tỷ lệ số câu trong tóm tắt so với văn bản gốc (mặc định: 0.2).
        - language (str): Ngôn ngữ của văn bản ("vietnamese" hoặc "english", mặc định: "vietnamese").
        - stop_words_path (str, tùy chọn): Đường dẫn đến tệp chứa danh sách từ dừng.

    Trả về:
        - str: Nội dung văn bản được tóm tắt.

    Ngoại lệ:
        - ValueError: Nếu văn bản rỗng hoặc ngôn ngữ không được hỗ trợ.
    """
    if not text.strip():
        raise ValueError("Nội dung văn bản không được để trống")

    # Chọn tokenizer dựa trên ngôn ngữ
    if language.lower() == "vietnamese":
        tokenizer = VietnameseTokenizer()
    elif language.lower() == "english":
        tokenizer = EnglishTokenizer()
    else:
        raise ValueError(f"Ngôn ngữ không được hỗ trợ: {language}")

    # Khởi tạo parser với tokenizer được chọn
    parser = PlaintextParser.from_string(text, tokenizer)
    summarizer = TextRankSummarizer()

    # Tải từ dừng nếu có
    if stop_words_path:
        summarizer.stop_words = load_stop_words(stop_words_path)

    # Tính số câu cần giữ trong tóm tắt
    total_sentences = len(parser.document.sentences)
    n_sentences = max(1, min(total_sentences, int(total_sentences * ratio)))

    # Tạo tóm tắt
    summary_sentences = summarizer(parser.document, n_sentences)
    summary = " ".join(str(sentence) for sentence in summary_sentences)

    return summary