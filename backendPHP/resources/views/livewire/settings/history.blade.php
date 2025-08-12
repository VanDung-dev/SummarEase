<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

// new class extends Component {
//     public string $current_password = '';
//     public string $password = '';
//     public string $password_confirmation = '';

//     /**
//      * Update the password for the currently authenticated user.
//      */
//     public function updatePassword(): void
//     {
//         try {
//             $validated = $this->validate([
//                 'current_password' => ['required', 'string', 'current_password'],
//                 'password' => ['required', 'string', Password::defaults(), 'confirmed'],
//             ]);
//         } catch (ValidationException $e) {
//             $this->reset('current_password', 'password', 'password_confirmation');

//             throw $e;
//         }

//         Auth::user()->update([
//             'password' => Hash::make($validated['password']),
//         ]);

//         $this->reset('current_password', 'password', 'password_confirmation');

//         $this->dispatch('password-updated');
//     }
// }; ?>

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