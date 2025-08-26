<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Nội dung lịch sử</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="stylesheet" href="app-gimPeXcx.css">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @include('partials.head')

        <style>
            .close-button {
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: #f0f0f0;
                border: 1px solid #ccc;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                font-size: 20px;
                cursor: pointer;
                z-index: 1000;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .dark .close-button {
                background-color: #333;
                border-color: #555;
            }

            .close-button:hover {
                background-color: #e0e0e0;
            }

            .dark .close-button:hover {
                background-color: #555;
            }
            
            .delete-button {
                position: fixed;
                top: 20px;
                left: 20px;
                background-color: #ef4444;
                border: 1px solid #dc2626;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                font-size: 20px;
                cursor: pointer;
                z-index: 1000;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .dark .delete-button {
                background-color: #dc2626;
                border-color: #b91c1c;
            }
            
            .delete-button:hover {
                background-color: #dc2626;
            }
            
            .dark .delete-button:hover {
                background-color: #b91c1c;
            }

            .h-content {
                margin-bottom: 15px;
            }

            .submit-button {
                background-color: #3b82f6;
                color: white;
                padding: 0 1rem;
                border: none;
                cursor: pointer;
                font-weight: 500;
                transition: background-color 0.2s;
            }

            .submit-button:hover {
                background-color: #2563eb;
            }
        </style>

    </head>
    <body>
        <div class="close-button" onclick="window.history.back()" title="Đóng trang">✕</div>
        
        @if(isset($history) && $history)
            <form action="{{ route('history.delete', $history->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch sử này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-button" title="Xóa lịch sử">🗑️</button>
            </form>
        @endif
        
        <div class="body-history">
        @if(isset($history) && $history)
            <h1 class="h-content">{{ $history->title }}</h1>
            <p class="h-content">{{ $history->file_name ?? ''}}</p>
            <p class="h-content">Tỉ lệ: {{ $history->summary_ratio*100 . '%' }}</p>
            <p class="h-content">Ngày tạo: {{ $history->created_at }}</p>

            @if(isset($history->doctext))
            <h2 class="h-content">Nội dung ban đầu:</h2>
            <div class="output-areaa">
                {{ $history->doctext }}
            </div>
            @endif

            <h2 class="h-content">Nội dung đã được tóm tắt:</h2>
            <div class="output-areaa">
                <?php
                    $markdown = $history->summary_text;
                    // Xử lý in đậm
                    $markdown = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $markdown);
                    // Xử lý in nghiêng
                    $markdown = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $markdown);
                    // Xử lý đoạn văn
                    $markdown = preg_replace('/(\r\n|\r|\n){2,}/', '</p><p>', $markdown);
                    // Xử lý ngắt dòng
                    $markdown = preg_replace('/(\r\n|\r|\n)/', '<br>', $markdown);
                    // Bọc nội dung trong thẻ p
                    $markdown = '<p>' . $markdown . '</p>';
                ?>
                {!! $markdown !!}
            </div>
            <button id="copy-button" class="mt-4 submit-button" style="display: block; width: 100%; text-align: center;">Sao chép</button>
        @else
            <p class="h-content">Không tìm thấy bản tóm tắt.</p>
        @endif
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Thêm event listener cho nút sao chép
                const copyButton = document.getElementById('copy-button');
                if (copyButton) {
                    copyButton.addEventListener('click', function() {
                        const summaryText = @json($history->summary_text ?? '');
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