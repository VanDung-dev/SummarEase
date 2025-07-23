```
cd backendPHP
composer install
npm install
npm audit fix
```
nhớ thêm file .env
```
php artisan serve
```

nếu lỗi test thì hãy kiểm tra .env.example và cập nhật những thành phần sau vào .env:
- GOOGLE_REDIRECT=http://127.0.0.1:8000/auth/callback
- GOOGLE_CLIENT_ID=
- GOOGLE_CLIENT_SECRET=
- APP_URL=http://localhost:8000
- API_BASE_URI=http://localhost:5001

khi chạy server php thì cần chạy thực hiện các quy trình sau:
- khởi động laragon để chạy server php
- chạy lệnh `python backendNLP/run.py` (termial 1)
- chạy lệnh `php backendPHP/artisan serve --working-path=backendPHP` (terminal 2)
