## **Kiến trúc hệ thống (High-level Architecture)**

```aiignore
+----------------------------+
|          Frontend          | <==> Người dùng
|      React + Tailwind      |
+-------------+--------------+
              |
              v
+----------------------------+
|        Backend API         |
|   Laravel / Flask RESTful  |
+-------------+--------------+
              |
              v
+----------------------------+      +-----------------------------+
|     NLP Engine (Python)    | <--> |     LLM (T5/BART/OpenAI)    |
|TextRank / spaCy / T5 / BART|      |(Tóm tắt trừu tượng nâng cao)|
+-------------+--------------+      +-----------------------------+
              |
              v
+----------------------------+
|   Document Preprocessing   |
| PDF/DOCX/TXT => plain text |
+-------------+--------------+
              |
              v
+----------------------------+
|         Database           |
|          MySQL             |
+----------------------------+
```

---

## **Cấu trúc thư mục tổng thể**
- Lưu ý: đây là khung mẫu, không nhất thiết phải làm đúng theo yêu cầu `thư mục tổng thể`

```aiignore
SummarEase/
│
├── frontend/                    # Giao diện người dùng
│   ├── public/                  # File tĩnh (index.html, favicon, v.v.)
│   ├── src/                     # Source code chính
│   │   ├── assets/              # Hình ảnh, font, style tĩnh
│   │   ├── components/          # Các component React
│   │   ├── pages/               # Các trang chính (Home, Upload, Summary)
│   │   ├── services/            # API gọi Laravel
│   │   └── App.jsx              # Entry chính
│   └── package.json
│
├── backend-php/                 # Laravel backend chính
│   ├── app/                     # Controllers, Models, Services
│   │   ├── Http/Controllers/    # Gọi xử lý file, NLP, quản lý user
│   │   ├── Models/              # User, Document
│   ├── routes/
│   │   └── api.php              # Định nghĩa API chính
│   ├── database/                # Migration, Seeder
│   ├── storage/app/uploads/     # Thư mục chứa file người dùng
│   ├── public/                  # Thư mục public Laravel
│   └── .env                     # Thông số kết nối NLP, DB
│
├── backend-nlp/                 # Python NLP engine
│   ├── app/                     
│   │   ├── summarizer.py        # Script TextRank, xử lý đầu vào
│   │   ├── utils.py             # Hàm xử lý văn bản
│   │   └── __init__.py
│   ├── api.py                   # Flask app định nghĩa API POST /summarize
│   ├── requirements.txt         # Thư viện cần cài
│   └── run.sh                   # File chạy Flask app
│
├── .devcontainer/               # Cấu hình Devcontainer
│   └── docker-compose.yml
│
├── docker/                      # Cấu hình Docker
│   ├── Dockerfile-php
│   ├── Dockerfile-python
│   ├── Dockerfile-node
│   └── docker-compose.yml
│
├── .gitignore                   # Loại bỏ file không cần theo dõi
│
└── README.md                    # Mô tả dự án
```

---

## **Các Giai Đoạn Phát Triển & Ưu tiên**

### **Giai đoạn 1: Phiên bản MVP**

**Mục tiêu:** Tạo sản phẩm hoạt động được, hỗ trợ tóm tắt trích xuất văn bản cơ bản.

* Đăng ký/đăng nhập (Laravel Auth / Sanctum)
* Tải file PDF, DOCX, TXT → xử lý bằng `Python + textract / PyMuPDF / docx2txt`
* Làm sạch văn bản, chuẩn hóa, loại bỏ stopwords (`spaCy`, `nltk`)
* Thuật toán TextRank hoặc LexRank
* Giao diện đơn giản: form upload + hiển thị tóm tắt
* Tùy chọn độ dài tóm tắt (% hoặc số câu)
* Hiển thị song song bản gốc & tóm tắt (2 cột)

**Công nghệ nên dùng:**

* Frontend: ReactJS + Tailwind
* Backend: Laravel (REST API) + Python Script (microservice gọi bằng shell hoặc HTTP)
* NLP: Python (NLTK, spaCy, Gensim)
* Database: MySQL

---

### **Giai đoạn 2: Tối ưu hóa UX & NLP**

**Mục tiêu:** Trải nghiệm tốt hơn và tăng tính học thuật.

* In đậm/gạch chân câu được chọn từ văn bản gốc
* Giao diện hiện đại: “Highlight câu quan trọng trực tiếp”
* Lưu lịch sử tóm tắt (user-wise)
* Phân loại tài liệu (giáo trình, nghiên cứu, luận văn…)
* Hỗ trợ tiếng Việt tốt hơn (xử lý stopwords riêng, mô hình BERT/VietnameseBERT cho ranking)

---

### **Giai đoạn 3: AI nâng cao & Thông minh**

**Mục tiêu:** Tăng độ chính xác và tự nhiên của bản tóm tắt.

* **Tóm tắt trừu tượng**: dùng T5-small, BART hoặc tích hợp OpenAI GPT-4 API
* **Tóm tắt theo chủ đề** (chỉ tóm phần “phương pháp”, “kết quả”)
* **Tóm tắt nhiều tài liệu một lúc** (gộp nội dung, phân cụm theo chủ đề)
* **Chấm điểm bản tóm tắt**: mô hình đánh giá chất lượng (sử dụng BLEU, ROUGE, hoặc cosine similarity với bản chuẩn)

---

##  **Chi tiết các thành phần chính**

### 1. **Xử lý tài liệu**

| Loại file        | Công cụ                                                         |
| ---------------- | --------------------------------------------------------------- |
| TXT              | Python natively                                                 |
| DOCX             | `python-docx`, `docx2txt`                                       |
| PDF              | `PyMuPDF`, `pdfminer.six`, `fitz`, `pytesseract` (OCR optional) |
| Image (nâng cao) | `EasyOCR`, `Tesseract`                                          |

---

### 2. **Thuật toán tóm tắt trích xuất**

* **TextRank** (Gensim): dựa vào mối liên kết giữa các câu
* **LexRank**: dựa vào cosine similarity
* **Frequency-based**: lọc câu chứa nhiều từ khóa có trọng số cao
* **TF-IDF Summarizer**: thủ công nhưng hiệu quả

---

### 3. **Tóm tắt trừu tượng**

* Mô hình: `t5-base`, `facebook/bart-large-cnn`
* Tùy chọn tích hợp:

    * Local với transformers (nếu có GPU tốt)
    * Gọi qua API GPT-4 / Gemini
