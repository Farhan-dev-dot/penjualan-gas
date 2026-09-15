<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = auth('admin')->user();

        if ($admin && in_array($admin->role ?? null, ['manager', 'admin'], true)) {
            return $next($request);
        }

        // Jangan redirect ke manager.dashboard (halaman itu memakai
        // middleware ini juga -> bisa membuat loop). Arahkan ke dashboard
        // sesuai role, atau ke halaman login jika belum terautentikasi.
        if (!$admin) {
            return redirect()->route('admin.login');
        }

        return redirect()
            ->route('admin.dashboard')
            ->withErrors(['email' => 'Anda tidak memiliki akses ke halaman Manager.']);
    }
}
