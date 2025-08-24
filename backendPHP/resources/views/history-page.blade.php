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
            
            .delete-all-button {
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
            
            .dark .delete-all-button {
                background-color: #dc2626;
                border-color: #b91c1c;
            }
            
            .delete-all-button:hover {
                background-color: #dc2626;
            }
            
            .dark .delete-all-button:hover {
                background-color: #b91c1c;
            }
            
            .delete-button {
                background-color: #ef4444;
                color: white;
                border: none;
                padding: 5px 10px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                float: right;
                margin-top: 5px;
            }
            
            .delete-button:hover {
                background-color: #dc2626;
            }
        </style>
    </head>
    <body>
        <div class="close-button" onclick="window.history.back()" title="Đóng trang">✕</div>
        
        @if($history->count() > 0)
            <form action="{{ route('history.delete-all') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tất cả lịch sử?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-all-button" title="Xóa tất cả lịch sử">🗑️</button>
            </form>
        @endif
        
        @forelse($history as $item)
        <div style="border: 1px solid #696c71; border-radius: 5px; margin-bottom: 10px; padding: 5px;">
            <a href="{{ route('history-content', $item->summaryid) }}">
                <h4 style="font-weight: bold; font-style: italic; text-decoration: underline; text-align: center;">{{ $item->file_name }}</h4>
                <p style="text-align: justify;">{{ $item->title }}</p>
                <p style="text-align: right; font-size: 0.8rem; color: #696c71;">Tỉ lệ: {{ $item->summary_ratio }}</p>
                <p style="text-align: right; font-size: 0.8rem; color: #696c71;">Ngày tạo: {{ $item->created_at }}</p>
            </a>
            
            <form action="{{ route('history.delete', $item->summaryid) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch sử này?');" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-button">Xóa</button>
            </form>
        </div>
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