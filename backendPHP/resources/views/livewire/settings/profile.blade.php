
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
<body>
    

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Users')" :subheading="__('Danh sách người dùng')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div id="users" class="section">
                <div class="search-bar">
                    <input type="text" id="userSearchInput" placeholder="Tìm kiếm người dùng..."/>
                    <button onclick="searchUsers()">Tìm</button>
                </div>
                <div class="table-container">
                    <table class="file-table" id="userTable">
                        <thead>
                            <tr>
                                <th>Tên người dùng</th>
                                <th>Quyền</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody"></tbody>
                    </table>
                    <div class="permissions">
                        <select id="roleSelect">
                            <option value="admin">Admin</option>
                            <option value="user">Người dùng</option>
                        </select>
                        <input type="text" id="newUserInput" placeholder="Tên người dùng mới"/>
                        <button onclick="addUser()">Thêm người dùng</button>
                    </div>
                </div>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>