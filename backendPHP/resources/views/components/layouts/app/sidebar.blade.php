<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <style>
            .history-item {
                position: relative;
                border: 1px solid #696c71;
                border-radius: 5px;
                margin-bottom: 10px;
                padding: 5px;
                overflow: hidden;
            }

            .history-menu {
                position: absolute;
                top: 5px;
                right: 5px;
                cursor: pointer;
                padding: 5px;
                border-radius: 4px;
                background: rgba(255, 255, 255, 0.9);
                border: none;
                color: #696c71;
                font-size: 18px;
                line-height: 1;
                z-index: 10;
                backdrop-filter: blur(2px);
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }

            .dark .history-menu {
                background: rgba(63, 63, 70, 0.9);
                color: #d4d4d8;
            }

            .history-menu:hover {
                background-color: #e5e7eb;
            }

            .dark .history-menu:hover {
                background-color: #52525b;
            }

            .dropdown-menu {
                position: absolute;
                right: 0;
                top: 30px;
                background: white;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                min-width: 120px;
                display: none;
            }

            .dark .dropdown-menu {
                background: #3f3f46;
                border-color: #52525b;
            }

            .dropdown-menu.show {
                display: block;
            }

            .dropdown-item {
                display: block;
                width: 100%;
                padding: 0.5rem 1rem;
                text-align: left;
                background: none;
                border: none;
                cursor: pointer;
                font-size: 0.875rem;
                color: #374151;
            }

            .dark .dropdown-item {
                color: #d4d4d8;
            }

            .dropdown-item:hover {
                background-color: #f3f4f6;
            }

            .dark .dropdown-item:hover {
                background-color: #52525b;
            }

            .history-content {
                padding-right: 30px; /* Tạo khoảng trống bên phải để tránh nội dung bị chồng lên nút */
            }
        </style>

        <script>
            function toggleDropdown(id) {
                event.stopPropagation();
                const dropdown = document.getElementById('dropdown-' + id);
                const isOpen = dropdown.classList.contains('show');

                // Close all dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });

                // Open the clicked dropdown if it wasn't open
                if (!isOpen) {
                    dropdown.classList.add('show');
                }
            }

            // Close dropdowns when clicking elsewhere
            document.addEventListener('click', function() {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            });
        </script>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="siba">

            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

<flux:navlist.item class="nal-list" :href="route('dashboard')" :current="request()->routeIs('dashboard')">
    <i class="fa-solid fa-file-lines"></i>
    <span class="nav-text">{{ __('Tóm tắt văn bản') }}</span>
</flux:navlist.item>

<flux:navlist.item class="nal-list" :href="route('dashboard-file')" :current="request()->routeIs('dashboard-file')">
    <i class="fa-solid fa-file-arrow-up"></i>
    <span class="nav-text">{{ __('Tóm tắt file') }}</span>
</flux:navlist.item>

<flux:navlist.item class="nal-list" :href="route('dashboard-url')" :current="request()->routeIs('dashboard-url')">
    <i class="fa-solid fa-link"></i>
    <span class="nav-text">{{ __('Tóm tắt URL') }}</span>
</flux:navlist.item>

            <div class="overflow-y-auto max-h-64">
            @php
            $usr = auth()->user();
            $isAdmin = $usr && $usr->isAdmin();
            $hquery = request('hsearch');
            session(['original_hquery' => $hquery]);
            @endphp

            <form method="get">
                <input type="text" id="HistorySearchInput" name="hsearch" placeholder="Tìm kiếm lịch sử..." style="height: 30px; width: 100%; padding: 3px; border-radius: 5px; border: 1px solid #ccc; overflow-x: auto;" value="{{ session('horiginal_query') ?? '' }}" />
                <button type="submit" style="height: 30px; padding: 3px 10px; border-radius: 5px; border: 1px solid #ccc; background-color: #4a6cff; width: 100%">Tìm</button>
            </form>
            <br />
            @if($isAdmin)
                @php
                $history = DB::table('summaries')
                    ->select('users.name as username', 'summaries.id as summaryid', 'summary_ratio', 'title', 'file_name', 'summaries.created_at')
                    ->orderBy('summaries.created_at', 'desc')
                    ->join('documents', 'documents.id', '=', 'document_id')
                    ->join('users', 'users.id', '=', 'documents.user_id')
                    ->when($hquery, function ($q) use ($hquery) {
                                    return $q->where('users.name', 'like', '%' . $hquery . '%')->orWhere('title', 'like', '%' . $hquery . '%')->orWhere('file_name', 'like', '%' . $hquery . '%');
                                })
                    ->paginate();
                @endphp
            @elseif(auth()->check())
                @php
                    $userId = Auth::id();
                    $history = DB::table('summaries')
                    ->select('summaries.id as summaryid', 'summary_ratio', 'title', 'file_name', 'summaries.created_at')
                    ->orderBy('summaries.created_at', 'desc')
                    ->join('documents', 'documents.id', '=', 'document_id')
                    ->join('users', 'users.id', '=', 'documents.user_id')
                    ->where('users.id', '=', $userId)
                    ->when($hquery, function ($q) use ($hquery, $userId) {
                        return $q->where('title', 'like', '%' . $hquery . '%')
                                 ->orWhere('file_name', 'like', '%' . $hquery . '%')
                                 ->where('users.id', '=', $userId);
                    })
                    ->paginate();
                @endphp
            @endif

            @forelse($history as $item)
                <div class="history-item-container">
                    <div class="history-item">
                        <button class="history-menu" onclick="toggleDropdown({{ $item->summaryid }})" title="Tùy chọn">⋯</button>

                        <div class="dropdown-menu" id="dropdown-{{ $item->summaryid }}">
                            <form action="{{ route('history.delete', $item->summaryid) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch sử này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item">Xóa</button>
                            </form>
                        </div>

                        <div class="history-content">
                            <a href="{{ route('history-content', $item->summaryid) }}" style="display: block; text-decoration: none; color: inherit;">
                                <h4 style="font-weight: bold; font-style: italic; text-decoration: underline; text-align: center;">{{ $item->file_name }}</h4>
                                <p style="text-align: justify; font-size: 0.85rem;">{{ $item->title }}</p>
                                @if (isset($item->username))
                                <p style="text-align: left; font-size: 0.8rem; color: #696c71;">Người dùng: {{ $item->username }}</p>
                                @endif
                                <p style="text-align: left; font-size: 0.7rem; color: #696c71;">Tỉ lệ: {{ $item->summary_ratio*100 . '%' }}</p>
                                <p style="text-align: left; font-size: 0.7rem; color: #696c71;">Ngày tạo: {{ $item->created_at }}</p>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center;">Lịch sử trống</p>
            @endforelse
                <p>Tổng số lần tóm tắt: {{ $history->total() }}</p>
                <br />
                <p>{{ $history->links('pagination::bootstrap-5') }}</p>
            </div>

            <flux:spacer />



            <!-- Desktop User Menu -->
            <flux:dropdown class="s" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Cài đặt') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Đăng xuất') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">


            <flux:dropdown position="top" align="end">

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Cài đặt') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Đăng xuất') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
