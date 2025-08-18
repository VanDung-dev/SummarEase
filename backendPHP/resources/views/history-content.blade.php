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

    </head>
    <body>
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
                {{ $history->summary_text }}
            </div>        
        @else
            <p class="h-content">Không tìm thấy bản tóm tắt.</p>
        @endif
        </div>
    </body>
    
</html>