<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="siba" tabindex="0">
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
            $query = request('search');
            @endphp

            <form method="get">
                <input type="text" id="HistorySearchInput" name="search" placeholder="Tìm kiếm lịch sử..." style="height: 30px; width: 100%; padding: 3px; border-radius: 5px; border: 1px solid #ccc; overflow-x: auto;" />
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
                    ->when($query, function ($q) use ($query) {
                                    return $q->where('users.name', 'like', '%' . $query . '%')->orWhere('title', 'like', '%' . $query . '%')->orWhere('file_name', 'like', '%' . $query . '%');
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
                    ->when($query, function ($q) use ($query) {
                                    return $q->where('title', 'like', '%' . $query . '%')->orWhere('file_name', 'like', '%' . $query . '%');
                                })
                    ->paginate();
                @endphp
            @endif

            @forelse($history as $item)
                <a href="{{ route('history-content', $item->summaryid) }}">
                <div style="border: 1px solid #696c71; border-radius: 5px; margin-bottom: 10px; padding: 5px; overflow: hidden;">
                    <h4 style="font-weight: bold; font-style: italic; text-decoration: underline; text-align: center;">{{ $item->file_name }}</h4>
                    <p style="text-align: justify;">{{ $item->title }}</p>
                    @if (isset($item->username))
                    <p style="text-align: right; font-size: 0.8rem; color: #696c71;">Người dùng: {{ $item->username }}</p>
                    @endif
                    <p style="text-align: right; font-size: 0.8rem; color: #696c71;">Tỉ lệ: {{ $item->summary_ratio }}</p>
                    <p style="text-align: right; font-size: 0.8rem; color: #696c71;">Ngày tạo: {{ $item->created_at }}</p>
                </div>
                </a>
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