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

    <x-settings.layout :heading="__('Tài liệu')" :subheading="__('Các tài liệu đã tải lên')">
        <!-- <form wire:submit="updatePassword" class="mt-6 space-y-6"> -->
            <div id="files" class="section">
                <div class="search-bar">
                    <form method="get">
                        <input type="text" id="fileSearchInput" name="search" placeholder="Tìm kiếm tài liệu..."/>
                        <button type="submit">Tìm</button>
                    </form>
                </div>
                <div class="table-container" style="max-height: 300px; overflow-y: auto;">
                    <table class="file-table" id="fileTable">
                        @php
                            $query = request('search');
                            $docs = DB::table('documents')
                            ->select('documents.id as docid', 'documents.file_type as doctype', 'documents.file_name as docname', 'documents.uploaded_at as uploadtime')
                            ->orderBy('documents.uploaded_at', 'desc')
                            ->where('file_type', '!=', 'url')
                            ->where('file_type', '!=', 'text')
                            ->when($query, function ($q) use ($query) {
                                return $q->where('documents.file_name', 'like', '%' . $query . '%');
                            })
                            ->paginate();
                        @endphp
                        <thead>
                            <tr>
                                <th>Tên tài liệu</th>
                                <th>Loại tài liệu</th>
                                <th>Ngày tải lên</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="fileTableBody">
                            @forelse($docs as $item)
                                <tr>
                                    <td>{{ $item->docname }}</td>
                                    <td>{{ $item->doctype }}</td>
                                    <td>{{ $item->uploadtime }}</td>
                                    <td>
                                        <form action="{{ route('delete-file', $item->docid) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài liệu này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="color: red;">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Không có tài liệu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <p>Tổng số tập tin: {{ $docs->total() }}</p>
                    <br />
                    <p>{{ $docs->links('pagination::bootstrap-5') }}</p>
                </div>
                @if (session('message'))
                    <p>{{ session('message') }}</p>
                @endif
            </div>
        <!-- </form> -->
    </x-settings.layout>
</section>
<script src="script.js"></script>