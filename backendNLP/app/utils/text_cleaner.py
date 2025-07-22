import re


def clean_text(text):
    """
    Làm sạch văn bản bằng cách chuẩn hóa khoảng trắng và loại bỏ khoảng trắng thừa.

    Đầu vào:
        - text (str): Văn bản cần làm sạch.

    Trả về:
        - str: Văn bản đã được làm sạch.
    """
    # Chuẩn hóa khoảng trắng: thay nhiều khoảng trắng bằng một khoảng trắng duy nhất
    text = re.sub(r'\s+', ' ', text)

    # Loại bỏ khoảng trắng thừa ở đầu và cuối
    return text.strip()