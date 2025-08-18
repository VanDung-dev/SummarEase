
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

