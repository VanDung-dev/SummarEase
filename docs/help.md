# Hướng dẫn chạy ứng dụng Summarease

## Chạy bằng terminal local (không dùng Docker)

### Yêu cầu hệ thống
- PHP 8.2+
- Python 3.12+
- MySQL 8.0+

### Bước 1: Cài đặt ban đầu

1. Cài đặt dependencies cho backend PHP:
```
cd backendPHP
composer install
```

2. Cài đặt dependencies cho backend NLP:
```
cd backendNLP
pip install -r requirements.txt
```

3. Tải xuống các tài nguyên NLTK cần thiết:
```
python -m nltk.downloader punkt stopwords
```

4. Sao chép và cấu hình file .env cho cả hai backend:
- backendPHP/.env.example → backendPHP/.env
- backendNLP/.env.example → backendNLP/.env

### Bước 2: Chạy các dịch vụ

Khi chạy server, cần thực hiện các quy trình sau:

1. Khởi động server cơ sở dữ liệu MySQL

2. Mở terminal 1 và chạy backend NLP:
```
cd backendNLP
python run.py
```

3. Mở terminal 2 và chạy backend PHP:
```
cd backendPHP
php artisan serve
```

### Xử lý lỗi Local
Nếu gặp lỗi test, hãy kiểm tra `.env.example` ở 2 thư mục `backendPHP` và `backendNLP` và điều chỉnh lại `.env`.

---

## Chạy bằng Docker

### Yêu cầu hệ thống
- Docker và docker-compose đã được cài đặt

### Khởi chạy Docker

Để chạy Docker với chế độ tự động build lại khi có thay đổi:
```
cd docker
docker-compose up -d --build
```

Nếu bạn muốn force rebuild tất cả các image:
```
cd docker
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Truy cập ứng dụng
Sau khi chạy Docker, ứng dụng sẽ chạy trên các địa chỉ sau:
- PHP frontend service: http://localhost:8000
- Python NLP service API: http://localhost:5001

### Quản lý container Docker
- Kiểm tra trạng thái container: `docker-compose ps`
- Xem log: `docker-compose logs -f`
- Dừng dịch vụ: `docker-compose down`
- Rebuild và restart services: `docker-compose up -d --build`

### Xử lý lỗi Docker
Nếu gặp lỗi test, hãy kiểm tra `.env.docker` ở 2 thư mục `backendPHP` và `backendNLP` và điều chỉnh lại `.env`.