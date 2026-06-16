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
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
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

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Password Saat Ini <span class="required">*</span></label>
                        <input type="password" name="current_password" class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}" placeholder="Password lama" required>
                        @error('current_password') <div class="form-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password Baru <span class="required">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                        @error('password') <div class="form-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                    </div>

                    <button type="submit" class="btn btn-outline" style="width:100%;justify-content:center;">🔐 Update Password</button>
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
