<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class AssignUserRole
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        // Kiểm tra xem event có chứa user không
        if (isset($event->user) && $event->user instanceof \App\Models\User) {
            // Kiểm tra xem người dùng đã có vai trò chưa
            $existingRole = DB::table('user_roles')
                ->where('user_id', $event->user->id)
                ->first();
                
            // Nếu chưa có vai trò nào, gán vai trò 'user' (role_id = 2)
            if (!$existingRole) {
                DB::table('user_roles')->insert([
                    'user_id' => $event->user->id,
                    'role_id' => 2, // Vai trò 'user'
                ]);
            }
        }
    }
}