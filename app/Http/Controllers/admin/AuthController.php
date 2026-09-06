<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function FormLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::guard('admin')->attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.'
                ])
                ->withInput($request->only('email'));
        }

        $admin = Auth::guard('admin')->user();

        if ($admin->role !== 'admin') {
            $this->logoutAndInvalidate($request);

            return back()
                ->withErrors([
                    'email' => 'Akun ini bukan akun administrator.'
                ])
                ->withInput($request->only('email'));
        }

        if ($admin->status === 'nonaktif') {
            $this->logoutAndInvalidate($request);

            return back()
                ->withErrors([
                    'email' => 'Akun administrator ini sedang nonaktif. Silakan hubungi administrator.'
                ])
                ->withInput($request->only('email'));
        }

        // Prevent session fixation only after the account has passed all checks.
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        $this->logoutAndInvalidate($request);

        return redirect()->route('admin.login')
            ->with('status', 'Anda telah keluar dari akun administrator.');
    }

    private function logoutAndInvalidate(Request $request): void
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
