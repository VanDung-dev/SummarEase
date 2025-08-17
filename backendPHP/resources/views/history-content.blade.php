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
        <link href="style.css" rel="stylesheet" />

        @include('partials.head')

    </head>
    <body>
        @if(isset($history) && $history)
            <h1>{{ $history->title }}</h1>
            <p>{{ $history->file_name ?? ''}}</p>
            <p>Tỉ lệ: {{ $history->summary_ratio }}</p>
            <p>Ngày tạo: {{ $history->created_at }}</p>

            @if(isset($history->doctext))
            <h2>Nội dung ban đầu:</h2>
            <div>
                {{ $history->doctext }}
            </div>
            @endif
            
            <h2>Nội dung đã được tóm tắt:</h2>
            <div>
                {{ $history->summary_text }}
            </div>        
        @else
            <p>Không tìm thấy bản tóm tắt.</p>
        @endif
    </body>
    
</html>