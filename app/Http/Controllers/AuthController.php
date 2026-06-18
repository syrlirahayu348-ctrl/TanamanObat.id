<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Exception;

class AuthController extends Controller
{
    // Show login form
    public function loginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $rateKey = 'login-attempts:' . $request->ip();

        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            $seconds = RateLimiter::availableIn($rateKey);
            $minutes = ceil($seconds / 60);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan masuk. Silakan tunggu selama {$minutes} menit.",
            ])->withInput($request->except('password'));
        }

        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && !$user->is_active) {
            RateLimiter::hit($rateKey, 120);
            return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan oleh administrator.'])->withInput();
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if (Auth::user()->email_verified_at === null) {
                // User has not verified email yet, force verification flow
                $user = Auth::user();
                Auth::logout();

                $otp = rand(100000, 999999);
                session([
                    'verify_user_id' => $user->id,
                    'verify_otp'     => $otp,
                    'verify_email'   => $user->email,
                ]);

                // Kirim email OTP
                $mailStatus = 'success';
                try {
                    \Illuminate\Support\Facades\Log::info("[OTP Login] Mengirim OTP ke: {$user->email}, OTP: {$otp}");
                    Mail::to($user->email)->send(new SendOtpMail($otp, $user->name));
                    \Illuminate\Support\Facades\Log::info("[OTP Login] Email berhasil dikirim ke: {$user->email}");
                } catch (Exception $e) {
                    $mailStatus = 'error';
                    \Illuminate\Support\Facades\Log::error("[OTP Login] GAGAL kirim email ke {$user->email}: " . $e->getMessage());
                    \Illuminate\Support\Facades\Log::error("[OTP Login] Stack trace: " . $e->getTraceAsString());
                }

                if ($mailStatus === 'error') {
                    session(['verify_mail_sent' => false]);
                    return redirect()->route('verification.notice')->with('info', 'Silakan verifikasi email Anda. (Catatan: Pengiriman email gagal, silakan periksa konfigurasi email Anda. Kode OTP simulasi ditampilkan di bawah).');
                }

                session(['verify_mail_sent' => true]);
                return redirect()->route('verification.notice')->with('success', 'Kode OTP baru telah dikirim ke email Anda. Silakan verifikasi email Anda.');
            }

            $request->session()->regenerate();
            RateLimiter::clear($rateKey);
            return redirect()->intended('/')->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        RateLimiter::hit($rateKey, 120); // rate limiting 120s (2 minutes)

        $remaining = RateLimiter::remaining($rateKey, 3);
        $attemptsMessage = $remaining > 0 
            ? " Email atau password salah. Sisa percobaan: {$remaining}."
            : " Email atau password salah. Percobaan habis, silakan coba lagi dalam 2 menit.";

        return back()->withErrors([
            'email' => $attemptsMessage,
        ])->withInput($request->except('password'));
    }

    // Show register form
    public function registerForm()
    {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session(['register_captcha' => $num1 + $num2]);

        return view('auth.register', compact('num1', 'num2'));
    }

    // Handle register
    public function register(Request $request)
    {
        $rateKey = 'register-attempts:' . $request->ip();

        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            $seconds = RateLimiter::availableIn($rateKey);
            $minutes = ceil($seconds / 60);
            return back()->withErrors([
                'name' => "Terlalu banyak percobaan pendaftaran. Silakan tunggu selama {$minutes} menit.",
            ])->withInput();
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->symbols()],
            'captcha'  => ['required', 'integer'],
            'role'     => ['required', 'string', 'in:user,editor'],
        ]);

        // Validate Captcha
        $expectedCaptcha = session('register_captcha');
        if ($request->captcha != $expectedCaptcha) {
            RateLimiter::hit($rateKey, 120);
            return back()->withErrors([
                'captcha' => 'Jawaban CAPTCHA salah.',
            ])->withInput();
        }

        // Create User (unverified)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'is_active' => true,
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);
        session([
            'verify_user_id' => $user->id,
            'verify_otp'     => $otp,
            'verify_email'   => $user->email,
        ]);

        // Kirim email OTP
        $mailStatus = 'success';
        try {
            \Illuminate\Support\Facades\Log::info("[OTP Register] Mengirim OTP ke: {$user->email}, OTP: {$otp}");
            Mail::to($user->email)->send(new SendOtpMail($otp, $user->name));
            \Illuminate\Support\Facades\Log::info("[OTP Register] Email berhasil dikirim ke: {$user->email}");
        } catch (Exception $e) {
            $mailStatus = 'error';
            \Illuminate\Support\Facades\Log::error("[OTP Register] GAGAL kirim email ke {$user->email}: " . $e->getMessage());
            \Illuminate\Support\Facades\Log::error("[OTP Register] Stack trace: " . $e->getTraceAsString());
        }

        RateLimiter::clear($rateKey);

        if ($mailStatus === 'error') {
            session(['verify_mail_sent' => false]);
            return redirect()->route('verification.notice')->with('success', 'Akun berhasil dibuat! (Catatan: Email verifikasi gagal terkirim. Anda dapat menggunakan kode OTP simulasi di bawah untuk melanjutkan pengujian).');
        }

        session(['verify_mail_sent' => true]);
        return redirect()->route('verification.notice')->with('success', 'Akun berhasil dibuat! Kode verifikasi OTP telah dikirim ke email Anda.');
    }

    // Show email verification form
    public function verifyForm()
    {
        if (!session()->has('verify_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.verify-email');
    }

    // Handle email verification code submit
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = session('verify_user_id');
        $otp = session('verify_otp');

        if (!$userId || !$otp) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi verifikasi habis.']);
        }

        if ($request->otp != $otp) {
            return back()->withErrors(['otp' => 'Kode verifikasi OTP salah.']);
        }

        $user = User::findOrFail($userId);
        $user->email_verified_at = now();
        $user->save();

        // Login user
        Auth::login($user);

        // Clear session verify data
        session()->forget(['verify_user_id', 'verify_otp', 'verify_email']);

        return redirect('/')
            ->with('verified', 'Email Anda berhasil diverifikasi! Selamat datang, ' . $user->name . '!');
    }

    // Resend OTP verification code
    public function resendOtp(Request $request)
    {
        $userId = session('verify_user_id');
        $email = session('verify_email');

        if (!$userId || !$email) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi verifikasi telah berakhir. Silakan login kembali.']);
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Pengguna tidak ditemukan.']);
        }

        // Generate new OTP
        $otp = rand(100000, 999999);
        session([
            'verify_otp' => $otp,
        ]);

        // Kirim email OTP
        $mailStatus = 'success';
        try {
            Mail::to($user->email)->send(new SendOtpMail($otp, $user->name));
        } catch (Exception $e) {
            $mailStatus = 'error';
            \Illuminate\Support\Facades\Log::error("OTP Resend Mail Error: " . $e->getMessage());
        }

        if ($mailStatus === 'error') {
            session(['verify_mail_sent' => false]);
            return back()->with('info', 'Kode OTP baru berhasil dibuat, namun gagal mengirim email. Pastikan konfigurasi SMTP di file .env Anda sudah benar. (Kode OTP simulasi diperbarui di bawah).');
        }

        session(['verify_mail_sent' => true]);
        return back()->with('success', 'Kode OTP baru telah berhasil dikirim ke email Anda!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda berhasil keluar.');
    }
}
