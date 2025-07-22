from flask import Flask
from . import routes

# Khởi tạo ứng dụng Flask
app = Flask(__name__)

# Đăng ký Blueprint cho các tuyến tóm tắt văn bản
app.register_blueprint(routes.summarize_bp)