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
                    <input type="range" name="ratio" id="ratio" min="0" max="1" step="0.1" value="{{ session('original_ratio')}}" class="mt-4 w-full" />
                    <p class="text-sm text-gray-500 mt-1"></p>
                    <button type="submit" name="sum-file" value="summarease" class="mt-4 submit-button">{{ __('Tóm tắt với SummarEase') }}</button>
                    <br />
                    <button type="submit" name="sum-file" value="gemini" class="mt-4 submit-button">{{ __('Tóm tắt với Gemini') }}</button>
        </form>

                <div class="output-areaa">
                     @if(session('summary'))
                        <div id="summary-output">{!! nl2br(e(session('summary'))) !!}</div>
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
                     @endif
                </div>


    <script src="https://kit.fontawesome.com/af877c9b83.js" crossorigin="anonymous"></script>
        

    <script src="script.js"></script>

    <!-- Thêm script để xử lý markdown -->
    <script src="{{ asset('js/script.js') }}"></script>
</x-layouts.app>
