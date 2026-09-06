<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();

            if (($admin->role ?? null) === 'admin') {
                return $next($request);
            }

            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Akun ini bukan akun administrator.']);
        }

        if (Auth::guard('web')->check()) {
            return redirect('/')->withErrors([
                'email' => 'Anda sudah login sebagai pelanggan. Silakan gunakan akun admin untuk mengakses halaman ini.',
            ]);
        }

        return redirect()->route('admin.login');
    }
}
