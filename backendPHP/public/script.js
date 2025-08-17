

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


/////////////////////////////////////////////////////////////////////////////////////////////////////
let users = Array.from({ length: 0 }, (_, i) => ({
  username: `user${i + 1}`,
  role: "user",
}));

const updateUserTable = (data = users) => {
    const tbody = document.getElementById("userTableBody");
    if (!tbody) return;
    tbody.innerHTML = "";
    if (data.length === 0) {
        tbody.innerHTML =
            '<tr><td colspan="3" style="text-align: center;">Chưa có người dùng nào.</td></tr>';
        return;
    }
  data.forEach((user) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${user.username}</td>
            <td>${user.role}</td>
            <td class="action-buttons">
                <button onclick="changeRole('${user.username}')"><i class="fas fa-edit"></i></button>
                <button onclick="deleteUser('${user.username}')"><i class="fas fa-trash-alt"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    });
};
window.addUser = () => {
    const usernameInput = document.getElementById("newUserInput");
    const username = usernameInput.value.trim();
    const role = document.getElementById("roleSelect").value;
    if (username !== "") {
        users.unshift({ username, role });
        updateUserTable();
        usernameInput.value = "";
    } else {
        showModal("Lỗi", "Tên người dùng không được để trống.");
    }
};
window.changeRole = (username) => {
    const newRole = prompt("Nhập quyền mới (admin/user):");
    if (newRole && (newRole.trim().toLowerCase() === "admin" || newRole.trim().toLowerCase() === "user")) {
        const user = users.find((u) => u.username === username);
        if (user) user.role = newRole.trim().toLowerCase();
        updateUserTable();
    } else if (newRole !== null) {
        showModal(
            "Lỗi",
            'Quyền không hợp lệ. Vui lòng nhập "admin" hoặc "user".'
        );
    }
};
window.deleteUser = (username) => {
    users = users.filter((u) => u.username !== username);
    updateUserTable();
};
window.searchUsers = () => {
    const searchTerm = document
        .getElementById("userSearchInput")
        .value.toLowerCase();
    const filteredUsers = users.filter((user) =>
        user.username.toLowerCase().includes(searchTerm)
    );
    updateUserTable(filteredUsers);
};
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
let files = Array.from({ length: 0 }, (_, i) => ({
    name: `tệp_${i + 1}.txt`,
    date: new Date().toLocaleDateString("vi-VN"),
    status: "Đã duyệt",
  }));
const updateFileTable = (data = files) => {
    const tbody = document.getElementById("fileTableBody");
    if (!tbody) return;
    tbody.innerHTML = "";
    if (data.length === 0) {
        tbody.innerHTML =
            '<tr><td colspan="4" style="text-align: center;">Chưa có tệp nào.</td></tr>';
        return;
    }
    data.forEach((file) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${file.name}</td>
            <td>${file.date}</td>
            <td>${file.status}</td>
            <td class="action-buttons">
                <button onclick="approveFile('${file.name}')"><i class="fas fa-check"></i></button>
                <button onclick="deleteFile('${file.name}')"><i class="fas fa-trash-alt"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    });
};window.addFile = () => {
    const name = prompt("Nhập tên tệp:");
    if (name && name.trim() !== "") {
        files.unshift({
            name,
            date: new Date().toLocaleDateString("vi-VN"),
            status: "Chưa duyệt",
        });
        updateFileTable();
    } else if (name !== null) {
        showModal("Lỗi", "Tên tệp không được để trống.");
    }
};
window.approveFile = (name) => {
    const file = files.find((f) => f.name === name);
    if (file) file.status = "Đã duyệt";
    updateFileTable();
};
window.deleteFile = (name) => {
    files = files.filter((f) => f.name !== name);
    updateFileTable();
}
window.searchFiles = () => {
    const searchTerm = document
        .getElementById("fileSearchInput")
        .value.toLowerCase();
    const filteredFiles = files.filter((file) =>
        file.name.toLowerCase().includes(searchTerm)
    );
    updateFileTable(filteredFiles);
};
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// const updateHistoryTable = (data = history) => {
//     const tbody = document.getElementById("historyTableBody");
//     if (!tbody) return;
//     tbody.innerHTML = "";
//     if (data.length === 0) {
//         tbody.innerHTML =
//             '<tr><td colspan="3" style="text-align: center;">Chưa có lịch sử nào.</td></tr>';
//         return;
//     }
//     data.forEach((entry) => {
//         const row = document.createElement("tr");
//         row.innerHTML = `
//             <td>${entry.name}</td>
//             <td>${entry.date}</td>
//             <td>${entry.result}</td>
//         `;
//         tbody.appendChild(row);
//     });
// };
//////////////////////////////////////////////////////////////////////////////////////////////////////
  updateFileTable();
  updateUserTable();
  updateHistoryTable();
  document.getElementById("users").classList.remove("hidden");
  document.getElementById("files").classList.remove("hidden");
  document.getElementById("history").classList.remove("hidden");
}); 