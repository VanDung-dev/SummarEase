<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>



<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Sáng') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Tối') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('Hệ thống') }}</flux:radio>
        </flux:radio.group>

        <flux:menu.separator />
          
        <div class="relative mb-5">
        <flux:heading>{{ __('Xóa tài khoản') }}</flux:heading>
        <flux:subheading>{{ __('Xóa tài khoản của bạn và tất cả tài nguyên') }}</flux:subheading>
        </div>

        <flux:modal.trigger name="confirm-user-deletion">
            <flux:button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                {{ __('Xóa tài khoản') }}
            </flux:button>
        </flux:modal.trigger>

        
    </x-settings.layout>


    
</section>
