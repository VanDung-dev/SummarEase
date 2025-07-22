from typing import List
from nltk.tokenize import sent_tokenize, word_tokenize


class VietnameseTokenizer:
    """
    Tokenizer cho tiếng Việt sử dụng NLTK để phân tách câu và từ.

    Sử dụng tokenizer mặc định của NLTK, phù hợp cho văn bản tiếng Việt đơn giản.
    """
    @staticmethod
    def to_sentences(text: str) -> List[str]:
        """
        Phân tách văn bản thành danh sách các câu.

        Đầu vào:
            - text (str): Văn bản cần phân tách.

        Trả về:
            - List[str]: Danh sách các câu được phân tách.
        """
        # Sử dụng NLTK để phân tách câu
        return sent_tokenize(text)

    @staticmethod
    def to_words(sentence: str) -> List[str]:
        """
        Phân tách câu thành danh sách các từ.

        Đầu vào:
            - sentence (str): Câu cần phân tách.

        Trả về:
            - List[str]: Danh sách các từ được phân tách.
        """
        # Sử dụng NLTK để phân tách từ
        return word_tokenize(sentence)

class EnglishTokenizer:
    """
    Tokenizer cho tiếng Anh sử dụng NLTK để phân tách câu và từ.

    Được sử dụng để xử lý văn bản tiếng Anh trong Sumy.
    """
    @staticmethod
    def to_sentences(text: str) -> List[str]:
        """
        Phân tách văn bản thành danh sách các câu.

        Đầu vào:
            - text (str): Văn bản cần phân tách.

        Trả về:
            - List[str]: Danh sách các câu được phân tách.
        """
        # Sử dụng NLTK để phân tách câu
        return sent_tokenize(text)

    @staticmethod
    def to_words(sentence: str) -> List[str]:
        """
        Phân tách câu thành danh sách các từ.

        Đầu vào:
            - sentence (str): Câu cần phân tách.

        Trả về:
            - List[str]: Danh sách các từ được phân tách.
        """
        # Sử dụng NLTK để phân tách từ
        return word_tokenize(sentence)