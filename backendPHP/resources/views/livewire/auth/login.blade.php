


<?php

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>


<link href="{{ asset('style.css') }}" rel="stylesheet">

    
    <div class="login-container">
            <div class="login-header">
                <i class="fa-solid fa-circle-user"></i>
                <h1>Đăng nhập</h1>
            </div>

	        <div class="form-group">
    	        <flux:link style="text-decoration:none" href="{{ route('google.redirect') }}" navigate="false">
                    <p class="login-description">
                        Truy cập vào công cụ tóm tắt văn bản học thuật
                    </p>
		            <button class="google-login-btn">
                        <img
                        src="https://www.google.com/favicon.ico"
                        alt="Google logo"
                        class="google-logo"
                        />
                    Đăng nhập với Google
                    </button>
	            </flux:link>
            </div>

             <p class="footer-text">
                <a href="#" id="registerLink">Điều khoản sử dụng</a><abbr> | </abbr>
                <a href="#" id="registerLink"> Chính sách riêng tư</a>
            </p>
    </div>
    
<script src="https://kit.fontawesome.com/af877c9b83.js" crossorigin="anonymous"></script>
