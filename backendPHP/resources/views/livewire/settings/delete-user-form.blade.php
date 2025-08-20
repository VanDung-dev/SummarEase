<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        // $this->validate([
        //     'password' => ['required', 'string', 'current_password'],
        // ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="mt-10 space-y-6">

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Bạn có chắc là muốn xóa tài khoản này không?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Một khi tài khoản đã bị xóa, tất cả dữ liệu tóm tắt kết nối với tài khoản này sẽ bị mất.') }}
                </flux:subheading>
            </div>

            <!-- <flux:input wire:model="password" :label="__('Password')" type="password" /> -->

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Hủy') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('Xóa tài khoản') }}</flux:button>
            </div>
        </form>
    </flux:modal>

</section>
