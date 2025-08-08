

const fileInput = document.getElementById("fileInput");
const fileList = document.getElementById("fileList");
const subtitle = document.querySelector(".sub");

const menu_btn = document.querySelector(".menu-btn");
const close_menu_btn = document.querySelector(".close-menu-btn");
const menu = document.querySelector("#menu");
const content = document.querySelector(".container-summary");



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

if (subtitle) {
    const subtitleText =
      "Công cụ hỗ trợ tóm tắt nhanh và chính xác các văn bản học thuật";
    typewriterEffect(subtitle, subtitleText, 100);
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




  // Khôi phục theme đã lưu khi tải trang
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra theme đã lưu trong localStorage
    const savedTheme = localStorage.getItem('theme');
    
    // Nếu có theme đã lưu, áp dụng nó
    if (savedTheme) {
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
    
    // Theo dõi sự thay đổi theme
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                // Kiểm tra xem class 'dark' có được thêm hay xóa không
                const isDark = document.documentElement.classList.contains('dark');
                // Lưu theme vào localStorage
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            }
        });
    });
    
    // Bắt đầu theo dõi thay đổi class của phần tử html
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});