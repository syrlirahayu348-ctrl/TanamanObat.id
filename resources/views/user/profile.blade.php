@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-header__title">👤 Profil Saya</h1>
        <p class="page-header__subtitle">Kelola informasi akun Anda</p>
    </div>
</div>

<div class="section">
    <div class="container container-sm">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;">

            {{-- Update Profile --}}
            <div class="card" style="padding:32px;">
                <h2 style="font-size:20px;margin-bottom:24px;font-family:'Playfair Display',serif;">Informasi Akun</h2>

                @if($errors->any())
                <div class="alert alert-error" data-dismiss>❌ {{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Avatar --}}
                    <div class="form-group" style="text-align:center;">
                        <div class="user-avatar" style="width:80px;height:80px;font-size:32px;margin:0 auto 12px;">
                            @if($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}" alt="Avatar" id="avatarPreview">
                            @else
                                {{ strtoupper(substr($user->name,0,1)) }}
                            @endif
                        </div>
                        <label for="avatarInput" style="cursor:pointer;" class="btn btn-outline btn-sm">📷 Ganti Foto</label>
                        <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*" data-image-preview="avatarPreview">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="form-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" pattern="[a-zA-Z0-9._%+-]+@gmail\.com" title="Harap gunakan email Gmail asli (akhiran @gmail.com)" required>
                        @error('email') <div class="form-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <div class="form-control" style="background:var(--green-50);cursor:not-allowed;">{!! $user->role_badge !!} {{ ucfirst($user->role) }}</div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">💾 Simpan Perubahan</button>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="card" style="padding:32px;">
                <h2 style="font-size:20px;margin-bottom:24px;font-family:'Playfair Display',serif;">Ganti Password</h2>

                <form method="POST" id="passwordForm" action="{{ route('profile.password') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Password Saat Ini <span class="required">*</span></label>
                        <div style="position:relative;">
                            <input type="password" name="current_password" id="currentPasswordInput" class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}" placeholder="Password lama" required>
                            <button type="button" onclick="togglePassword('currentPasswordInput','eyeBtnCurrent')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:16px;" id="eyeBtnCurrent">👁</button>
                        </div>
                        @error('current_password') <div class="form-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password Baru <span class="required">*</span></label>
                        <div style="position:relative;">
                            <input type="password" name="password" id="passwordInput" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Minimal 8 karakter" required>
                            <button type="button" onclick="togglePassword('passwordInput','eyeBtnNew')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:16px;" id="eyeBtnNew">👁</button>
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
                        <label class="form-label">Konfirmasi Password Baru <span class="required">*</span></label>
                        <div style="position:relative;">
                            <input type="password" name="password_confirmation" id="passwordConfirm" class="form-control" placeholder="Ulangi password baru" required>
                            <button type="button" onclick="togglePassword('passwordConfirm','eyeBtnConfirm')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:16px;" id="eyeBtnConfirm">👁</button>
                        </div>
                    </div>

                    <button type="submit" id="passwordSubmitBtn" class="btn btn-outline" style="width:100%;justify-content:center;" disabled>🔐 Update Password</button>
                </form>

                {{-- Account Info --}}
                <div style="margin-top:28px;padding-top:24px;border-top:1px solid var(--green-100);">
                    <h3 style="font-size:15px;margin-bottom:14px;font-family:'Inter',sans-serif;font-weight:700;">Statistik Akun</h3>
                    <div style="display:flex;flex-direction:column;gap:10px;font-family:'Inter',sans-serif;font-size:14px;">
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:var(--text-muted);">❤️ Favorit tersimpan</span>
                            <strong>{{ auth()->user()->favorites()->count() }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:var(--text-muted);">📅 Bergabung sejak</span>
                            <strong>{{ auth()->user()->created_at->format('d M Y') }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:var(--text-muted);">✅ Status akun</span>
                            <span style="color:var(--green-600);font-weight:600;">● Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId, btnId) {
    const input = document.getElementById(inputId);
    const btn = document.getElementById(btnId);
    if (input.type === 'password') { input.type = 'text'; btn.textContent = '🙈'; }
    else { input.type = 'password'; btn.textContent = '👁'; }
}

// Password strength real-time checker
const passwordInput = document.getElementById('passwordInput');
const passwordForm = document.getElementById('passwordForm');
const passwordSubmitBtn = document.getElementById('passwordSubmitBtn');

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

    // Enable/disable submit button based on score
    if (score === 4) {
        passwordSubmitBtn.removeAttribute('disabled');
    } else {
        passwordSubmitBtn.setAttribute('disabled', 'true');
    }
});

// Double check on form submission
passwordForm.addEventListener('submit', (e) => {
    const val = passwordInput.value;
    const isStrong = val.length >= 8 && /[A-Z]/.test(val) && /[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val);
    if (!isStrong) {
        e.preventDefault();
        alert('Password baru belum memenuhi semua persyaratan keamanan!');
    }
});

function updateRuleStatus(id, met) {
    const el = document.getElementById(id);
    if (el) {
        const icon = el.querySelector('.icon');
        if (met) {
            el.style.color = '#22c55e'; // Green
            if (icon) icon.textContent = '✓';
        } else {
            el.style.color = '#ef4444'; // Red
            if (icon) icon.textContent = '✗';
        }
    }
}
</script>
@endpush
