@extends('layouts.auth')
@section('title', 'Verifikasi Email')

@section('content')
<h2 class="auth-title">Verifikasi Email Anda</h2>
<p class="auth-subtitle">
    Kami telah mengirim kode OTP 6 digit ke
    <strong style="color: var(--primary, #15803d);">{{ session('verify_email') }}</strong>
</p>

@if(session('success'))
<div class="alert-box alert-success">
    <span>✅</span> <span>{{ session('success') }}</span>
</div>
@endif

@if(session('info'))
<div class="alert-box alert-info">
    <span>ℹ️</span> <span>{{ session('info') }}</span>
</div>
@endif

@if($errors->any())
<div class="alert-box alert-error">
    <span>❌</span> <span>{{ $errors->first() }}</span>
</div>
@endif

{{-- ⚠️ Peringatan cek spam — selalu tampil saat email terkirim --}}
@if(session('verify_mail_sent') === true || session()->has('verify_user_id'))
<div class="spam-alert">
    <div class="spam-alert__icon">📬</div>
    <div class="spam-alert__body">
        <div class="spam-alert__title">Email OTP sudah dikirim!</div>
        <div class="spam-alert__msg">
            Jika tidak ada di <strong>inbox</strong>, silakan cek di
            <strong>📁 folder Spam / Junk</strong> dan tandai sebagai
            <em>"Bukan Spam"</em> agar email berikutnya masuk inbox.
        </div>
    </div>
</div>
@endif

{{-- Simulated Inbox (fallback jika mail gagal) --}}
@if(session()->has('verify_otp') && session('verify_mail_sent') !== true)
<div class="sandbox-box">
    <div class="sandbox-title">
        🛠️ <span>Sandbox Mode (Email Gagal Terkirim)</span>
    </div>
    <div class="sandbox-body">
        Terkirim ke: <strong>{{ session('verify_email') }}</strong><br>
        Kode OTP Anda: <strong class="otp-display">{{ session('verify_otp') }}</strong>
    </div>
    <div class="sandbox-note">
        *Kotak ini muncul karena pengiriman SMTP gagal. Pastikan konfigurasi .env sudah benar.
    </div>
</div>
@endif

<form method="POST" action="{{ route('verification.verify') }}" id="otp-form">
    @csrf
    <div class="form-group">
        <label class="form-label">Kode Verifikasi (OTP) <span class="required">*</span></label>
        <div class="otp-input-wrapper">
            <input type="text" name="otp" id="otp-input"
                class="form-control otp-input {{ $errors->has('otp') ? 'is-invalid' : '' }}"
                placeholder="_ _ _ _ _ _" maxlength="6" required autofocus autocomplete="off"
                inputmode="numeric" pattern="[0-9]{6}">
        </div>
        @error('otp') <div class="form-error">⚠ {{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary w-full btn-submit">
        ✅ Verifikasi &amp; Masuk
    </button>
</form>

<div class="resend-section">
    <p class="resend-label" id="resend-label">
        Tidak menerima email? Kirim ulang dalam
        <span id="countdown" class="countdown-timer">60</span> detik
    </p>
    <form method="POST" action="{{ route('verification.resend') }}" id="resend-form" style="display:none;">
        @csrf
        <button type="submit" class="btn-resend">
            🔄 Kirim Ulang OTP
        </button>
    </form>
</div>

<div class="auth-divider">atau</div>

<div class="auth-footer">
    Salah mendaftar email? <a href="{{ route('register') }}">Daftar kembali</a>
</div>

<style>
    /* Alert boxes */
    .alert-box {
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 16px;
        font-size: 14px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        line-height: 1.5;
    }
    .alert-success { background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; }
    .alert-info    { background:#eff6ff; border:1px solid #bfdbfe; color:#1e40af; }
    .alert-error   { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; }

    /* Spam Alert — selalu tampil, lebih mencolok */
    .spam-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #fffbeb;
        border: 1.5px solid #f59e0b;
        border-left: 5px solid #f59e0b;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 20px;
        position: relative;
        animation: spamPulse 2s ease-in-out;
    }
    @keyframes spamPulse {
        0%   { box-shadow: 0 0 0 0 rgba(245,158,11,0.3); }
        50%  { box-shadow: 0 0 0 6px rgba(245,158,11,0); }
        100% { box-shadow: 0 0 0 0 rgba(245,158,11,0); }
    }
    .spam-alert__icon {
        font-size: 24px;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .spam-alert__body { flex: 1; }
    .spam-alert__title {
        font-size: 13.5px;
        font-weight: 700;
        color: #92400e;
        margin-bottom: 5px;
    }
    .spam-alert__msg {
        font-size: 13px;
        color: #78350f;
        line-height: 1.65;
    }
    .spam-alert__msg strong { color: #b45309; }

    /* Sandbox fallback box */
    .sandbox-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 20px;
        font-size: 13px;
    }
    .sandbox-title {
        font-weight: 700;
        color: #b45309;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 11px;
    }
    .sandbox-body { color: #78350f; line-height: 1.7; }
    .otp-display {
        font-size: 18px;
        color: #15803d;
        letter-spacing: 3px;
        font-family: 'Courier New', monospace;
    }
    .sandbox-note {
        margin-top: 10px;
        font-size: 11px;
        color: #92400e;
        border-top: 1px solid #fef3c7;
        padding-top: 8px;
    }

    /* OTP Input */
    .otp-input-wrapper { position: relative; }
    .otp-input {
        text-align: center;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: 8px;
        padding: 14px;
        border-radius: 12px;
        transition: all 0.2s;
    }
    .otp-input:focus {
        border-color: #15803d;
        box-shadow: 0 0 0 3px rgba(21,128,61,0.12);
        outline: none;
    }

    /* Submit btn */
    .btn-submit {
        width: 100%;
        justify-content: center;
        padding: 14px;
        font-size: 15px;
        font-weight: 700;
        margin-top: 4px;
        border-radius: 12px;
        transition: all 0.2s;
    }

    /* Resend section */
    .resend-section { margin-top: 16px; }
    .resend-label {
        text-align: center;
        font-size: 13px;
        color: #64748b;
        margin: 0 0 8px 0;
    }
    .countdown-timer {
        font-weight: 700;
        color: #15803d;
    }
    .btn-resend {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px;
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }
    .btn-resend:hover {
        background: #f1f5f9;
        color: #1e293b;
        border-color: #cbd5e1;
    }
</style>

<script>
    // Auto-format OTP input: angka saja, auto-submit saat 6 digit
    const otpInput = document.getElementById('otp-input');
    if (otpInput) {
        otpInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
            if (this.value.length === 6) {
                document.getElementById('otp-form').submit();
            }
        });
    }

    // Countdown timer untuk resend OTP
    let seconds = 60;
    const countdownEl = document.getElementById('countdown');
    const resendLabel = document.getElementById('resend-label');
    const resendForm = document.getElementById('resend-form');

    const timer = setInterval(() => {
        seconds--;
        if (countdownEl) countdownEl.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(timer);
            if (resendLabel) resendLabel.style.display = 'none';
            if (resendForm) resendForm.style.display = 'block';
        }
    }, 1000);
</script>
@endsection
