FROM ubuntu:24.04

LABEL \
    name="SummarEase Dev Environment" \
    version="1.0" \
    description="Multi-language dev setup: PHP 8.2 + Python 3.12" \
    maintainer="Nguyen Le Van Dung <dungnguyen2661@gmail.com>" \
    authors="Nguyen Le Van Dung" \
    license="MIT" \
    repository="https://github.com/VanDung-dev/SummarEase"

# Thiết lập múi giờ cho Việt Nam
ENV TZ=Asia/Ho_Chi_Minh
RUN apt-get update && apt-get install -y tzdata && \
    ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Công cụ hệ thống
RUN apt-get install -y \
    curl \
    git \
    unzip \
    zip \
    nano \
    software-properties-common \
    build-essential \
    wget \
    lsb-release \
    ca-certificates

# --- PHP 8.2 ---
RUN add-apt-repository ppa:ondrej/php -y && \
    apt-get update && \
    apt-get install -y php8.2 php8.2-cli php8.2-common php8.2-curl php8.2-mbstring php8.2-xml php8.2-mysql php8.2-zip

# --- Python 3.12 ---
RUN add-apt-repository ppa:deadsnakes/ppa -y && \
    apt-get update && \
    apt-get install -y python3.12 python3.12-dev python3.12-venv python3-pip

# Workspace làm việc
WORKDIR /app

# Mở các cổng dịch vụ phổ biến
EXPOSE 8080 5001

# Khởi động bằng bash
CMD ["bash"]