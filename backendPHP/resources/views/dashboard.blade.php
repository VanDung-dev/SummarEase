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

        <form method="POST">
                    @csrf
                    <textarea name="text" id="text" class="input-area">{{ session('original_text') }}</textarea>
                    <input type="range" name="ratio" id="ratio" min="0" max="1" step="0.1" value="0.5" class="mt-4 w-full" />
                    <p class="text-sm text-gray-500 mt-1"></p>
                    <button type="submit" class="mt-4 w-full rounded-xl bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">{{ __('Summarize with SummarEase') }}</button>
        </form>

        <form method="GET" action="/gemini" class="mt-2">
                    <textarea name="text" class="input-area" style="display:none;">{{ session('original_text') }}</textarea>
                    <input type="range" name="ratio" min="0" max="1" step="0.1" value="0.5" class="mt-4 w-full"/>
                    <button type="submit" class="mt-4 w-full rounded-xl bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Summarize with Gemini
                    </button>
        </form>


                <div class="output-area">
                     @if(session('summary'))
                    <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg">
                        <div id="summary-output">{!! nl2br(e(session('summary'))) !!}</div>
                    </div>
          
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Xử lý hiển thị markdown cho kết quả tóm tắt
                            const summaryText = @json(session('summary'));
                            const outputArea = document.getElementById('summary-output');
                            // Chuyển đổi markdown thành HTML
                            const html = summaryText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                            outputArea.innerHTML = html;
                        });
                    </script>
                     @endif
                </div>


    <script src="https://kit.fontawesome.com/af877c9b83.js" crossorigin="anonymous"></script>
        

    <script src="script.js"></script>

    <!-- Thêm script để xử lý markdown -->
    <script src="{{ asset('js/script.js') }}"></script>
</x-layouts.app>
