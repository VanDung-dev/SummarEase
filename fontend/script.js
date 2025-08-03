/* Chờ DOM được tải hoàn toàn trước khi chạy script */
document.addEventListener("DOMContentLoaded", function () {
  /* Lấy tham chiếu đến phần tử login-container */
  const loginContainer = document.querySelector(".login-container");
  /* Lấy tham chiếu đến phần tử container-summary */
  const summaryContainer = document.querySelector(".container-summary");
  /* Lấy tham chiếu đến liên kết hiển thị form đăng nhập */
  const showLoginLink = document.querySelector(".in-login");
  /* Lấy tham chiếu đến liên kết đăng ký */
  const registerLink = document.querySelector("#registerLink");
  /* Lấy tham chiếu đến nút đăng nhập với Google */
  const googleLoginBtn = document.querySelector(".google-login-btn");
  /* Lấy tham chiếu đến phần tử subtitle */
  const subtitle = document.querySelector(".subtitle");

  // Hiển thị menu
  const menu_btn = document.querySelector(".menu-btn");
  const menu = document.querySelector("#menu");

  menu_btn.addEventListener("click", () => {
    menu.classList.toggle("show");
    menu_btn.classList.toggle("opening");
    menu.style.display = "flex";
  });

  /* Hàm hiển thị login-container và ẩn container-summary */
  function showLogin() {
    loginContainer.style.display = "block"; /* Hiển thị login-container */
    summaryContainer.style.display = "none"; /* Ẩn container-summary */
    menu.style.display = "none";
  }

  /* Hàm hiển thị container-summary và ẩn login-container */
  function showSummary() {
    loginContainer.style.display = "none"; /* Ẩn login-container */
    summaryContainer.style.display = "block"; /* Hiển thị container-summary */
    menu.style.display = "flex";
    menu.style.display = "none";
  }

  /* Thêm hiệu ứng typewriter cho subtitle */
  function typewriterEffect(element, text, speed = 100) {
    let i = 0;
    element.textContent = ""; /* Xóa nội dung ban đầu */
    function type() {
      if (i < text.length) {
        element.textContent += text.charAt(i);
        i++;
        setTimeout(type, speed); /* Tốc độ gõ (ms) */
      }
    }
    type();
  }

  /* Thêm sự kiện click cho liên kết hiển thị login */
  if (showLoginLink) {
    showLoginLink.addEventListener("click", function (e) {
      e.preventDefault(); /* Ngăn hành vi mặc định của liên kết */
      showLogin(); /* Hiển thị form đăng nhập */
    });
  }

  /* Thêm sự kiện click cho liên kết đăng ký */
  if (registerLink) {
    registerLink.addEventListener("click", function (e) {
      e.preventDefault(); /* Ngăn hành vi mặc định */
      alert(
        "Chuyển đến trang đăng ký (chưa được triển khai)"
      ); /* Thông báo tạm thời */
    });
  }

  /* Thêm sự kiện click cho nút đăng nhập với Google */
  if (googleLoginBtn) {
    googleLoginBtn.addEventListener("click", function (e) {
      e.preventDefault(); /* Ngăn hành vi mặc định */
      showSummary(); /* Chuyển sang container-summary */
    });
  }

  /* Áp dụng hiệu ứng typewriter khi trang tải */
  if (subtitle) {
    const subtitleText =
      "Công cụ hỗ trợ tóm tắt nhanh và chính xác các văn bản học thuật";
    typewriterEffect(subtitle, subtitleText, 100); /* Tốc độ 100ms mỗi ký tự */
  }

  /* Hiển thị container-summary mặc định */
  showSummary();
});
