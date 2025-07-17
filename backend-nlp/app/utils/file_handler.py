import os
import fitz  # PyMuPDF
import chardet
from ebooklib import epub
from bs4 import BeautifulSoup
from docx import Document

def extract_text_from_txt(file_path):
    """
    Trích xuất văn bản từ tệp TXT hoặc Markdown.

    Đầu vào:
        - file_path (str): Đường dẫn đến tệp TXT/Markdown.

    Trả về:
        - str: Nội dung văn bản được trích xuất.

    Ngoại lệ:
        - ValueError: Nếu không thể đọc được tệp hoặc mã hóa không hợp lệ.
    """
    # Đọc nội dung tệp và xác định mã hóa
    with open(file_path, 'rb') as f:
        raw = f.read()
        encoding = chardet.detect(raw)['encoding']
        text = raw.decode(encoding or 'utf-8', errors='ignore')
    return text

def extract_text_from_docx(file_path):
    """
    Trích xuất văn bản từ tệp DOCX.

    Đầu vào:
        - file_path (str): Đường dẫn đến tệp DOCX.

    Trả về:
        - str: Nội dung văn bản được trích xuất.
    """
    # Đọc từng đoạn văn trong tài liệu
    doc = Document(file_path)
    full_text = [para.text for para in doc.paragraphs]
    return '\n'.join(full_text)

def extract_text_from_pdf(file_path):
    """
    Trích xuất văn bản từ tệp PDF sử dụng PyMuPDF.

    Đầu vào:
        - file_path (str): Đường dẫn đến tệp PDF.

    Trả về:
        - str: Nội dung văn bản được trích xuất.
    """
    # Trích xuất văn bản từ từng trang của PDF
    text = ""
    with fitz.open(file_path) as pdf:
        for page in pdf:
            text += page.get_text()
    return text

def extract_text_from_epub(file_path):
    """
    Trích xuất văn bản từ tệp EPUB sử dụng ebooklib và BeautifulSoup.

    Đầu vào:
        - file_path (str): Đường dẫn đến tệp EPUB.

    Trả về:
        - str: Nội dung văn bản được trích xuất.
    """
    # Đọc nội dung EPUB và trích xuất văn bản từ các tài liệu HTML
    book = epub.read_epub(file_path)
    text = ""
    for doc in book.get_items():
        if doc.get_type():
            content = doc.get_content().decode('utf-8', errors='ignore')
            soup = BeautifulSoup(content, 'html.parser')
            text += soup.get_text() + "\n"
    return text

def extract_text_from_md(file_path):
    """
    Trích xuất văn bản từ tệp Markdown.

    Đầu vào:
        - file_path (str): Đường dẫn đến tệp Markdown.

    Trả về:
        - str: Nội dung văn bản được trích xuất.
    """
    # Sử dụng hàm xử lý TXT cho Markdown
    return extract_text_from_txt(file_path)

def extract_text(file_path: str) -> str:
    """
    Trích xuất văn bản từ tệp dựa trên định dạng.

    Đầu vào:
        - file_path (str): Đường dẫn đến tệp cần trích xuất.

    Trả về:
        - str: Nội dung văn bản được trích xuất.

    Ngoại lệ:
        - ValueError: Nếu định dạng tệp không được hỗ trợ.
    """
    # Xác định phần mở rộng tệp
    ext = os.path.splitext(file_path)[-1].lower()
    if ext in ['.txt', '.md', '.markdown']:
        return extract_text_from_txt(file_path)
    elif ext == '.docx':
        return extract_text_from_docx(file_path)
    elif ext == '.pdf':
        return extract_text_from_pdf(file_path)
    elif ext == '.epub':
        return extract_text_from_epub(file_path)
    else:
        raise ValueError(f"Định dạng tệp không được hỗ trợ: {ext}")