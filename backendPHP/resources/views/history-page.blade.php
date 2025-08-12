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
        @forelse($history as $item)
            <p>{{ $item->title }}</p>
            <p>{{ $item->summary_ratio }}</p>
        @empty
            <p>Lịch sử trống</p>
        @endforelse
    </body>
    
</html>