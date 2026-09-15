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

        if (!in_array($admin->role, ['manager', 'admin'], true)) {
            $this->logoutAndInvalidate($request);

            return back()
                ->withErrors([
                    'email' => 'Akun ini bukan akun Manager atau Admin.'
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

        $redirect = $admin->role === 'manager'
            ? route('manager.dashboard')
            : route('admin.dashboard');

        return redirect()->intended($redirect);
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
