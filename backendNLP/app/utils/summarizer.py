from sumy.parsers.plaintext import PlaintextParser
from sumy.summarizers.text_rank import TextRankSummarizer
from .tokenizer import VietnameseTokenizer, EnglishTokenizer
from collections import Counter
import string
import math
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


def extract_keywords(text: str, language: str = "vietnamese", n_keywords: int = 5) -> list:
    """
    Trích xuất các từ khóa quan trọng từ văn bản sử dụng phương pháp TF-IDF cải tiến.

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
    if not sentences:
        return []

    words = []
    sentence_words = []

    # Xử lý từng câu
    for sentence in sentences:
        sent_words = [word.lower() for word in tokenizer.to_words(sentence)
                      if word not in string.punctuation and len(word) > 2]
        sentence_words.append(sent_words)
        words.extend(sent_words)

    if not words:
        return []

    # Tải từ dừng
    # Xác định đường dẫn tới file stopwords.txt
    current_dir = os.path.dirname(os.path.abspath(__file__))
    stop_words_path = os.path.join(current_dir, '..', 'stopwords.txt')
    stop_words = load_stop_words(stop_words_path)

    # Loại bỏ từ dừng
    words = [word for word in words if word not in stop_words]
    sentence_words = [[word for word in sent_words if word not in stop_words]
                      for sent_words in sentence_words]

    # Đếm tần suất xuất hiện của từ
    word_counts = Counter(words)
    total_words = len(words)

    # Tính TF (Term Frequency)
    tf_scores = {}
    for word, count in word_counts.items():
        tf_scores[word] = count / total_words if total_words > 0 else 0

    # Tính IDF (Inverse Document Frequency) - mô phỏng với từng câu như một "tài liệu"
    idf_scores = {}
    total_sentences = len(sentences)

    for word in word_counts.keys():
        # Đếm số câu chứa từ này
        containing_sentences = sum(1 for sent_words in sentence_words if word in sent_words)
        # Tránh chia cho 0 và làm mịn IDF
        if containing_sentences > 0:
            idf_scores[word] = math.log(total_sentences / containing_sentences) + 1
        else:
            idf_scores[word] = 1

    # Tính TF-IDF score
    tf_idf_scores = {}
    for word in word_counts.keys():
        tf_idf_scores[word] = tf_scores[word] * idf_scores[word]

    # Sắp xếp theo TF-IDF score giảm dần
    sorted_keywords = sorted(tf_idf_scores.items(), key=lambda x: x[1], reverse=True)

    # Lấy top n từ khóa
    keywords = [word for word, score in sorted_keywords[:n_keywords] if score > 0]

    # Nếu không có từ khóa nào với TF-IDF > 0, quay lại phương pháp đơn giản
    if not keywords:
        keywords = [word for word, count in word_counts.most_common(n_keywords)]

    return keywords


def highlight_keywords(text: str, keywords: list) -> str:
    """
    Đánh dấu các từ khóa trong văn bản tóm tắt

    Đầu vào:
        - summary (str): Văn bản tóm tắt
        - keywords (list): Danh sách từ khóa

    Trả về:
        - str: Văn bản với các từ khóa được đánh dấu
    """
    # Sắp xếp các từ khóa theo độ dài giảm dần để tránh thay thế một phần
    sorted_keywords = sorted(keywords, key=len, reverse=True)

    highlighted_text = text
    for keyword in sorted_keywords:
        # Chỉ đánh dấu từ khóa nếu nó không phải là một phần của từ khác
        # Sử dụng một biểu thức đơn giản để kiểm tra ranh giới từ
        import re
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