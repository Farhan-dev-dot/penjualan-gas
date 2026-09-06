<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    public function showProfile()
    {
        return view('customer.auth.form-profile', [
            'user' => Auth::guard('web')->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('web')->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string', 'max:255'],
        ];

        if (!$user->google_id) {
            $rules['email'] = [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ];
        }

        $user->update($request->validate($rules));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateKtp(Request $request)
    {
        if (!Schema::hasColumn('users', 'foto_ktp')) {
            return back()->with('error', 'Unggah foto KTP belum tersedia karena kolom foto_ktp tidak ada pada data user.');
        }

        $validated = $request->validate([
            'foto_ktp' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = Auth::guard('web')->user();
        $fotoKtpLama = $user->foto_ktp;
        $fotoKtpBaru = $validated['foto_ktp']->store('ktp', 'public');

        $user->forceFill(['foto_ktp' => $fotoKtpBaru])->save();

        if ($fotoKtpLama) {
            Storage::disk('public')->delete($fotoKtpLama);
        }

        return back()->with('success', 'Foto KTP berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
        ]);

        Auth::guard('web')->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }


    public function showRegisterForm()
    {
        return view('customer.auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'alamat'   => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telepon' => ['required', 'string'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar, silakan login.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'alamat'   => $validated['alamat'],
            'password' => Hash::make($validated['password']),
            'telepon' => $validated["telepon"],
            'role'     => 'user',
            "status"   => "aktif"
        ]);

        // Belum login dulu — user harus verifikasi OTP email dulu
        $this->sendRegisterVerificationCode($request, $user);

        return redirect()->route('register.otp.form');
    }


    // ================= LOGIN MANUAL =================

    public function showLoginForm()
    {
        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::guard('web')->attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::guard('web')->user();

        // Cek role
        if ($user->role !== 'user') {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Akun ini tidak dapat login melalui halaman ini.'])
                ->onlyInput('email');
        }

        // Cek status akun
        if ($user->status == 'nonaktif') {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Akun Anda sedang nonaktif. Silakan hubungi administrator.'])
                ->onlyInput('email');
        }

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }


    // =====================================================
    // OTP VERIFIKASI EMAIL SETELAH REGISTER MANUAL
    // (menggantikan halaman "Hubungkan Google" yang lama)
    // =====================================================

    private function sendRegisterVerificationCode(Request $request, User $user): void
    {
        $code = random_int(100000, 999999);

        $request->session()->put('register_pending', [
            'user_id'    => $user->id,
            'email'      => $user->email,
            'code'       => $code,
            'expires_at' => now()->addMinutes(5)->timestamp,
        ]);

        try {
            Mail::raw(
                "Kode verifikasi registrasi akun Anda: {$code}\nKode ini berlaku selama 5 menit. Jangan bagikan kode ini ke siapa pun.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Kode Verifikasi Registrasi Akun');
                }
            );
        } catch (\Exception $e) {
            Log::error('Gagal kirim OTP registrasi', [
                'email'   => $user->email,
                'message' => $e->getMessage(),
            ]);

            // Lempar lagi supaya user tahu ada masalah, bukan diam-diam redirect
            // ke halaman OTP padahal kodenya tidak pernah sampai.
            throw $e;
        }
    }

    public function showRegisterOtpForm(Request $request)
    {
        $pending = $request->session()->get('register_pending');


        if (!$pending) {
            return redirect()->route('register')->withErrors([
                'email' => 'Sesi verifikasi sudah kedaluwarsa, silakan daftar ulang.',
            ]);
        }

        return view('customer.auth.verify-register', [
            'email' => $pending['email'],
        ]);
    }

    public function verifyRegisterOtp(Request $request)
    {
        $pending = $request->session()->get('register_pending');

        if (!$pending) {
            return redirect()->route('register')->withErrors([
                'email' => 'Sesi verifikasi sudah kedaluwarsa, silakan daftar ulang.',
            ]);
        }

        $request->validate([
            'code' => ['required'],
        ]);

        if (now()->timestamp > $pending['expires_at']) {
            $request->session()->forget('register_pending');

            return redirect()->route('register')->withErrors([
                'email' => 'Kode verifikasi sudah kedaluwarsa, silakan daftar ulang.',
            ]);
        }

        if ((string) $request->code !== (string) $pending['code']) {
            return back()->withErrors([
                'code' => 'Kode verifikasi salah.',
            ]);
        }

        $user = User::find($pending['user_id']);

        if (!$user) {
            $request->session()->forget('register_pending');


            return redirect()->route('register')->withErrors([
                'email' => 'Akun tidak ditemukan, silakan daftar ulang.',
            ]);
        }

        $request->session()->forget('register_pending');

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        // Email terverifikasi -> baru login
        Auth::login($user, true);
        $request->session()->regenerate();

        // Langsung lanjut ke Google untuk ambil google_id
        return redirect()->route('auth.google');
    }

    public function resendRegisterOtp(Request $request)
    {
        $pending = $request->session()->get('register_pending');

        if (!$pending) {
            return redirect()->route('register')->withErrors([
                'email' => 'Sesi verifikasi sudah kedaluwarsa, silakan daftar ulang.',
            ]);
        }

        $user = User::find($pending['user_id']);

        if (!$user) {
            return redirect()->route('register')->withErrors([
                'email' => 'Akun tidak ditemukan, silakan daftar ulang.',
            ]);
        }

        $this->sendRegisterVerificationCode($request, $user);

        return back()->with('status', 'Kode baru sudah dikirim ke email Anda.');
    }


    // ================= LOGIN / LINK VIA GOOGLE =================

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes([
                'https://www.googleapis.com/auth/user.addresses.read',
            ])
            ->with(['prompt' => 'consent'])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {

            return redirect('/login')->withErrors([
                'email' => 'Login dengan Google gagal, anda belum memiliki akun.',
            ]);
        }

        // Ambil alamat dari Google People API (best effort, boleh gagal/kosong)
        $alamat = $this->fetchGoogleAddress($googleUser);


        // =====================================================
        // KASUS 1: USER SUDAH LOGIN (baru saja verifikasi OTP register)
        // -> LANGSUNG TAUTKAN KE AKUN YANG SEDANG LOGIN, TANPA OTP GOOGLE
        //    (tidak perlu OTP lagi karena identitas sudah diverifikasi via OTP email + sesi login)
        // =====================================================

        if (Auth::check()) {
            $currentUser = Auth::user();

            if (strcasecmp($currentUser->email, $googleUser->getEmail()) !== 0) {
                return redirect('/')->withErrors([
                    'email' => 'Email akun Google (' . $googleUser->getEmail() . ') harus sama dengan email akun Anda (' . $currentUser->email . ').',
                ]);
            }

            $dataUpdate = ['google_id' => $googleUser->getId()];

            if (!$currentUser->alamat && $alamat) {
                $dataUpdate['alamat'] = $alamat;
            }

            $currentUser->update($dataUpdate);

            return redirect()->intended('/')->with('status', 'Registrasi selesai, akun Google berhasil dihubungkan.');
        }


        // =====================================================
        // KASUS 2: BELUM LOGIN -> INI ADALAH PERCOBAAN LOGIN VIA GOOGLE (akun lama)
        // =====================================================

        $existingUser = User::where('email', $googleUser->getEmail())->first();

        // Akun sudah ada -> kirim kode OTP untuk konfirmasi sebelum login
        if ($existingUser) {
            // Cek status akun
            if ($existingUser->status == 'nonaktif') {
                return redirect('/login')->withErrors([
                    'email' => 'Akun Anda sedang nonaktif. Silakan hubungi administrator.'
                ]);
            }
            $this->sendGoogleVerificationCode($request, [
                'google_id' => $googleUser->getId(),
                'email'     => $googleUser->getEmail(),
                'name'      => $googleUser->getName(),
                'alamat'    => $alamat,
            ]);

            return redirect()->route('google.confirm.form');
        }

        // Email belum pernah terdaftar sama sekali -> TOLAK, wajib register manual dulu
        return redirect()->route('register')->withErrors([
            'email' => 'Email ' . $googleUser->getEmail() . ' belum terdaftar. Silakan daftar akun secara manual terlebih dahulu.',
        ]);
    }

    private function fetchGoogleAddress($googleUser): ?string
    {
        try {
            $response = Http::withToken($googleUser->token)
                ->get('https://people.googleapis.com/v1/people/me', [
                    'personFields' => 'addresses',
                ]);

            if ($response->successful()) {
                $addresses = $response->json('addresses', []);

                if (!empty($addresses)) {
                    return $addresses[0]['formattedValue'] ?? null;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Gagal ambil alamat Google', ['message' => $e->getMessage()]);
        }

        return null;
    }


    // =====================================================
    // KONFIRMASI LOGIN GOOGLE PAKAI KODE OTP DI EMAIL (untuk akun lama, saat belum login)
    // =====================================================

    private function sendGoogleVerificationCode(Request $request, array $googleData): void
    {
        $code = random_int(100000, 999999);

        $request->session()->put('google_pending', array_merge($googleData, [
            'code'       => $code,
            'expires_at' => now()->addMinutes(5)->timestamp,
        ]));

        Mail::raw(
            "Kode verifikasi login Google Anda: {$code}\nKode ini berlaku selama 5 menit. Jangan bagikan kode ini ke siapa pun.",
            function ($message) use ($googleData) {
                $message->to($googleData['email'])
                    ->subject('Kode Verifikasi Login Google');
            }
        );
    }

    public function showConfirmGoogleLinkForm(Request $request)
    {
        $pending = $request->session()->get('google_pending');

        if (!$pending) {
            return redirect()->route('login')->withErrors([
                'email' => 'Sesi konfirmasi Google sudah kedaluwarsa, silakan coba login lagi.',
            ]);
        }

        return view('customer.auth.confirm-google', [
            'email' => $pending['email'],
        ]);
    }

    public function confirmGoogleLink(Request $request)
    {
        $pending = $request->session()->get('google_pending');

        if (!$pending) {
            return redirect()->route('login')->withErrors([
                'email' => 'Sesi konfirmasi Google sudah kedaluwarsa, silakan coba login lagi.',
            ]);
        }

        $request->validate([
            'code' => ['required'],
        ]);

        if (now()->timestamp > $pending['expires_at']) {
            $request->session()->forget('google_pending');

            return redirect()->route('login')->withErrors([
                'email' => 'Kode verifikasi sudah kedaluwarsa, silakan login ulang.',
            ]);
        }

        if ((string) $request->code !== (string) $pending['code']) {
            return back()->withErrors([
                'code' => 'Kode verifikasi salah.',
            ]);
        }

        $user = User::where('email', $pending['email'])->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun tidak ditemukan, silakan coba lagi.',
            ]);
        }


        // Cek status akun
        if ($user->status == 'nonaktif') {
            $request->session()->forget('google_pending');

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda sedang nonaktif. Silakan hubungi administrator.',
            ]);
        }

        $dataUpdate = ['google_id' => $pending['google_id']];

        if (!$user->alamat && !empty($pending['alamat'])) {
            $dataUpdate['alamat'] = $pending['alamat'];
        }

        $user->update($dataUpdate);

        $request->session()->forget('google_pending');

        Auth::login($user, true);

        return redirect()->intended('/')->with('status', 'Login berhasil.');
    }

    public function resendGoogleCode(Request $request)
    {
        $pending = $request->session()->get('google_pending');

        if (!$pending) {
            return redirect()->route('login')->withErrors([
                'email' => 'Sesi konfirmasi Google sudah kedaluwarsa, silakan coba login lagi.',
            ]);
        }

        $this->sendGoogleVerificationCode($request, [
            'google_id' => $pending['google_id'],
            'email'     => $pending['email'],
            'name'      => $pending['name'],
            'alamat'    => $pending['alamat'],
        ]);

        return back()->with('status', 'Kode baru sudah dikirim ke email Anda.');
    }
}
