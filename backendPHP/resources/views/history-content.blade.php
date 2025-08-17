<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SummarEase</title>

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
            <p>File: {{ $history->file_name }}</p>
            <p>Ratio: {{ $history->summary_ratio }}</p>
            <p>Created at: {{ $history->created_at }}</p>
            
            <h2>Summary Text:</h2>
            <div>
                {{ $history->summary_text }}
            </div>
            
            @if(isset($history->doctext))
            <h2>Original Document:</h2>
            <div>
                {{ $history->doctext }}
            </div>
            @endif
        @else
            <p>Summary not found.</p>
        @endif
    </body>
    
</html>