# Hướng dẫn chạy ứng dụng Summarease

## Chạy bằng terminal thông thường (không dùng Docker)

### Bước 1: Cài đặt ban đầu
```
cd backendPHP
composer install
```
Nhớ thêm file .env

### Bước 2: Chạy các dịch vụ
Khi chạy server, cần thực hiện các quy trình sau:

1. Khởi động server cở sở dữ liệu MySQL

2. Mở terminal 1 và chạy lệnh:
```
cd backendNLP
python run.py
```
> Nếu lỗi thì chạy lệnh `pip install -r requirements.txt` và `python -m nltk.downloader punkt stopwords`

3. Mở terminal 2 và chạy lệnh:
```
cd backendPHP
php artisan serve
```
> Nếu lỗi thì chạy lệnh `composer install`

### Xử lý lỗi
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