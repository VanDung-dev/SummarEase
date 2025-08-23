<link href="style.css" rel="stylesheet" />

<x-layouts.app :title="__('Tóm tắt văn bản')">
    <div class="full container">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

        </div>

        <h1 style="margin-top: -1rem" class="title">Tóm tắt văn bản học thuật</h1>

        <p class="sub">
        Công cụ hỗ trợ tóm tắt nhanh và chính xác các văn bản học thuật
        </p>

        <form method="POST">
                    @csrf
                    <textarea style="text-align: justify;" name="text" id="text" class="input-areaa" placeholder="Hãy nhập văn bản cần tóm tắt...">{{ session('original_text') }}</textarea>
                    <input type="range" name="ratio" id="ratio" min="0" max="1" step="0.1" value="{{ session('original_ratio')}}" class="mt-4 w-full" />
                    <p class="text-sm text-gray-500 mt-1"></p>
                    <div class="flex" style="gap: 1rem;">
                        <button type="submit" name="sum" value="summarease" class="mt-4 submit-button">{{ __('Tóm tắt với SummarEase') }}</button>
                        <button type="submit" name="sum" value="gemini" class="mt-4 submit-button">{{ __('Tóm tắt với Gemini') }}</button>
                    </div>
        </form>

                <div class="output-areaa">
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
                <button id="copy-button" class="mt-4 submit-button" style="display: block; width: 100%; text-align: center; display: flex; align-items: center; justify-content: center;">Sao chép</button>
                @if(session('error'))
                <p style="margin-top: 0; color: red;">{{ session('error') }}</p>
                @endif

    <script src="https://kit.fontawesome.com/af877c9b83.js" crossorigin="anonymous"></script>

    <script>
        // Force Page Refresh khi điều hướng giữa các phương thức tóm tắt
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
</div>
<!-- Thêm id cho form để có thể submit từ bên ngoài -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.id = 'summary-form';

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
</x-layouts.app>
