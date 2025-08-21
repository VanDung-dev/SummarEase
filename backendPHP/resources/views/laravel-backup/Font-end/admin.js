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

  const updateHistoryTable = (data = history) => {
    const tbody = document.getElementById("historyTableBody");
    if (!tbody) return;
    tbody.innerHTML = "";
    if (data.length === 0) {
      tbody.innerHTML =
        '<tr><td colspan="3" style="text-align: center;">Chưa có lịch sử nào.</td></tr>';
      return;
    }
    data.forEach((entry) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${entry.name}</td>
                <td>${entry.date}</td>
                <td>${entry.result}</td>
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
      updateDashboardData();
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
    updateDashboardData();
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
  window.changeRole = (username) => {
    const newRole = prompt("Nhập quyền mới (admin/user):");
    if (
      newRole &&
      (newRole.trim().toLowerCase() === "admin" ||
        newRole.trim().toLowerCase() === "user")
    ) {
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
  window.addHistory = () => {
    const name = prompt("Nhập tên tệp:");
    if (name && name.trim() !== "") {
      history.unshift({
        name,
        date: new Date().toLocaleDateString("vi-VN"),
        result: "Tóm tắt thành công",
      });
      updateHistoryTable();
      updateDashboardData();
    } else if (name !== null) {
      showModal("Lỗi", "Tên tệp không được để trống.");
    }
  };
  window.searchHistory = () => {
    const searchTerm = document
      .getElementById("historySearchInput")
      .value.toLowerCase();
    const filteredHistory = history.filter((entry) =>
      entry.name.toLowerCase().includes(searchTerm)
    );
    updateHistoryTable(filteredHistory);
  };

  /* ==========================================================
       Xử lý sự kiện click cho Sidebar
       ========================================================== */
  menuToggle.addEventListener("click", () => {
    sidebar.classList.toggle("active");
    if (window.innerWidth > 768) {
      sidebar.classList.toggle("collapsed");
    }
  });

  sidebarLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      sidebarLinks.forEach((item) => item.classList.remove("active"));
      link.classList.add("active");
      document
        .querySelectorAll(".section")
        .forEach((section) => section.classList.add("hidden"));
      if (dashboardGrid) dashboardGrid.classList.add("hidden");
      if (dashboardChart) dashboardChart.classList.add("hidden");
      switch (link.id) {
        case "home-link":
          if (dashboardGrid) dashboardGrid.classList.remove("hidden");
          if (dashboardChart) dashboardChart.classList.remove("hidden");
          updateDashboardData();
          break;
        case "users-link":
          document.getElementById("users").classList.remove("hidden");
          updateUserTable();
          break;
        case "products-link":
          document.getElementById("files").classList.remove("hidden");
          updateFileTable();
          break;
        case "summary-history-link":
          document.getElementById("history").classList.remove("hidden");
          updateHistoryTable();
          break;
        case "analysis-link":
          break;
        case "settings-link":
          break;
        case "logout-link":
          window.location.href = "index.html";
          return;
      }
    });
  });

  /* ==========================================================
       Khởi tạo ban đầu
       ========================================================== */
  updateDashboardData();
  updateFileTable();
  updateUserTable();
  updateHistoryTable();
});
