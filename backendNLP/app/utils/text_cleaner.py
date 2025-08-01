import re


def clean_text(text):
    """
    Làm sạch văn bản bằng cách hợp nhất nhiều đoạn văn thành một đoạn duy nhất,
    chuẩn hóa khoảng trắng và loại bỏ khoảng trắng thừa.

    Đầu vào:
        - text (str): Văn bản cần làm sạch, có thể chứa nhiều đoạn văn.

    Trả về:
        - str: Văn bản đã được làm sạch và hợp nhất thành một đoạn.
    """
    # Hợp nhất các đoạn văn: thay thế các dấu xuống dòng hoặc nhiều khoảng trắng bằng một khoảng trắng duy nhất
    text = re.sub(r'[\n\r]+', ' ', text)

    # Chuẩn hóa khoảng trắng: thay nhiều khoảng trắng bằng một khoảng trắng duy nhất
    text = re.sub(r'\s+', ' ', text)

    # Loại bỏ khoảng trắng thừa ở đầu và cuối
    return text.strip()