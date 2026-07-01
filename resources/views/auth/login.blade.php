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

    <button type="submit" class="btn btn-primary w-full" style="width:100%;justify-content:center;padding:14px;margin-bottom:12px;">
        🚀 Masuk
    </button>

    <a href="{{ route('login.google') }}" class="btn btn-outline w-full" style="width:100%; display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; border:1px solid #d1d5db; border-radius:8px; text-decoration:none; color:#374151; background:#ffffff; font-family:'Inter',sans-serif; font-size:14px; font-weight:500; transition:all 0.2s ease-in-out; margin-bottom:12px;">
        <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.64 9.2045c0-.6381-.0573-1.2518-.1636-1.8409H9v3.4814h4.8436c-.2086 1.125-.8427 2.0782-1.7959 2.7164v2.2581h2.9087c1.7018-1.5668 2.6836-3.874 2.6836-6.615z" fill="#4285F4"/>
            <path d="M9 18c2.43 0 4.4673-.806 5.9564-2.1805l-2.9087-2.2581c-.8059.54-1.8368.859-3.0477.859-2.344 0-4.3282-1.5831-5.036-3.7104H.9574v2.3318C2.4382 15.9832 5.4818 18 9 18z" fill="#34A853"/>
            <path d="M3.964 10.71c-.18-.54-.2822-1.1168-.2822-1.71s.1023-1.17.2823-1.71V4.9582H.9573A8.9965 8.9965 0 0 0 0 9c0 1.4523.3477 2.8227.9573 4.0418l3.0067-2.3318z" fill="#FBBC05"/>
            <path d="M9 3.5795c1.3214 0 2.5077.4541 3.4405 1.346l2.5813-2.5814C13.4632.8918 11.4259 0 9 0 5.4818 0 2.4382 2.0168.9573 4.9582l3.0067 2.3318C4.6718 5.1627 6.656 3.5795 9 3.5795z" fill="#EA4335"/>
        </svg>
        Masuk dengan Google
    </a>
</form>

<div class="auth-divider">atau</div>

<div class="auth-footer">
    Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
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
