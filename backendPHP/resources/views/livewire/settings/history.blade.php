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
    
    <!-- Liên kết đến file CSS chính -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @include('partials.head')

</head>
<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('History')" :subheading="__('Lịch sử tóm tắt')">
        <form wire:submit="updatePassword" class="mt-6 space-y-6">
            <div id="history" class="section">
                <div class="search-bar">
                    <input type="text" id="historySearchInput" placeholder="Tìm kiếm lịch sử..."/>
                    <button onclick="searchHistory()">Tìm</button>
                </div>
                <div class="table-container">
                    <table class="file-table" id="historyTable">
                        <thead>
                            <tr>
                                <th>Tên tệp</th>
                                <th>Ngày tóm tắt</th>
                                <th>Kết quả</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody"></tbody>
                    </table>
                    <button onclick="addHistory()">Thêm lịch sử mẫu</button>
                </div>
            </div>
        </form>
    </x-settings.layout>
</section>