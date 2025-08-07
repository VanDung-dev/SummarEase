<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable// implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
	    'google_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
    
    /**
     * Check if user has admin role
     */
    public function isAdmin(): bool
    {
        // Check if the user has admin role (role_id = 1)
        return \DB::table('user_roles')
            ->where('user_id', $this->id)
            ->where('role_id', 1)
            ->exists();
    }
    
    /**
     * Check if user has regular user role
     */
    public function isRegularUser(): bool
    {
        // Check if the user has regular user role (role_id = 2)
        return \DB::table('user_roles')
            ->where('user_id', $this->id)
            ->where('role_id', 2)
            ->exists();
    }
    
    /**
     * Get user role silently (without displaying on web interface)
     */
    public function getUserRoleSilently(): string
    {
        if ($this->isAdmin()) {
            return 'admin';
        } elseif ($this->isRegularUser()) {
            return 'user';
        }
        return 'guest';
    }
    
    /**
     * Automatically assign 'user' role to new users
     */
    public function assignDefaultRole(): void
    {
        // Kiểm tra xem người dùng đã có vai trò chưa
        $existingRole = DB::table('user_roles')
            ->where('user_id', $this->id)
            ->first();
            
        // Nếu chưa có vai trò nào, gán vai trò 'user' (role_id = 2)
        if (!$existingRole) {
            DB::table('user_roles')->insert([
                'user_id' => $this->id,
                'role_id' => 2, // Vai trò 'user'
            ]);
        }
    }
    
    /**
     * Override the boot method to automatically assign role when creating a user
     */
    protected static function booted(): void
    {
        static::created(function ($user) {
            $user->assignDefaultRole();
        });
    }
}