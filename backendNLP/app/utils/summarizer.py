from sumy.parsers.plaintext import PlaintextParser
from sumy.summarizers.text_rank import TextRankSummarizer
from .tokenizer import VietnameseTokenizer, EnglishTokenizer
from collections import Counter
import string
import re


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


def extract_keywords(text: str, language: str = "vietnamese", n_keywords: int = 5) -> list:
    """
    Trích xuất các từ khóa quan trọng từ văn bản.

    Đầu vào:
        - text (str): Văn bản cần trích xuất từ khóa
        - language (str): Ngôn ngữ của văn bản
        - n_keywords (int): Số lượng từ khóa cần trích xuất

    Trả về:
        - list: Danh sách các từ khóa quan trọng
    """
    # Chọn tokenizer dựa trên ngôn ngữ
    if language.lower() == "vietnamese":
        tokenizer = VietnameseTokenizer()
    elif language.lower() == "english":
        tokenizer = EnglishTokenizer()
    else:
        raise ValueError(f"Ngôn ngữ không được hỗ trợ: {language}")

    # Phân tách câu và từ
    sentences = tokenizer.to_sentences(text)
    words = [] # Lưu trữ từ được lower
    original_words = {}  # Lưu trữ từ gốc, key là từ lower, value là từ gốc đầu tiên gặp

    for sentence in sentences:
        sentence_words = tokenizer.to_words(sentence)
        for word in sentence_words:
            if word not in string.punctuation:
                lower_word = word.lower()
                words.append(lower_word)
                # Chỉ lưu từ gốc đầu tiên
                if lower_word not in original_words:
                    original_words[lower_word] = word

    # Đếm tần suất xuất hiện của từ (dùng dạng chữ thường để thống kê)
    word_counts = Counter(words)

    # Lấy các từ xuất hiện nhiều nhất, chuyển sang từ gốc
    keywords = []
    for word, count in word_counts.most_common():
        if len(word) > 2 and word in original_words:
            keywords.append(original_words[word])
            if len(keywords) >= n_keywords:
                break

    return keywords[:n_keywords]


def highlight_keywords(text: str, keywords: list) -> str:
    """
    Đánh dấu các từ khóa trong văn bản tóm tắt

    Đầu vào:
        - text (str): Văn bản tóm tắt
        - keywords (list): Danh sách từ khóa

    Trả về:
        - str: Văn bản với các từ khóa được đánh dấu
    """
    # Tạo bản sao của văn bản để xử lý
    highlighted_text = text

    # Sắp xếp từ khóa theo độ dài (dài nhất trước) để tránh thay thế một phần
    sorted_keywords = sorted(keywords, key=len, reverse=True)

    for keyword in sorted_keywords:
        # Sử dụng ranh giới từ để chỉ khớp từ nguyên vẹn
        pattern = r'\b' + re.escape(keyword) + r'\b'
        highlighted_text = re.sub(pattern, f"**{keyword}**", highlighted_text, flags=re.IGNORECASE)

    return highlighted_text


def generate_title(text: str, keywords: list, language: str = "vietnamese") -> str:
    """
    Tạo tiêu đề cho văn bản dựa trên từ khóa và nội dung

    Đầu vào:
        - text (str): Văn bản cần tạo tiêu đề
        - keywords (list): Danh sách từ khóa
        - language (str): Ngôn ngữ của văn bản

    Trả về:
        - str: Tiêu đề được tạo
    """
    if language.lower() == "vietnamese":
        tokenizer = VietnameseTokenizer()
    else:
        tokenizer = EnglishTokenizer()

    sentences = tokenizer.to_sentences(text)
    if not sentences:
        return "Tiêu đề mặc định"

    first_sentence = sentences[0]
    if keywords:
        return f"{keywords[0]}: {first_sentence[:50]}..." if len(first_sentence) > 50 else first_sentence
    return first_sentence[:70] + "..." if len(first_sentence) > 70 else first_sentence


def textrank_summarize(text: str, ratio: float = 0.2, language: str = "vietnamese", stop_words_path: str = None,
                       highlight: bool = True) -> dict:
    """
    Cập nhật hàm tóm tắt để trả về cả từ khóa và văn bản có đánh dấu

    Trả về:
        - dict: {
            "summary": văn bản tóm tắt,
            "keywords": danh sách từ khóa,
            "highlighted_summary": văn bản có từ khóa đánh dấu
        }
    """
    if not text.strip():
        raise ValueError("Nội dung văn bản không được để trống")

    # Chọn tokenizer
    if language.lower() == "vietnamese":
        tokenizer = VietnameseTokenizer()
    elif language.lower() == "english":
        tokenizer = EnglishTokenizer()
    else:
        raise ValueError(f"Ngôn ngữ không được hỗ trợ: {language}")

    # Tạo bản tóm tắt
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

    # Trích xuất từ khóa
    keywords = extract_keywords(text, language)

    # Tạo kết quả
    result = {
        "summary": summary,
        "keywords": keywords,
        "highlighted_summary": highlight_keywords(summary, keywords) if highlight else summary,
        "title": generate_title(summary, keywords, language)
    }

    return result