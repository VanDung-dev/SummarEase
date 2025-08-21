/* ==========================================================
  Xử lý sự kiện khi DOM được tải
  ========================================================== */
document.addEventListener("DOMContentLoaded", function () {
  const loginContainer = document.querySelector(".login-container");
  const summaryContainer = document.querySelector(".container-summary");
  const showLoginLink = document.querySelector(".in-login");
  const userIcon = document.getElementById("userIcon");
  const registerLink = document.querySelector("#registerLink");
  const googleLoginBtn = document.querySelector(".google-login-btn");
  const subtitle = document.querySelector(".subtitle");
  const logoutLink = document.querySelector(".logout a");
  const logoutSection = document.getElementById("logoutSection");
  const fileInput = document.getElementById("fileInput");
  const fileList = document.getElementById("fileList");
  const menu_btn = document.querySelector(".menu-btn");
  const close_menu_btn = document.querySelector(".close-menu-btn");
  const menu = document.querySelector("#menu");
  const content = document.querySelector(".container-summary");

  /* ==========================================================
    Các hàm điều khiển hiển thị giao diện
    ========================================================== */
  function showLogin() {
    loginContainer.style.display = "block";
    summaryContainer.style.display = "none";
    menu.classList.remove("show");
    content.classList.remove("menu-show");
    showLoginLink.classList.remove("disabled");
    showLoginLink.childNodes[0].nodeValue = "Đăng nhập";
    userIcon.style.display = "none";
    logoutSection.style.display = "none";
    menu_btn.classList.add("hidden");
    close_menu_btn.classList.add("hidden");
  }

  function showSummary() {
    loginContainer.style.display = "none";
    summaryContainer.style.display = "block";
    showLoginLink.classList.add("disabled");
    showLoginLink.childNodes[0].nodeValue = "";
    userIcon.style.display = "inline-block";
    logoutSection.style.display = "flex";
    menu_btn.classList.remove("hidden");
    close_menu_btn.classList.add("hidden");
  }

  function typewriterEffect(element, text, speed = 100) {
    let i = 0;
    element.textContent = "";
    function type() {
      if (i < text.length) {
        element.textContent += text.charAt(i);
        i++;
        setTimeout(type, speed);
      }
    }
    type();
  }

  /* ==========================================================
    Xử lý sự kiện cho các nút và liên kết
    ========================================================== */
  menu_btn.addEventListener("click", () => {
    menu.classList.add("show");
    content.classList.add("menu-show");
    menu_btn.classList.add("hidden");
    close_menu_btn.classList.remove("hidden");
  });

  close_menu_btn.addEventListener("click", () => {
    menu.classList.remove("show");
    content.classList.remove("menu-show");
    menu_btn.classList.remove("hidden");
    close_menu_btn.classList.add("hidden");
  });

  if (showLoginLink) {
    showLoginLink.addEventListener("click", function (e) {
      e.preventDefault();
      if (!showLoginLink.classList.contains("disabled")) {
        showLogin();
      }
    });
  }

  if (registerLink) {
    registerLink.addEventListener("click", function (e) {
      e.preventDefault();
      alert("Chuyển đến trang đăng ký (chưa được triển khai)");
    });
  }

  if (googleLoginBtn) {
    googleLoginBtn.addEventListener("click", function (e) {
      e.preventDefault();
      showSummary();
    });
  }

  if (logoutLink) {
    logoutLink.addEventListener("click", function (e) {
      e.preventDefault();
      location.reload();
    });
  }

  if (subtitle) {
    const subtitleText =
      "Công cụ hỗ trợ tóm tắt nhanh và chính xác các văn bản học thuật";
    typewriterEffect(subtitle, subtitleText, 100);
  }

  /* ==========================================================
    Xử lý sự kiện tải tệp
    ========================================================== */
  if (fileInput) {
    fileInput.addEventListener("change", function () {
      const files = this.files;
      fileList.innerHTML = "";

      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.size > 10 * 1024 * 1024) {
          alert("Tệp vượt quá 10MB. Vui lòng chọn tệp khác.");
          fileInput.value = "";
          return;
        }

        const fileExtension = file.name.split(".").pop().toLowerCase();
        if (!["pdf", "doc", "docx", "txt"].includes(fileExtension)) {
          alert("Chỉ hỗ trợ tệp .pdf, .doc, .docx, .txt.");
          fileInput.value = "";
          return;
        }

        const fileItem = document.createElement("div");
        fileItem.className = "file";
        const icon = document.createElement("i");
        if (fileExtension === "pdf") {
          icon.className = "fas fa-file-pdf";
          icon.style.color = "#e74c3c";
        } else if (fileExtension === "doc" || fileExtension === "docx") {
          icon.className = "fas fa-file-word";
          icon.style.color = "#3a7bd5";
        } else if (fileExtension === "txt") {
          icon.className = "fas fa-file-alt";
          icon.style.color = "#888";
        } else {
          icon.className = "fas fa-file";
          icon.style.color = "#444";
        }

        fileItem.appendChild(icon);
        fileItem.appendChild(document.createTextNode(" " + file.name));

        const removeIcon = document.createElement("i");
        removeIcon.className = "fas fa-xmark";
        removeIcon.style.marginLeft = "8px";
        removeIcon.style.cursor = "pointer";
        removeIcon.style.color = "#999";
        removeIcon.addEventListener("click", () => fileItem.remove());

        fileItem.appendChild(removeIcon);
        fileList.appendChild(fileItem);
      }
    });
  }
});
