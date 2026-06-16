@extends('layouts.auth')
@section('title', 'Verifikasi Email')

@section('content')
<h2 class="auth-title">Verifikasi Email Anda</h2>
<p class="auth-subtitle">Masukkan kode OTP 6 digit yang telah kami kirimkan ke email Anda.</p>

{{-- Simulated Inbox --}}
<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:16px;margin-bottom:24px;font-family:'Inter',sans-serif;font-size:13px;">
    <div style="font-weight:700;color:#b45309;margin-bottom:6px;display:flex;align-items:center;gap:6px;">
        ✉️ <span>Simulasi Email Terkirim</span>
    </div>
    <div style="color:#78350f;line-height:1.6;">
        Ke: <strong style="color:var(--text-primary);">{{ session('verify_email') }}</strong><br>
        Kode OTP Verifikasi Anda: <strong style="font-size:16px;color:var(--green-700);letter-spacing:1px;">{{ session('verify_otp') }}</strong>
    </div>
</div>

@if($errors->any())
<div class="alert alert-error" data-dismiss>
    ❌ {{ $errors->first() }}
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

<div class="auth-divider">atau</div>

<div class="auth-footer">
    Salah mendaftar email? <a href="{{ route('register') }}">Daftar kembali</a>
</div>
@endsection
