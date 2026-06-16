@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
<h2 class="auth-title">Selamat Datang!</h2>
<p class="auth-subtitle">Masuk ke akun TanamanObat.id Anda</p>

@if($errors->any())
<div class="alert alert-error" data-dismiss>
    ❌ {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('login.post') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">Email <span class="required">*</span></label>
        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
            value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
        @error('email') <div class="form-error">⚠ {{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Password <span class="required">*</span></label>
        <div style="position:relative;">
            <input type="password" name="password" id="passwordInput" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                placeholder="Masukkan password" required>
            <button type="button" onclick="togglePassword()" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:16px;" id="eyeBtn">👁</button>
        </div>
        @error('password') <div class="form-error">⚠ {{ $message }}</div> @enderror
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <label style="display:flex;align-items:center;gap:8px;font-family:'Inter',sans-serif;font-size:14px;cursor:pointer;">
            <input type="checkbox" name="remember" style="accent-color:var(--green-500);">
            Ingat saya
        </label>
    </div>

    <button type="submit" class="btn btn-primary w-full" style="width:100%;justify-content:center;padding:14px;">
        🚀 Masuk
    </button>
</form>

<div class="auth-divider">atau</div>

<div class="auth-footer">
    Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
</div>

<div style="margin-top:20px;padding:14px;background:var(--green-50);border-radius:10px;border:1px solid var(--green-200);">
    <p style="font-family:'Inter',sans-serif;font-size:12px;color:var(--green-700);font-weight:600;margin-bottom:6px;">🔑 Akun Demo:</p>
    <div style="font-family:'Inter',sans-serif;font-size:11px;color:var(--text-secondary);line-height:1.8;">
        <div>Admin: admin@tanamanobat.id / password</div>
        <div>Editor: editor@tanamanobat.id / password</div>
        <div>User: user@tanamanobat.id / password</div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const btn = document.getElementById('eyeBtn');
    if (input.type === 'password') { input.type = 'text'; btn.textContent = '🙈'; }
    else { input.type = 'password'; btn.textContent = '👁'; }
}
</script>
@endpush
@endsection
