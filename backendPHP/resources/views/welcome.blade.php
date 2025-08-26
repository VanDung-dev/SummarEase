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


        @include('partials.head')

    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">


      <div class="container-summary" id="summary-page">
      <div class="header">
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
            <!-- <a style="margin-top: 2.5rem" href="{{ route('history') }}" class="login">Xem lịch sử</a> -->
        </header>
      </div>
       <h1 class="title">Tóm tắt văn bản học thuật</h1>

        <p class="sub">
        Công cụ hỗ trợ tóm tắt nhanh và chính xác các văn bản học thuật
        </p>

           <form method="POST" action="{{ url('/summarize/text') }}">
                    @csrf
                    <input type="hidden" name="is_guest" value="true">
                    <input type="hidden" name="guest_id" value="{{ session()->getId() }}">
                    <textarea style="text-align: justify;" name="text" id="text" class="input-areah" placeholder="Hãy nhập văn bản cần tóm tắt...">{{ session('original_text') ?? '' }}</textarea>
                    <p style="text-align: right; margin-bottom: -5px; margin-top: -15px; margin-right: 140px; color: white;">Tỉ lệ: <span id="ratioValue">{{ (session('original_ratio') ?? 0.5) *100 . '%' }}</span></p>
                    <input type="range" name="ratio" id="ratio" min="0" max="1" step="0.1" value="{{ session('original_ratio') }}" class="mt-4 w-full" />
                    <script>
                    const slider = document.getElementById('ratio');
                    const display = document.getElementById('ratioValue');

                    slider.addEventListener('input', () => {
                    display.textContent = slider.value * 100 + '%';
                    });
                    </script>
                    <button type="submit" class="mt-4 submit-button">{{ __('Tóm tắt') }}</button>

            </form>

          <div class="output-areah">
              @if(session('summary'))
                      <div id="summary-output" style="text-align: justify;">{!! nl2br(e(session('summary'))) !!}</div>
                  <script>
                      document.addEventListener('DOMContentLoaded', function() {
                          // Xử lý hiển thị markdown cho kết quả tóm tắt
                          const summaryText = @json(session('summary'));
                          const outputArea = document.getElementById('summary-output');
                          // Chuyển đổi markdown thành HTML
                          const html = summaryText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                          const html_i = html.replace(/\*(.*?)\*/g, '<i>$1</i>');
                          outputArea.innerHTML = html_i;
                      });
                  </script>
              @else
                  <div style="position: relative; padding-top: 2rem; min-height: 3rem;">
                      <div id="summary-output" style="text-align: justify;"></div>
                  </div>
              @endif
          </div>
          <button id="copy-button" class="mt-4 submit-button w-full text-center" style="margin-top: 1rem;">Sao chép</button>

        </div>

    <script src="https://kit.fontawesome.com/af877c9b83.js" crossorigin="anonymous"></script>

    @if (Route::has('login'))
      <div class="h-14.5 hidden lg:block"></div>
    @endif

    <script src="script.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Thêm event listener cho nút sao chép
        const copyButton = document.getElementById('copy-button');
        if (copyButton) {
            copyButton.addEventListener('click', function() {
                const summaryText = @json(session('summary') ?? '');
                if (summaryText) {
                    // Xóa định dạng in đậm Markdown (** văn bản **) Khi sao chép
                    const plainText = summaryText.replace(/\*\*(.*?)\*\*/g, '$1');
                    // Cũng xóa định dạng in nghiêng (*văn bản*)
                    const plainTextClean = plainText.replace(/\*(.*?)\*/g, '$1');
                    navigator.clipboard.writeText(plainTextClean).then(function() {
                        const originalText = copyButton.textContent;
                        copyButton.textContent = 'Đã sao chép!';
                        setTimeout(function() {
                            copyButton.textContent = originalText;
                        }, 2000);
                    });
                } else {
                    alert('Không có văn bản nào để sao chép!');
                }
                });
            }
        });
    </script>


    </body>

</html>


