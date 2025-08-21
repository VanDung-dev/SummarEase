# SummarEase - Ứng dụng tóm tắt tài liệu đơn giản

## Giới thiệu

SummarEase là một ứng dụng tóm tắt tài liệu thông minh, giúp người dùng tiết kiệm thời gian bằng cách tự động rút gọn nội dung văn bản từ các định dạng như PDF, DOCX và TXT. Ứng dụng hỗ trợ cả tóm tắt trích xuất (sử dụng TextRank) và tóm tắt trừu tượng (sử dụng các mô hình AI như T5, BART, Gemini).

## Tính năng chính

- Đăng ký/đăng nhập người dùng
- Tải lên và xử lý tài liệu (PDF, DOCX, TXT, EPUB, MD)
- Tóm tắt văn bản sử dụng thuật toán TextRank
- Tóm tắt nâng cao sử dụng AI (Gemini API)
- Tùy chọn độ dài bản tóm tắt
- Hiển thị nội dung gốc và bản tóm tắt
- Lưu lịch sử tóm tắt theo người dùng
- Trích xuất từ khóa quan trọng
- Tự động tạo tiêu đề cho bản tóm tắt

## Kiến trúc hệ thống

```
+----------------------------+
|          Frontend          | <==> Người dùng
|        Blade + CSS         |
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
|     NLP Engine (Python)    | <--> |     LLM (Gemini API)        |
|TextRank / spaCy / T5 / BART|      |(Tóm tắt trừu tượng nâng cao)|
+-------------+--------------+      +-----------------------------+
              |
              v
+----------------------------+      +-----------------------------+
| Document Preprocessing     |      | OCR Engine (nâng cao)       |
| PDF/DOCX/TXT => plain text | <--> | EasyOCR / Tesseract         |
+----------------------------+      +-----------------------------+
              |
              v
+----------------------------+
|         Database           |
|          MySQL             |
+----------------------------+
```

## Công nghệ sử dụng

### Backend PHP (Laravel)
- **Framework**: Laravel 10+
- **Ngôn ngữ**: PHP 8.2
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum
- **Frontend**: Blade Templates, CSS

### Backend NLP (Python)
- **Framework**: Flask
- **Ngôn ngữ**: Python 3.12
- **Xử lý ngôn ngữ tự nhiên**: NLTK, spaCy, sumy
- **Xử lý tài liệu**: PyMuPDF, python-docx, ebooklib
- **AI/LLM**: Gemini API (gemini-1.5-flash)

### Frontend
- **Template Engine**: Blade (Laravel)
- **Styling**: CSS thuần + Tailwind CSS

### DevOps
- **Containerization**: Docker
- **Web Server**: Apache/Nginx (thông qua Laragon)

## Cấu trúc thư mục

```
SummarEase/
├── backendPHP/                  # Laravel backend chính
│   ├── app/                     # Controllers, Models, Services
│   │   ├── Http/Controllers/    # Các controller xử lý yêu cầu
│   │   ├── Models/              # Các model cơ sở dữ liệu
│   │   └── Services/            # Dịch vụ xử lý logic
│   ├── resources/views/         # Giao diện người dùng (Blade templates)
│   ├── routes/                  # Định nghĩa API/Web routes
│   └── ...
├── backendNLP/                  # Python NLP engine
│   ├── app/                     
│   │   ├── utils/               # Các tiện ích xử lý văn bản
│   │   ├── routes.py            # API endpoints cho tóm tắt
│   │   └── main.py              # Khởi tạo ứng dụng Flask
│   ├── tests/                   # Unit tests
│   ├── requirements.txt         # Thư viện Python cần cài
│   └── ...
├── docker/                      # Cấu hình Docker
└── testsAPI/                    # API tests (Bruno)
```

## Cài đặt và chạy dự án

### Yêu cầu hệ thống
- PHP 8.2+
- Python 3.12+
- MySQL 8.0+
- Node.js 22.x (cho Tailwind CSS)
- Docker (tùy chọn)

### Cài đặt

1. **Clone repository:**
```bash
git clone <repository-url>
cd SummarEase
```

2. **Cài đặt dependencies cho backend PHP:**
```bash
cd backendPHP
composer install
```

3. **Cài đặt dependencies cho backend NLP:**
```bash
cd ../backendNLP
pip install -r requirements.txt
```

4. **Cài đặt dependencies cho frontend (Tailwind CSS):**
```bash
npm install
```

### Cấu hình

1. **Backend PHP:**
   - Tạo file `.env` từ `.env.example`
   - Cấu hình database connection
   - Chạy migration: `php artisan migrate`

2. **Backend NLP:**
   - Tạo file `.env` từ `.env.example`
   - Thêm Gemini API key: `GEMINI_API_KEY=your_api_key_here`

### Chạy dự án

1. **Chạy backend PHP:**
```bash
cd backendPHP
php artisan serve
```

2. **Chạy backend NLP:**
```bash
cd backendNLP
python run.py
```

3. **Chạy frontend (development):**
```bash
npm run dev
```

4. **Chạy với Docker (nếu có):**
```bash
cd docker
docker-compose up -d
```

## API Endpoints

### Backend PHP (Laravel)
- `GET /` - Trang chủ
- `GET /dashboard` - Bảng điều khiển người dùng
- `POST /api/summarize` - Tóm tắt văn bản
- `GET /history` - Lịch sử tóm tắt

### Backend NLP (Flask)
- `POST /summarize` - Tóm tắt văn bản với TextRank
- `POST /summarize-files` - Tóm tắt từ file với TextRank
- `POST /summarize-url` - Tóm tắt từ URL với TextRank
- `POST /summarize-gemini` - Tóm tắt văn bản với Gemini
- `POST /summarize-file-gemini` - Tóm tắt từ file với Gemini
- `POST /summarize-url-gemini` - Tóm tắt từ URL với Gemini

## Testing

### Backend NLP
```bash
cd backendNLP
python -m pytest tests/
```

## Đóng góp

1. Fork repository
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit thay đổi (`git commit -m 'Add some AmazingFeature'`)
4. Push lên branch (`git push origin feature/AmazingFeature`)
5. Mở Pull Request

## Giấy phép

Dự án được cấp phép dưới giấy phép MIT - xem file [LICENSE](LICENSE) để biết thêm chi tiết.
