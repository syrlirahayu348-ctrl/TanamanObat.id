@extends('layouts.auth')
@section('title', 'Daftar Akun')

@section('content')
<h2 class="auth-title">Buat Akun Baru</h2>
<p class="auth-subtitle">Bergabung dengan komunitas TanamanObat.id</p>

@if($errors->any())
<div class="alert alert-error" data-dismiss>
    ❌ {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('register.post') }}">
    @csrf

    {{-- Role Selection --}}
    <input type="hidden" name="role" id="roleInput" value="user">
    <div style="margin-bottom: 20px;">
        <label class="form-label">Daftar sebagai</label>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 8px;">
            <div id="roleMember" class="role-select-card" onclick="selectRole('user')" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 14px; border: 2px solid var(--green-600); border-radius: var(--radius-md); background: var(--green-50); cursor: pointer; text-align: center; transition: var(--transition);">
                <span style="font-size: 20px; margin-bottom: 6px;">👤</span>
                <span style="font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 700; color: var(--green-800);">User</span>
            </div>
            <div id="roleContributor" class="role-select-card" onclick="selectRole('editor')" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 14px; border: 2px solid #e2e8f0; border-radius: var(--radius-md); background: white; cursor: pointer; text-align: center; transition: var(--transition);">
                <span style="font-size: 20px; margin-bottom: 6px;">✍️</span>
                <span style="font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 700; color: var(--text-secondary);">Editor</span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
            value="{{ old('name') }}" placeholder="Nama lengkap Anda" required autofocus>
        @error('name') <div class="form-error">⚠ {{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label class="form-label">Email <span class="required">*</span></label>
        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
            value="{{ old('email') }}" placeholder="nama@email.com" required>
        @error('email') <div class="form-error">⚠ {{ $message }}</div> @enderror
    </div>



    <div class="form-group">
        <label class="form-label">Password <span class="required">*</span></label>
        <div style="position:relative;">
            <input type="password" name="password" id="passwordInput" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                placeholder="Minimal 8 karakter" required>
            <button type="button" onclick="togglePassword('passwordInput','eyeBtn1')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:16px;" id="eyeBtn1">👁</button>
        </div>
        @error('password') <div class="form-error">⚠ {{ $message }}</div> @enderror

        {{-- Password Strength Checklist --}}
        <div style="margin-top: 10px; font-family: 'Inter', sans-serif;">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px; margin-bottom: 6px;">
                <div id="bar1" style="height: 4px; background: #e2e8f0; border-radius: 2px; transition: background 0.2s;"></div>
                <div id="bar2" style="height: 4px; background: #e2e8f0; border-radius: 2px; transition: background 0.2s;"></div>
                <div id="bar3" style="height: 4px; background: #e2e8f0; border-radius: 2px; transition: background 0.2s;"></div>
                <div id="bar4" style="height: 4px; background: #e2e8f0; border-radius: 2px; transition: background 0.2s;"></div>
            </div>
            <div id="strengthText" style="font-size: 11px; font-weight: 700; color: var(--text-muted); margin-bottom: 8px;">Belum diisi</div>
            
            <div style="display: flex; flex-direction: column; gap: 4px; font-size: 11px;">
                <div id="ruleLength" style="color: #ef4444; display: flex; align-items: center; gap: 6px;">
                    <span class="icon">✗</span> <span>Minimal 8 karakter</span>
                </div>
                <div id="ruleUpper" style="color: #ef4444; display: flex; align-items: center; gap: 6px;">
                    <span class="icon">✗</span> <span>Huruf besar (A-Z)</span>
                </div>
                <div id="ruleNumber" style="color: #ef4444; display: flex; align-items: center; gap: 6px;">
                    <span class="icon">✗</span> <span>Angka (0-9)</span>
                </div>
                <div id="ruleSymbol" style="color: #ef4444; display: flex; align-items: center; gap: 6px;">
                    <span class="icon">✗</span> <span>Simbol (!@#$%^&*)</span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
        <div style="position:relative;">
            <input type="password" name="password_confirmation" id="passwordConfirm" class="form-control"
                placeholder="Ulangi password" required>
            <button type="button" onclick="togglePassword('passwordConfirm','eyeBtn2')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:16px;" id="eyeBtn2">👁</button>
        </div>
    </div>

<div class="form-group">
    <label class="form-label">Keamanan (CAPTCHA) <span class="required">*</span></label>
    @php $recaptchaKey = env('RECAPTCHA_SITE_KEY'); @endphp
    @if($recaptchaKey)
        <div class="g-recaptcha" data-sitekey="{{ $recaptchaKey }}"></div>
        @error('g-recaptcha-response') <div class="form-error">⚠ {{ $message }}</div> @enderror
    @endif
</div>

    <div style="margin-bottom:20px;">
        <label style="display:flex;align-items:flex-start;gap:8px;font-family:'Inter',sans-serif;font-size:13px;cursor:pointer;color:var(--text-secondary);">
            <input type="checkbox" required style="accent-color:var(--green-500);margin-top:2px;flex-shrink:0;">
            Saya setuju dengan syarat & ketentuan yang berlaku dan memahami bahwa informasi tanaman obat bukan pengganti konsultasi medis.
        </label>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px;margin-bottom:12px;">
        🌿 Buat Akun
    </button>

    <a href="{{ route('login.google') }}" class="btn btn-outline w-full" style="width:100%; display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; border:1px solid #d1d5db; border-radius:8px; text-decoration:none; color:#374151; background:#ffffff; font-family:'Inter',sans-serif; font-size:14px; font-weight:500; transition:all 0.2s ease-in-out; margin-bottom:12px;">
        <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.64 9.2045c0-.6381-.0573-1.2518-.1636-1.8409H9v3.4814h4.8436c-.2086 1.125-.8427 2.0782-1.7959 2.7164v2.2581h2.9087c1.7018-1.5668 2.6836-3.874 2.6836-6.615z" fill="#4285F4"/>
            <path d="M9 18c2.43 0 4.4673-.806 5.9564-2.1805l-2.9087-2.2581c-.8059.54-1.8368.859-3.0477.859-2.344 0-4.3282-1.5831-5.036-3.7104H.9574v2.3318C2.4382 15.9832 5.4818 18 9 18z" fill="#34A853"/>
            <path d="M3.964 10.71c-.18-.54-.2822-1.1168-.2822-1.71s.1023-1.17.2823-1.71V4.9582H.9573A8.9965 8.9965 0 0 0 0 9c0 1.4523.3477 2.8227.9573 4.0418l3.0067-2.3318z" fill="#FBBC05"/>
            <path d="M9 3.5795c1.3214 0 2.5077.4541 3.4405 1.346l2.5813-2.5814C13.4632.8918 11.4259 0 9 0 5.4818 0 2.4382 2.0168.9573 4.9582l3.0067 2.3318C4.6718 5.1627 6.656 3.5795 9 3.5795z" fill="#EA4335"/>
        </svg>
        Daftar dengan Google
    </a>
</form>

<div class="auth-divider">atau</div>
<div class="auth-footer">
    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
</div>

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
function togglePassword(inputId, btnId) {
    const input = document.getElementById(inputId);
    const btn = document.getElementById(btnId);
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
    } else {
        input.type = 'password';
        btn.textContent = '👁';
    }
}

function updateRuleStatus(id, met) {
    const el = document.getElementById(id);
    const icon = el.querySelector('.icon');
    if (met) {
        el.style.color = '#22c55e'; // Green
        icon.textContent = '✓';
    } else {
        el.style.color = '#ef4444'; // Red
        icon.textContent = '✗';
    }
}

function selectRole(role) {
    document.getElementById('roleInput').value = role;
    const member = document.getElementById('roleMember');
    const contributor = document.getElementById('roleContributor');
    if (role === 'user') {
        member.style.borderColor = 'var(--green-600)';
        member.style.background = 'var(--green-50)';
        member.querySelector('span:last-child').style.color = 'var(--green-800)';
        
        contributor.style.borderColor = '#e2e8f0';
        contributor.style.background = 'white';
        contributor.querySelector('span:last-child').style.color = 'var(--text-secondary)';
    } else {
        contributor.style.borderColor = 'var(--green-600)';
        contributor.style.background = 'var(--green-50)';
        contributor.querySelector('span:last-child').style.color = 'var(--green-800)';
        
        member.style.borderColor = '#e2e8f0';
        member.style.background = 'white';
        member.querySelector('span:last-child').style.color = 'var(--text-secondary)';
    }
}

// Password strength real-time checker
const passwordInput = document.getElementById('passwordInput');
passwordInput.addEventListener('input', () => {
    const val = passwordInput.value;
    const hasLength = val.length >= 8;
    const hasUpper = /[A-Z]/.test(val);
    const hasNumber = /[0-9]/.test(val);
    const hasSymbol = /[^A-Za-z0-9]/.test(val);
    
    // Update rules visual status
    updateRuleStatus('ruleLength', hasLength);
    updateRuleStatus('ruleUpper', hasUpper);
    updateRuleStatus('ruleNumber', hasNumber);
    updateRuleStatus('ruleSymbol', hasSymbol);
    
    // Calculate score
    let score = 0;
    if (hasLength) score++;
    if (hasUpper) score++;
    if (hasNumber) score++;
    if (hasSymbol) score++;
    
    // Update bars
    const bars = ['bar1', 'bar2', 'bar3', 'bar4'];
    const colors = ['#ef4444', '#f59e0b', '#3b82f6', '#22c55e'];
    const texts = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat'];
    
    bars.forEach((barId, index) => {
        const bar = document.getElementById(barId);
        if (index < score) {
            bar.style.background = colors[score - 1];
        } else {
            bar.style.background = '#e2e8f0';
        }
    });
    
    const textEl = document.getElementById('strengthText');
    if (val.length === 0) {
        textEl.textContent = 'Belum diisi';
        textEl.style.color = 'var(--text-muted)';
    } else {
        textEl.textContent = texts[score - 1] || 'Sangat Lemah';
        textEl.style.color = colors[score - 1] || '#ef4444';
    }
});


</script>
@endpush
@endsection
