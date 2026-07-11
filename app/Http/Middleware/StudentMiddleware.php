<?php

namespace App\Http\Middleware;

use App\Support\LoginGuard;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isStudent()) {
            abort(403, 'Access denied. Students only.');
        }

        if ($error = LoginGuard::check(auth()->user())) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('user.login')->withErrors(['login' => $error]);
        }

        return $next($request);
    }
}
