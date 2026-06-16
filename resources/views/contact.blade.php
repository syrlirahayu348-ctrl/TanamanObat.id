@extends('layouts.app')
@section('title', 'Hubungi Kami')
@section('meta_desc', 'Hubungi tim admin TanamanObat.id jika memiliki pertanyaan, saran, atau aduan.')

@section('content')
<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span>›</span>
            <span>Hubungi Kami</span>
        </div>
        <h1 class="page-header__title">📬 Hubungi Kami</h1>
        <p class="page-header__subtitle">Kirimkan pertanyaan, laporan kesalahan informasi, atau kerja sama</p>
    </div>
</div>

<div class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:40px;align-items:start;">
            
            {{-- Left column: Contact Info --}}
            <div style="display:flex;flex-direction:column;gap:24px;">
                <div class="card-glass" style="padding:32px;">
                    <h3 style="font-size:20px;font-family:'Playfair Display',serif;margin-bottom:20px;color:var(--text-primary);">
                        Saluran Komunikasi Resmi
                    </h3>
                    <p style="font-family:'Inter',sans-serif;font-size:14px;color:var(--text-secondary);margin-bottom:24px;line-height:1.7;">
                        Jika Anda ingin berdiskusi langsung, menemukan kesalahan pada ensiklopedia kami, atau membutuhkan kerja sama, silakan gunakan saluran komunikasi di bawah ini.
                    </p>

                    <div style="display:flex;flex-direction:column;gap:20px;font-family:'Inter',sans-serif;font-size:14px;">
                        {{-- WhatsApp --}}
                        <div style="display:flex;align-items:center;gap:14px;">
                            <div style="width:40px;height:40px;border-radius:10px;background:#dcfce7;display:flex;align-items:center;justify-content:center;font-size:20px;">
                                🟢
                            </div>
                            <div>
                                <div style="font-weight:700;color:var(--text-primary);">WhatsApp Admin</div>
                                <div style="color:var(--green-700);font-weight:600;">+62 822-8402-6557</div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div style="display:flex;align-items:center;gap:14px;">
                            <div style="width:40px;height:40px;border-radius:10px;background:#e0f2fe;display:flex;align-items:center;justify-content:center;font-size:20px;">
                                ✉️
                            </div>
                            <div>
                                <div style="font-weight:700;color:var(--text-primary);">Email Dukungan</div>
                                <div style="color:#0284c7;font-weight:600;">admin@tanamanobat.id</div>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div style="display:flex;align-items:center;gap:14px;">
                            <div style="width:40px;height:40px;border-radius:10px;background:#ede9fe;display:flex;align-items:center;justify-content:center;font-size:20px;">
                                📍
                            </div>
                            <div>
                                <div style="font-weight:700;color:var(--text-primary);">Kantor Pusat</div>
                                <div style="color:#6d28d9;line-height:1.4;">Jl. Nusantara Herbal No. 45, Menteng, Jakarta, Indonesia</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Disclaimer Card --}}
                <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:18px;padding:24px;">
                    <p style="font-family:'Inter',sans-serif;font-size:12px;color:#92400e;line-height:1.6;">
                        ⚠️ <strong>Penting:</strong> Admin kami hanya memproses pesan yang berkaitan dengan operasional website, laporan kesalahan konten, aduan akun, atau proposal kerja sama. Kami **tidak melayani tanya jawab medis** atau peresepan obat herbal secara personal.
                    </p>
                </div>
            </div>

            {{-- Right column: Contact Form --}}
            <div class="card" style="padding:40px;">
                <h3 style="font-size:20px;font-family:'Playfair Display',serif;margin-bottom:6px;">Formulir Kontak</h3>
                <p style="font-family:'Inter',sans-serif;font-size:14px;color:var(--text-muted);margin-bottom:28px;">
                    Tuliskan pesan Anda dan kami akan merespons dalam waktu 1x24 jam kerja.
                </p>

                @if(session('success'))
                <div class="alert alert-success" style="background:#dcfce7;border:1.5px solid var(--green-300);color:var(--green-800);border-radius:12px;padding:14px;font-family:'Inter',sans-serif;font-size:13px;margin-bottom:20px;">
                    ✅ {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-error" style="background:#fef2f2;border:1.5px solid #fecaca;color:#991b1b;border-radius:12px;padding:14px;font-family:'Inter',sans-serif;font-size:13px;margin-bottom:20px;">
                    ❌ {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('contact.submit') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Nama lengkap Anda" required value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Anda <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="nama@email.com" required value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subjek / Topik <span class="required">*</span></label>
                        <input type="text" name="subject" class="form-control" placeholder="Contoh: Kesalahan info / Kerja sama" required value="{{ old('subject') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pesan Anda <span class="required">*</span></label>
                        <textarea name="message" class="form-control" placeholder="Tuliskan pesan Anda secara jelas..." required style="min-height:140px;">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px;margin-top:8px;">
                        🚀 Kirim Pesan
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
