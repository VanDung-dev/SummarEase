<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Nội dung lịch sử</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

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
            }

            .dark .close-button {
                background-color: #333;
            }
            
            .close-button:hover {
                background-color: #e0e0e0;
            }

            .dark .close-button:hover {
                background-color: #555;
            }
            
            .h-content {
                margin-bottom: 15px;
            }
        </style>

    </head>
    <body>
        <div class="close-button" onclick="window.history.back()" title="Đóng trang">✕</div>
        <div class="body-history">
        @if(isset($history) && $history)
            <h1 class="h-content">{{ $history->title }}</h1>
            <p class="h-content">{{ $history->file_name ?? ''}}</p>
            <p class="h-content">Tỉ lệ: {{ $history->summary_ratio }}</p>
            <p class="h-content">Ngày tạo: {{ $history->created_at }}</p>

            @if(isset($history->doctext))
            <h2 class="h-content">Nội dung ban đầu:</h2>
            <div class="h-content">
                {{ $history->doctext }}
            </div>
            @endif
            
            <h2 class="h-content">Nội dung đã được tóm tắt:</h2>
            <div class="h-content">
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
        @else
            <p class="h-content">Không tìm thấy bản tóm tắt.</p>
        @endif
        </div>
    </body>
    
</html>