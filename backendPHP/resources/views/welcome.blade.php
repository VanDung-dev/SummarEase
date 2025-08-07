


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SummarEase</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="style.css" rel="stylesheet" />

        @include('partials.head')

    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">


      <div class="container-summary" id="summary-page">
      <div class="header">
        <h1 class="title">Tóm tắt văn bản học thuật</h1>
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="login"
                        >
                            Quay lại trang chủ
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="login"
                        >
                            Đăng nhập
                        </a>
                    @endauth
                </nav>
            @endif
        </header>
      </div>
      <p class="sub">
        Công cụ hỗ trợ tóm tắt nhanh và chính xác các văn bản học thuật
      </p>
      <div class="main">
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

        
      </div>
      



    <script src="https://kit.fontawesome.com/af877c9b83.js" crossorigin="anonymous"></script>
        
    @if (Route::has('login'))
      <div class="h-14.5 hidden lg:block"></div>
    @endif

    <script src="script.js"></script>


    </body>
    
</html>


