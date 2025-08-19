<link href="style.css" rel="stylesheet" />

<x-layouts.app :title="__('Tóm tắt văn bản')">
    <div class="full container">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

        </div>

        <h1 class="title">Tóm tắt văn bản học thuật</h1>

        <p class="sub">
        Công cụ hỗ trợ tóm tắt nhanh và chính xác các văn bản học thuật
        </p>

        <form method="POST">
                    @csrf
                    <textarea style="text-align: justify;" name="text" id="text" class="input-areaa" placeholder="Hãy nhập văn bản cần tóm tắt...">{{ session('original_text') }}</textarea>
                    <input type="range" name="ratio" id="ratio" min="0" max="1" step="0.1" value="{{ session('original_ratio')}}" class="mt-4 w-full" />
                    <p class="text-sm text-gray-500 mt-1"></p>
                    <button type="submit" name="sum" value="summarease" class="mt-4 submit-button">{{ __('Tóm tắt với SummarEase') }}</button>
                    <br />
                    <button type="submit" name="sum" value="gemini" class="mt-4 submit-button">{{ __('Tóm tắt với Gemini') }}</button>
        </form>

                <div class="output-areaa">
                     @if(session('summary'))
                        <div id="summary-output" style="text-align: justify;">{!! nl2br(e(session('summary'))) !!}</div>
                     @endif
                </div>
                @if(session('error'))
                <p style="margin-top: 0; color: red;">{{ session('error') }}</p>
                @endif

    <script src="https://kit.fontawesome.com/af877c9b83.js" crossorigin="anonymous"></script>

    <script>
        // Force Page Refresh khi điều hướng giữa các phương pháp tóm tắt
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.nal-list');
            navItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && !window.location.href.includes(href.split('/').pop())) {
                        e.preventDefault();
                        window.location.href = href;
                    }
                });
            });
        });
    </script>

    <script src="script.js"></script>

    <!-- Thêm script để xử lý markdown -->
    <script src="{{ asset('js/script.js') }}"></script>
</x-layouts.app>
