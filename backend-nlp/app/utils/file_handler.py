import os
import fitz  # PyMuPDF
import chardet
import requests
from ebooklib import epub
from bs4 import BeautifulSoup
from docx import Document
from urllib.parse import urlparse  # Để kiểm tra URL hợp lệ

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

def extract_text_from_web(url: str) -> str:
    """
    Trích xuất văn bản từ trang web sử dụng requests và BeautifulSoup.

    Đầu vào:
        - url (str): Địa chỉ URL của trang web.

    Trả về:
        - str: Nội dung văn bản được trích xuất.

    Ngoại lệ:
        - ValueError: Nếu URL không hợp lệ hoặc không thể tải nội dung.
    """
    # Kiểm tra URL hợp lệ
    parsed_url = urlparse(url)
    if not parsed_url.scheme or not parsed_url.netloc:
        raise ValueError(f"URL không hợp lệ: {url}")

    # Gửi yêu cầu HTTP với header giả lập trình duyệt
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    }

    try:
        response = requests.get(url, headers=headers, timeout=10)
        response.raise_for_status()  # Kiểm tra mã trạng thái HTTP
    except requests.exceptions.RequestException as e:
        raise ValueError(f"Không thể tải trang web: {e}")

    # Xác định mã hóa và giải mã nội dung
    if response.encoding:
        encoding = response.encoding
    else:
        detected = chardet.detect(response.content)
        encoding = detected.get('encoding', 'utf-8')

    html_content = response.content.decode(encoding, errors='ignore')

    # Phân tích và trích xuất văn bản
    soup = BeautifulSoup(html_content, 'html.parser')

    # Loại bỏ các thẻ script và style
    for element in soup(['script', 'style', 'noscript', 'meta', 'link']):
        element.decompose()

    text = soup.get_text(separator='\n', strip=True)
    return text


def extract_text(source: str) -> str:
    """
    Trích xuất văn bản từ tệp hoặc trang web dựa trên đầu vào.

    Đầu vào:
        - source (str): Đường dẫn tệp hoặc URL trang web.

    Trả về:
        - str: Nội dung văn bản được trích xuất.

    Ngoại lệ:
        - ValueError: Nếu định dạng không được hỗ trợ.
    """
    # Kiểm tra nếu là URL web
    parsed = urlparse(source)
    if parsed.scheme in ['http', 'https']:
        return extract_text_from_web(source)

    # Xử lý file cục bộ
    if not os.path.exists(source):
        raise FileNotFoundError(f"Không tìm thấy tệp: {source}")

    ext = os.path.splitext(source)[-1].lower()
    if ext in ['.txt', '.md', '.markdown']:
        return extract_text_from_txt(source)
    elif ext == '.docx':
        return extract_text_from_docx(source)
    elif ext == '.pdf':
        return extract_text_from_pdf(source)
    elif ext == '.epub':
        return extract_text_from_epub(source)
    else:
        raise ValueError(f"Định dạng không được hỗ trợ: {ext}")