 <link href="style.css" rel="stylesheet" />

<x-layouts.app :title="__('Tóm tắt văn bản')">
    <div class="full container">
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

        <form method="POST">
                    @csrf
                    <textarea name="text" id="text" class="input-areaa" placeholder="Hãy nhập văn bản cần tóm tắt...">{{ session('original_text') }}</textarea>
                    <input type="range" name="ratio" id="ratio" min="0" max="1" step="0.1" value="{{ session('original_ratio')}}" class="mt-4 w-full" />
                    <p class="text-sm text-gray-500 mt-1"></p>
                    <button type="submit" name="sum" value="summarease" class="mt-4 submit-button">{{ __('Summarize with SummarEase') }}</button>
                    <br />
                    <button type="submit" name="sum" value="gemini" class="mt-4 submit-button">{{ __('Summarize with Gemini') }}</button>
        </form>
                    <!--<textarea name="text" id="text" class="input-area">{{ session('original_text') }}</textarea>
                    <input type="range" name="ratio" id="ratio" min="0" max="1" step="0.1" value='{{ session('original_ratio') }}' class="mt-4 w-full" />
                    <p class="text-sm text-gray-500 mt-1"></p>
                    <button type="submit" class="mt-4 w-full rounded-xl bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">{{ __('Summarize with SummarEase') }}</button>
        </form>-->

        <!-- <form method="GET" action="/gemini" class="mt-2">
                    <textarea name="textgmn" class="input-areaa" placeholder="Hãy nhập văn bản cần tóm tắt...">{{ session('original_text_gmn') }}</textarea>
                    <input type="range" name="ratiogmn" min="0" max="1" step="0.1" value='{{ session('original_ratio_gmn') }}' class="mt-4 w-full"/>
                    <button type="submit" class="mt-4 submit-button">
                    Summarize with Gemini
                    </button>
        </form> -->

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
