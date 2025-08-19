<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Danh sách lịch sử</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="style.css" rel="stylesheet" />

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
        </style>
    </head>
    <body>
        <div class="close-button" onclick="window.history.back()" title="Đóng trang">✕</div>
        
        @forelse($history as $item)
        <a href="{{ route('history-content', $item->summaryid) }}">
        <div style="border: 1px solid #696c71; border-radius: 5px; margin-bottom: 10px; padding: 5px;">
            <h4 style="font-weight: bold; font-style: italic; text-decoration: underline; text-align: center;">{{ $item->file_name }}</h4>
            <p style="text-align: justify;">{{ $item->title }}</p>
            <p style="text-align: right; font-size: 0.8rem; color: #696c71;">Tỉ lệ: {{ $item->summary_ratio }}</p>
            <p style="text-align: right; font-size: 0.8rem; color: #696c71;">Ngày tạo: {{ $item->created_at }}</p>
        </div>
        </a>
        @empty
            <p>Lịch sử trống</p>
        @endforelse
        <p>Tổng số lần tóm tắt: {{ $history->total() }}</p>
        <p>{{ $history->links('pagination::bootstrap-5') }}</p>
        
        <script>
            // Hỗ trợ phím Esc để đóng trang
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    window.history.back();
                }
            });
        </script>
    </body>
    
</html>