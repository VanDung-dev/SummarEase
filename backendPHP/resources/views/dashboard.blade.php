 <link href="style.css" rel="stylesheet" />

<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div> -->
        </div>
        
        <h1 class="title">Tóm tắt văn bản học thuật</h1>

        <p class="sub">
        Công cụ hỗ trợ tóm tắt nhanh và chính xác các văn bản học thuật
        </p>

        <div class="main-dasboard">
        <div class="section upload-section">
          <label class="textarea-label"
            >Văn bản hay tệp cần tóm tắt (.txt, .docx, pdf)</label
          >
          <div class="input-area">
            <textarea
              class="text-input"
              placeholder="Nhập văn bản cần tóm tắt..."
            ></textarea>
            <div class="file-row">
              <div class="file-upload-controls">
                <p class="file-note">
                  Lưu ý dung lượng tệp phải nhỏ hơn hoặc bằng 10MB
                </p>
                <input
                  type="file"
                  id="fileInput"
                  multiple
                  style="display: none"
                  accept=".pdf,.doc,.docx,.txt"
                />
                <div class="file-selection-container">
                  <button
                    type="button"
                    class="file-btn"
                    onclick="document.getElementById('fileInput').click()"
                  >
                    <i class="fa-solid fa-paperclip"></i> chọn tệp
                  </button>
                  <div class="file-list-wrapper">
                    <div id="fileList"></div>
                  </div>
                </div>
                <div class="action-btns">
                  <button class="send-btn">
                    <i class="fa-solid fa-microphone"></i>
                  </button>
                  <button class="mic-btn">
                    <i class="fa-solid fa-arrow-up"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="section summary-section">
            <label class="textarea-label">Nội dung được tóm tắt</label>
            <div class="output-area"></div>
          </div>
        </div>
    </div>


    <script src="https://kit.fontawesome.com/af877c9b83.js" crossorigin="anonymous"></script>
        

    <script src="script.js"></script>

    <!-- Thêm script để xử lý markdown -->
    <script src="{{ asset('js/script.js') }}"></script>
</x-layouts.app>
