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

---

nếu lỗi test thì hãy kiểm tra .env.example và cập nhật những thành phần sau vào .env:
- GOOGLE_REDIRECT=http://127.0.0.1:8000/auth/callback
- GOOGLE_CLIENT_ID=
- GOOGLE_CLIENT_SECRET=
- APP_URL=http://localhost:8000
- API_BASE_URI=http://localhost:5001

---

khi chạy server php thì cần chạy thực hiện các quy trình sau:
- khởi động laragon để chạy server php
- Mở terminal 1 và chạy lệnh
```
cd backendNLP
python run.py
```
> nếu lỗi thì chạy lệnh `pip install -r requirements.txt`
- Mở terminal 2 và chạy lệnh
```
cd backendPHP
php artisan serve
```
> nếu lỗi thì chạy lệnh `composer install`
