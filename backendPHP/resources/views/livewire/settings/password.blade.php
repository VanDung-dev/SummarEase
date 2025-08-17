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

    <x-settings.layout :heading="__('File')" :subheading="__('Các file đã tải lên')">
        <form wire:submit="updatePassword" class="mt-6 space-y-6">
            <div id="files" class="section">
                <div class="search-bar">
                    <input type="text" id="fileSearchInput" placeholder="Tìm kiếm tệp..."/>
                    <button onclick="searchFiles()">Tìm</button>
                </div>
                <div class="table-container">
                    <table class="file-table" id="fileTable">
                        <thead>
                            <tr>
                                <th>Tên tệp</th>
                                <th>Ngày tải lên</th>   
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="fileTableBody">
                            <tr>
                                <td colspan="4" style="text-align: center;">Chưa có tệp nào.</td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" onclick="addFile()">Thêm tệp mới</button>
                </div>
            </div>
        </form>
    </x-settings.layout>
</section>
<script src="script.js"></script>