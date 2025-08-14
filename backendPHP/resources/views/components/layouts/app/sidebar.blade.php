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
        <flux:sidebar sticky stashable class="siba">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <flux:navlist.item class="nal-list" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Tóm tắt văn bản') }}</flux:navlist.item>
  
            <flux:navlist.item class="nal-list" :href="route('history')" :current="request()->routeIs('history')" wire:navigate>{{ __('Tóm tắt file') }}</flux:navlist.item>
            
            <flux:navlist.item class="nal-list" :href="route('history')" :current="request()->routeIs('history')" wire:navigate>{{ __('Tóm tắt URL') }}</flux:navlist.item>

            <div class="overflow-y-auto max-h-64">
            @if(auth()->check())
            @php
                $userId = Auth::id();
                $history = DB::table('summaries')
                ->select('summary_text', 'summary_ratio', 'title', 'file_name', 'content as doctext', 'summaries.created_at')
                ->orderBy('summaries.created_at', 'desc')
                ->join('documents', 'documents.id', '=', 'document_id')
                ->join('users', 'users.id', '=', 'documents.user_id')
                ->where('users.id', '=', $userId)
                ->paginate();
            @endphp

            @forelse($history as $item)
                <p>{{ $item->title }}</p>
                <p>{{ $item->summary_ratio }}</p>
            @empty
                <p>Lịch sử trống</p>
            @endforelse
                <p>Tổng số lần tóm tắt: {{ $history->total() }}</p>
                <p>{{ $history->links('pagination::bootstrap-5') }}</p>
            @endif
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
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group> 

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
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
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
