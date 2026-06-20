<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();
        $default = $user->isAdmin() ? route('admin.dashboard') : route('student.dashboard');
        $this->redirectIntended(default: $default);
    }
}; ?>

<div>
    <!-- Session Status -->
    @if(session('status'))
        <div class="mb-6 px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm text-gray-600 font-medium">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">
        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email address</label>
            <input wire:model="form.email" id="email" type="email" name="email"
                placeholder="you@example.com"
                required autofocus autocomplete="username"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm placeholder-gray-300 focus:outline-none focus:border-black focus:ring-2 focus:ring-black/10 transition-all">
            @error('form.email')
                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-xs text-gray-400 hover:text-black transition-colors font-medium">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input wire:model="form.password" id="password" type="password" name="password"
                placeholder="••••••••"
                required autocomplete="current-password"
                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 text-sm placeholder-gray-300 focus:outline-none focus:border-black focus:ring-2 focus:ring-black/10 transition-all">
            @error('form.password')
                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-2.5">
            <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                class="w-4 h-4 rounded border-gray-300 text-black focus:ring-black cursor-pointer">
            <label for="remember" class="text-sm text-gray-500 cursor-pointer select-none">Remember me</label>
        </div>

        <!-- Submit -->
        <button type="submit"
            class="w-full py-3.5 bg-black hover:bg-gray-900 text-white text-sm font-bold rounded-xl transition-all active:scale-[0.98] flex items-center justify-center gap-2 mt-2">
            <span wire:loading.remove wire:target="login">Sign in</span>
            <span wire:loading wire:target="login" class="flex items-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Signing in...
            </span>
        </button>
    </form>
</div>