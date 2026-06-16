@extends('layouts.app')
@section('title', 'Tanya Jawab (FAQ)')
@section('meta_desc', 'Pertanyaan yang sering diajukan mengenai website ensiklopedia TanamanObat.id.')

@section('content')
<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span>›</span>
            <span>FAQ</span>
        </div>
        <h1 class="page-header__title">💬 Tanya Jawab (FAQ)</h1>
        <p class="page-header__subtitle">Temukan jawaban atas pertanyaan umum seputar TanamanObat.id</p>
    </div>
</div>

<div class="section">
    <div class="container" style="max-width:800px;">
        <div style="display:flex;flex-direction:column;gap:18px;">
            
            {{-- FAQ Item 1 --}}
            <div class="card" style="overflow:hidden;">
                <button class="faq-toggle" style="width:100%;padding:20px 24px;text-align:left;display:flex;justify-content:between;align-items:center;font-size:16px;font-weight:700;color:var(--text-primary);font-family:'Inter',sans-serif;background:var(--green-50);border:none;cursor:pointer;">
                    <span>🌿 Apa itu TanamanObat.id?</span>
                    <span class="faq-icon" style="font-size:18px;color:var(--green-600);transition:transform 0.2s;">＋</span>
                </button>
                <div class="faq-content" style="max-height:0;overflow:hidden;transition:all 0.3s ease;background:white;padding:0 24px;">
                    <div style="padding:20px 0;font-family:'Inter',sans-serif;font-size:14px;color:var(--text-secondary);line-height:1.7;">
                        TanamanObat.id adalah sistem informasi berbasis web yang berfungsi sebagai ensiklopedia digital tanaman obat tradisional asli Indonesia. Kami menyajikan informasi lengkap seputar deskripsi tanaman, nama ilmiah/latin, manfaat kesehatan, aturan pakai, hingga efek sampingnya untuk mengedukasi masyarakat secara digital.
                    </div>
                </div>
            </div>

            {{-- FAQ Item 2 --}}
            <div class="card" style="overflow:hidden;">
                <button class="faq-toggle" style="width:100%;padding:20px 24px;text-align:left;display:flex;justify-content:between;align-items:center;font-size:16px;font-weight:700;color:var(--text-primary);font-family:'Inter',sans-serif;background:var(--green-50);border:none;cursor:pointer;">
                    <span>🔒 Apakah informasi medis di website ini 100% akurat?</span>
                    <span class="faq-icon" style="font-size:18px;color:var(--green-600);transition:transform 0.2s;">＋</span>
                </button>
                <div class="faq-content" style="max-height:0;overflow:hidden;transition:all 0.3s ease;background:white;padding:0 24px;">
                    <div style="padding:20px 0;font-family:'Inter',sans-serif;font-size:14px;color:var(--text-secondary);line-height:1.7;">
                        Informasi yang disajikan pada website ini dikumpulkan dari berbagai literatur herbal dan pengobatan tradisional nusantara untuk tujuan edukasi. Informasi ini <strong>bukan pengganti saran medis profesional, diagnosis, atau perawatan dokter</strong>. Selalu konsultasikan kondisi kesehatan Anda ke tenaga medis berlisensi sebelum mengonsumsi ramuan obat herbal.
                    </div>
                </div>
            </div>

            {{-- FAQ Item 3 --}}
            <div class="card" style="overflow:hidden;">
                <button class="faq-toggle" style="width:100%;padding:20px 24px;text-align:left;display:flex;justify-content:between;align-items:center;font-size:16px;font-weight:700;color:var(--text-primary);font-family:'Inter',sans-serif;background:var(--green-50);border:none;cursor:pointer;">
                    <span>👤 Bagaimana cara menyimpan tanaman ke daftar Favorit?</span>
                    <span class="faq-icon" style="font-size:18px;color:var(--green-600);transition:transform 0.2s;">＋</span>
                </button>
                <div class="faq-content" style="max-height:0;overflow:hidden;transition:all 0.3s ease;background:white;padding:0 24px;">
                    <div style="padding:20px 0;font-family:'Inter',sans-serif;font-size:14px;color:var(--text-secondary);line-height:1.7;">
                        Anda harus mendaftar akun dan masuk (login) terlebih dahulu. Setelah masuk, Anda akan melihat tombol berlogo hati (🤍 Simpan Favorit) di halaman detail tanaman obat. Cukup klik tombol tersebut untuk menyimpannya ke daftar menu personal **Favorit Saya**.
                    </div>
                </div>
            </div>

            {{-- FAQ Item 4 --}}
            <div class="card" style="overflow:hidden;">
                <button class="faq-toggle" style="width:100%;padding:20px 24px;text-align:left;display:flex;justify-content:between;align-items:center;font-size:16px;font-weight:700;color:var(--text-primary);font-family:'Inter',sans-serif;background:var(--green-50);border:none;cursor:pointer;">
                    <span>✍️ Siapa saja yang bisa menambahkan data tanaman baru?</span>
                    <span class="faq-icon" style="font-size:18px;color:var(--green-600);transition:transform 0.2s;">＋</span>
                </button>
                <div class="faq-content" style="max-height:0;overflow:hidden;transition:all 0.3s ease;background:white;padding:0 24px;">
                    <div style="padding:20px 0;font-family:'Inter',sans-serif;font-size:14px;color:var(--text-secondary);line-height:1.7;">
                        Data tanaman obat nusantara dikelola secara terstruktur dan aman menggunakan sistem RBAC (Role-Based Access Control). Hanya pengguna terdaftar dengan peran **Editor** atau **Admin** yang memiliki wewenang untuk menambahkan atau mengedit konten ensiklopedia digital ini.
                    </div>
                </div>
            </div>

            {{-- FAQ Item 5 --}}
            <div class="card" style="overflow:hidden;">
                <button class="faq-toggle" style="width:100%;padding:20px 24px;text-align:left;display:flex;justify-content:between;align-items:center;font-size:16px;font-weight:700;color:var(--text-primary);font-family:'Inter',sans-serif;background:var(--green-50);border:none;cursor:pointer;">
                    <span>📬 Bagaimana cara menghubungi administrator jika ada kesalahan informasi?</span>
                    <span class="faq-icon" style="font-size:18px;color:var(--green-600);transition:transform 0.2s;">＋</span>
                </button>
                <div class="faq-content" style="max-height:0;overflow:hidden;transition:all 0.3s ease;background:white;padding:0 24px;">
                    <div style="padding:20px 0;font-family:'Inter',sans-serif;font-size:14px;color:var(--text-secondary);line-height:1.7;">
                        Anda dapat mengirimkan pesan aduan atau saran langsung kepada tim kami melalui formulir kontak yang disediakan di halaman **Kontak Kami**, atau langsung menghubungi saluran bantuan administrasi kami via email atau WhatsApp resmi.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggles = document.querySelectorAll('.faq-toggle');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('.faq-icon');
            
            // Close other open FAQ contents
            document.querySelectorAll('.faq-content').forEach(c => {
                if (c !== content) {
                    c.style.maxHeight = '0';
                    c.previousElementSibling.querySelector('.faq-icon').textContent = '＋';
                    c.previousElementSibling.querySelector('.faq-icon').style.transform = '';
                }
            });

            if (content.style.maxHeight === '0px' || !content.style.maxHeight) {
                content.style.maxHeight = content.scrollHeight + 'px';
                icon.textContent = '✕';
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.style.maxHeight = '0';
                icon.textContent = '＋';
                icon.style.transform = '';
            }
        });
    });
});
</script>
@endpush
@endsection
