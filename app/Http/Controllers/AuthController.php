<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Exception;
use App\Services\WhatsAppService;

use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        $driver = Socialite::driver('google');
        if (app()->environment(['local', 'development', 'testing'])) {
            $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        }
        return $driver->redirect();
    }

    // Handle Google OAuth callback
    public function handleGoogleCallback()
    {
        try {
            $driver = Socialite::driver('google');
            if (app()->environment(['local', 'development', 'testing'])) {
                $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
            }
            $googleUser = $driver->user();
            
            // Search user by google_id or email
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // If user exists but google_id is not set, update it
                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $googleUser->id,
                    ]);
                }
            } else {
                // Register a new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'role' => 'user',
                    'avatar' => $googleUser->avatar,
                    'is_active' => true,
                ]);
            }

            // Always mark email as verified for Google account
            if (empty($user->email_verified_at)) {
                $user->email_verified_at = now();
                $user->save();
            }

            // Check if active
            if (!$user->is_active) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Akun Anda dinonaktifkan. Silakan hubungi admin.',
                ]);
            }

            Auth::login($user, true);

            return redirect()->intended('/')->with('success', 'Selamat datang, ' . $user->name . '!');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error("[Google Login Error] " . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Gagal masuk menggunakan Google. Silakan coba lagi.',
            ]);
        }
    }
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
        return view('auth.register');
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

        // Helper to check Gmail address validity
        function isValidGmail(string $email): bool {
            // Must be a Gmail address
            $domain = substr(strrchr($email, "@"), 1);
            if (strtolower($domain) !== 'gmail.com') {
                return false;
            }
            // Verify MX records exist for gmail.com (basic sanity check)
            return checkdnsrr($domain, 'MX');
        }

        // ---------- Registration validation ----------
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:user,editor'],
            'g-recaptcha-response' => ['required'],
        ]);



        // Reject fake Gmail addresses
        if (!isValidGmail($request->email)) {
            return back()
                ->withErrors(['email' => 'Hanya alamat Gmail yang valid diperbolehkan.'])
                ->withInput();
        }

        // Verify Google reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secret = env('RECAPTCHA_SECRET_KEY');

        $http = \Illuminate\Support\Facades\Http::asForm();
        // Disable SSL verification only in non‑production environments to avoid curl error 60
        if (app()->environment(['local', 'development', 'testing'])) {
            $http = $http->withOptions(['verify' => false]);
        }

        $verification = $http->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $secret,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip(),
        ]);

        if (!($verification->json('success') ?? false)) {
            return back()
                ->withErrors(['g-recaptcha-response' => 'CAPTCHA verification failed. Please try again.'])
                ->withInput();
        }


            $registrationData = [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
                'is_active'=> true,
            ];
        session(['register_data' => $registrationData]);

        // Generate OTP
        $otp = rand(100000, 999999);
        session([
            'verify_otp'     => $otp,
            'verify_email'   => $request->email,
        ]);

        $mailStatus = 'skipped';
        $whatsAppStatus = 'skipped';

// Send email OTP only
        try {
            \Illuminate\Support\Facades\Log::info("[OTP Register] Mengirim OTP ke email: {$request->email}, OTP: {$otp}");
            Mail::to($request->email)->send(new SendOtpMail($otp, $request->name));
            $mailStatus = 'success';
        } catch (Exception $e) {
            $mailStatus = 'error';
            \Illuminate\Support\Facades\Log::error("[OTP Register] GAGAL kirim email ke {$request->email}: " . $e->getMessage());
        }
        $whatsAppStatus = 'skipped';

        RateLimiter::clear($rateKey);

        // Store status flags for debugging if needed
        session([
            'verify_mail_sent' => $mailStatus === 'success',
            'verify_whatsapp_sent' => $whatsAppStatus === 'sent',
        ]);
        return redirect()->route('verification.notice')->with('success', 'Akun berhasil dibuat! Kode OTP telah dikirim via email.');
    }

    // Show email verification form
    public function verifyForm(Request $request)
    {
        if (!session()->has('verify_otp')) {
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

        $otp = session('verify_otp');
        $email = session('verify_email');
        $registerData = session('register_data');

        // If registration data exists, this is a new account verification
        if ($registerData) {
            if ($request->otp != $otp) {
                return back()->withErrors(['otp' => 'Kode verifikasi OTP salah.']);
            }
            // Create the user now
            $user = User::create($registerData);
            $user->email_verified_at = now();
            $user->save();
            // Cleanup registration data
            session()->forget('register_data');
        } else {
            // Existing login verification flow
            $userId = session('verify_user_id');
            if (!$userId || !$otp) {
                return redirect()->route('login')->withErrors(['email' => 'Sesi verifikasi habis.']);
            }
            if ($request->otp != $otp) {
                return back()->withErrors(['otp' => 'Kode verifikasi OTP salah.']);
            }
            $user = User::findOrFail($userId);
            $user->email_verified_at = now();
            $user->save();
        }

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
