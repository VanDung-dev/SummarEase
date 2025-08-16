

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


/* ==========================================================
   Xử lý sự kiện khi DOM đã tải
   ========================================================== */
document.addEventListener("DOMContentLoaded", () => {
  /* ==========================================================
       Khai báo biến và lấy các phần tử từ DOM
       ========================================================== */
  const menuToggle = document.getElementById("menu-toggle");
  const sidebar = document.querySelector(".sidebar");
  const sidebarLinks = document.querySelectorAll(".sidebar-menu li");
  const mainContentContainer = document.getElementById(
    "main-content-container"
  );
  const homeContent = mainContentContainer.innerHTML;
  const dashboardGrid = document.querySelector(".dashboard-grid");
  const dashboardChart = document.querySelector(".dashboard-chart");
  const modal = document.getElementById("custom-modal");
  const modalTitle = document.getElementById("modal-title");
  const modalMessage = document.getElementById("modal-message");
  const closeModalBtn = document.getElementById("close-modal");

  /* ==========================================================
       Dữ liệu giả lập
       ========================================================== */
  let files = Array.from({ length: 0 }, (_, i) => ({
  name: `tệp_${i + 1}.txt`,
  date: new Date().toLocaleDateString("vi-VN"),
  status: "Đã duyệt",
}));
let users = Array.from({ length: 0 }, (_, i) => ({
  username: `user${i + 1}`,
  role: "user",
}));


  /* ==========================================================
       Hàm xử lý Modal
       ========================================================== */
  const showModal = (title, message) => {
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    modal.style.display = "flex";
  };

  const closeModal = () => {
    modal.style.display = "none";
  };

  closeModalBtn.addEventListener("click", closeModal);
  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      closeModal();
    }
  });

  /* ==========================================================
       Hàm cập nhật giao diện Dashboard
       ========================================================== */
  const updateDashboardData = () => {
    const totalUsers = document.getElementById("total-users");
    const totalProducts = document.getElementById("total-products");
    const data = {
      users: users.length,
      products: files.length,
    };
    if (totalUsers) totalUsers.textContent = data.users;
    if (totalProducts) totalProducts.textContent = data.products;
  };

  /* ==========================================================
       Hàm cập nhật bảng dữ liệu
       ========================================================== */
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
};

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


  /* ==========================================================
       Các hàm xử lý tương tác người dùng
       ========================================================== */
  window.addFile = () => {
  const name = prompt("Nhập tên tệp:");
  if (name && name.trim() !== "") {
    files.unshift({
      name,
      date: new Date().toLocaleDateString("vi-VN"),
      status: "Chưa duyệt",
    });
    updateFileTable();
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
};
window.searchFiles = () => {
  const searchTerm = document
    .getElementById("fileSearchInput")
    .value.toLowerCase();
  const filteredFiles = files.filter((file) =>
    file.name.toLowerCase().includes(searchTerm)
  );
  updateFileTable(filteredFiles);
};
  window.addUser = () => {
    const usernameInput = document.getElementById("newUserInput");
    const username = usernameInput.value.trim();
    const role = document.getElementById("roleSelect").value;
    if (username !== "") {
      users.unshift({ username, role });
      updateUserTable();
      updateDashboardData();
      usernameInput.value = "";
    } else {
      showModal("Lỗi", "Tên người dùng không được để trống.");
    }
  };
  window.deleteUser = (username) => {
    users = users.filter((u) => u.username !== username);
    updateUserTable();
    updateDashboardData();
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
