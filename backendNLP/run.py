from app.main import app
from dotenv import load_dotenv

load_dotenv()

import os
if not os.path.exists('.env'):
    print("Error: .env Tệp bị thiếu. Vui lòng tạo tệp .env dựa trên .env.example và thêm cấu hình của bạn.")
    print("Thoát khỏi ứng dụng.")
    exit(1)

try:
    if __name__ == "__main__":
        app.run(host="0.0.0.0", port=5001)
except Exception as e:
    print(f"Đã xảy ra lỗi khi khởi động máy chủ: {e}")
    exit(1)