@extends('layouts.auth')
@section('title', 'Verifikasi Email')

@section('content')
<h2 class="auth-title">Verifikasi Email Anda</h2>
<p class="auth-subtitle">Masukkan kode OTP 6 digit yang telah kami kirimkan ke email Anda.</p>

@if(session('success'))
<div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;border-radius:12px;padding:14px;margin-bottom:20px;font-family:'Inter',sans-serif;font-size:14px;display:flex;align-items:center;gap:8px;">
    <span>✅</span> <span>{{ session('success') }}</span>
</div>
@endif

@if(session('info'))
<div style="background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;border-radius:12px;padding:14px;margin-bottom:20px;font-family:'Inter',sans-serif;font-size:14px;display:flex;align-items:center;gap:8px;">
    <span>ℹ️</span> <span>{{ session('info') }}</span>
</div>
@endif

@if($errors->any())
<div style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:12px;padding:14px;margin-bottom:20px;font-family:'Inter',sans-serif;font-size:14px;display:flex;align-items:center;gap:8px;">
    <span>❌</span> <span>{{ $errors->first() }}</span>
</div>
@endif

{{-- Simulated Inbox --}}
@if(session()->has('verify_otp') && session('verify_mail_sent') !== true)
<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:16px;padding:16px;margin-bottom:24px;font-family:'Inter',sans-serif;font-size:13px;box-shadow:0 4px 12px rgba(251,191,36,0.08);">
    <div style="font-weight:700;color:#b45309;margin-bottom:6px;display:flex;align-items:center;gap:6px;">
        🛠️ <span style="text-transform: uppercase; letter-spacing: 0.5px; font-size: 11px;">Sandbox Mode (Simulasi OTP)</span>
    </div>
    <div style="color:#78350f;line-height:1.6;">
        Terkirim ke: <strong style="color:var(--text-primary);">{{ session('verify_email') }}</strong><br>
        Kode OTP Anda: <strong style="font-size:16px;color:#15803d;letter-spacing:1px;">{{ session('verify_otp') }}</strong>
    </div>
    <div style="margin-top:10px;font-size:11px;color:#92400e;border-top:1px solid #fef3c7;padding-top:8px;">
        *Kotak simulasi ini hanya muncul karena pengiriman email asli ke server SMTP gagal/belum diatur dengan benar di file <code>.env</code>.
    </div>
</div>
@endif

<form method="POST" action="{{ route('verification.verify') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">Kode Verifikasi (OTP) <span class="required">*</span></label>
        <input type="text" name="otp" class="form-control {{ $errors->has('otp') ? 'is-invalid' : '' }}"
            placeholder="Contoh: 123456" maxlength="6" required autofocus autocomplete="off"
            style="text-align:center;font-size:20px;font-weight:700;letter-spacing:6px;padding:12px;">
        @error('otp') <div class="form-error">⚠ {{ $message }}</div> @enderror
    </div>

    <button type="submit" class="btn btn-primary w-full" style="width:100%;justify-content:center;padding:14px;">
        ✅ Verifikasi & Masuk
    </button>
</form>

<form method="POST" action="{{ route('verification.resend') }}" style="margin-top: 16px;">
    @csrf
    <button type="submit" class="btn-resend">
        🔄 Kirim Ulang OTP
    </button>
</form>

<style>
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
        background: #f1f5f9 !important;
        color: #1e293b !important;
        border-color: #cbd5e1 !important;
    }
</style>

<div class="auth-divider">atau</div>

<div class="auth-footer">
    Salah mendaftar email? <a href="{{ route('register') }}">Daftar kembali</a>
</div>
@endsection
