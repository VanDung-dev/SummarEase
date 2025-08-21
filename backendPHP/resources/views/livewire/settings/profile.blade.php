<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Liên kết đến file CSS chính -->
    <link href="style.css" rel="stylesheet" />

    @include('partials.head')

</head>
<body>
    

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Người dùng')" :subheading="__('Danh sách người dùng')">
        <!-- <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6"> -->
            <div id="users" class="section">
                <div class="search-bar">
                    <form method="get">
                        <input type="text" id="userSearchInput" name="search" placeholder="Tìm kiếm người dùng..." />
                        <button type="submit">Tìm</button>
                    </form>
                </div>
                <div class="table-container" style="max-height: 400px; overflow-y: auto;">
                    <table class="file-table" id="userTable">
                        @php
                            $query = request('search');
                            $usrs = DB::table('users')
                            ->select('users.id as uid', 'users.name as username', 'roles.name as rolename')
                            ->orderBy('users.created_at', 'desc')
                            ->join('user_roles', 'users.id', '=', 'user_id')
                            ->join('roles', 'roles.id', '=', 'role_id')
                            ->where('users.name', 'like', '%' . $query . '%')
                            ->when($query, function ($q) use ($query) {
                                return $q->where('users.name', 'like', '%' . $query . '%');
                            })
                            ->paginate();
                        @endphp
                        <thead>
                            <tr>
                                <th>Tên người dùng</th>
                                <th>Quyền</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody" style="max-height: 300px; overflow-y: auto;">
                            @forelse($usrs as $item)
                                <tr>
                                    <td>{{ $item->username }}</td>
                                    <td>{{ $item->rolename }}</td>
                                    <td>
                                        @if ($item->uid != Auth::id() and $item->rolename != 'admin')
                                            <form action="{{ route('delete-user', $item->uid) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="color: red;">Xóa</button>
                                            </form>
                                        @else
                                        <span>Không thể thao tác với quản trị viên hay bản thân</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">Không có người dùng</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <p>Tổng số người dùng: {{ $usrs->total() }}</p>
                    <br />
                    <p>{{ $usrs->links('pagination::bootstrap-5') }}</p>
                </div>
                @if (session('message'))
                    <p>{{ session('message') }}</p>
                @endif
            </div>
        <!-- </form> -->
    </x-settings.layout>
</section>
<script src="script.js"></script>
</body>
</html>
