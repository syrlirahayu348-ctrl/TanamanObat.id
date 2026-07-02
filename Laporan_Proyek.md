# LAPORAN PROYEK
## Keamanan Basis Data dan Manajemen Identitas
### WEBSITE TANAMANOBAT.ID

---

**Disusun Oleh:**
Syrlirahayu348

**Program Studi:** Teknik Informatika / Sistem Informasi
**Tahun:** 2026

---
---

## LEMBAR PENGESAHAN

**Judul Proyek:** Implementasi Keamanan Basis Data dan Manajemen Identitas pada Website TanamanObat.id
**Nama Mahasiswa:** Syrlirahayu348
**Program Studi:** Teknik Informatika / Sistem Informasi
**Tahun Akademik:** 2025/2026

Laporan proyek ini telah diperiksa dan disahkan sebagai persyaratan tugas mata kuliah Keamanan Sistem Informasi.

&nbsp;

Dosen Pembimbing,

&nbsp;

&nbsp;

(__________________________)
NIP. ___________________________

---
---

## KATA PENGANTAR

Puji syukur penulis panjatkan ke hadirat Tuhan Yang Maha Esa atas limpahan rahmat-Nya sehingga laporan proyek **"Implementasi Keamanan Basis Data dan Manajemen Identitas pada Website TanamanObat.id"** dapat diselesaikan dengan baik. 

Website TanamanObat.id adalah ensiklopedia digital tanaman obat Indonesia berbasis Laravel 10. Laporan ini berfokus pada desain sistem keamanan yang diterapkan, meliputi autentikasi berlapis (OTP email, Google reCAPTCHA, Google OAuth), pembatasan login (Rate Limiting), kontrol akses berbasis peran (RBAC), perlindungan SQL Injection via Eloquent ORM, serta hasil pengujian keamanan sistem.

Kritik dan saran yang membangun sangat penulis harapkan demi penyempurnaan laporan ini di masa mendatang.

&nbsp;

2026

**Penulis**

---
---

## DAFTAR ISI

1. Cover & Lembar Pengesahan
2. Kata Pengantar & Daftar Isi
3. **BAB I Pendahuluan**
   - 1.1 Latar Belakang & Kebutuhan Keamanan
   - 1.2 Rumusan Masalah
   - 1.3 Tujuan Proyek
4. **BAB II Analisis, Perancangan dan Implementasi**
   - 2.1 Desain Keamanan Sistem (Autentikasi & Otorisasi)
   - 2.2 Secure Query & Proteksi SQL Injection
   - 2.3 Hak Akses Database & Skema RBAC
   - 2.4 Panduan Screenshot Tampilan Sistem
5. **BAB III Pengujian dan Analisis**
   - 3.1 Skenario Pengujian Fungsional Keamanan
   - 3.2 Analisis Kerentanan & Mitigasi (OWASP Top 10)
6. **BAB IV Penutup**
   - 4.1 Kesimpulan
   - 4.2 Saran Pengembangan
7. Daftar Pustaka
8. Lampiran (Struktur File & Middleware Stack)

---
---

# BAB I
# PENDAHULUAN

---

### 1.1 Latar Belakang

Indonesia memiliki keanekaragaman hayati tanaman obat yang sangat kaya. Website **TanamanObat.id** dirancang untuk mendokumentasikan dan menyajikan informasi tanaman obat secara terstruktur bagi masyarakat umum, editor, dan administrator. 

Keamanan basis data dan manajemen identitas sangat krusial karena platform ini membedakan hak akses pengguna dan mengelola data sensitif (seperti kredensial akun dan ulasan). Sistem ini rentan terhadap ancaman seperti SQL Injection, Brute Force, CSRF, dan akses tidak sah. Oleh karena itu, diterapkanlah manajemen identitas berbasis peran (Role-Based Access Control / RBAC) dan sistem autentikasi berlapis.

#### Tingkatan Peran Pengguna (RBAC Overview):
* **Guest**: Pengunjung umum yang belum login. Hanya bisa melihat Landing Page, FAQ, dan mengirim pesan kontak.
* **User**: Pengguna terdaftar. Bisa melihat katalog tanaman, memberikan ulasan/rating, menyimpan favorit, dan mengedit profil.
* **Editor**: Pengguna editorial. Memiliki akses tambahan untuk menambah dan mengedit data tanaman.
* **Admin**: Administrator utama. Memiliki kontrol penuh atas manajemen pengguna, kategori tanaman, dan pesan masuk.

### 1.2 Rumusan Masalah

1. Bagaimana mengimplementasikan sistem autentikasi yang aman dengan OTP email, Google reCAPTCHA, dan Google OAuth?
2. Bagaimana membatasi hak akses pengguna menggunakan middleware otorisasi berbasis peran (RBAC)?
3. Bagaimana mengamankan interaksi basis data dari SQL Injection pada Laravel?
4. Bagaimana mengamankan formulir dari serangan CSRF dan percobaan login brute-force?

### 1.3 Tujuan

1. Membangun sistem login/registrasi dengan validasi reCAPTCHA, email OTP, dan Google Sign-In.
2. Menerapkan middleware kustom untuk otorisasi hak akses peran (Guest, User, Editor, Admin).
3. Mengamankan query database melalui pemanfaatan Eloquent ORM dan parameter binding.
4. Mencegah ancaman brute-force (rate limiter) dan CSRF (token verification).

---
---

# BAB II
# ANALISIS, PERANCANGAN, DAN IMPLEMENTASI

---

### 2.1 Desain Keamanan Sistem (Autentikasi & Otorisasi)

Sistem keamanan TanamanObat.id dirancang dalam beberapa lapisan keamanan utama:

#### A. Sistem Autentikasi Berlapis
1. **Password Hashing (Bcrypt)**: Password di-hash otomatis menggunakan Bcrypt melalui cast model Laravel (`'password' => 'hashed'`).
2. **Rate Limiting (Anti Brute-Force)**: Membatasi login maksimal 3 kali per IP dalam waktu 2 menit menggunakan `RateLimiter::tooManyAttempts()`.
3. **Verifikasi OTP Email**: Pengguna baru wajib memasukkan kode OTP 6 digit yang dikirim via email SMTP sebelum data akun disimpan ke database.
4. **Google reCAPTCHA v2**: Melindungi endpoint registrasi dari serangan bot otomatis dengan memverifikasi token ke API Google.
5. **Validasi Domain Gmail**: Registrasi hanya diperbolehkan menggunakan domain `@gmail.com` disertai pengecekan rekaman MX record.
6. **Google OAuth 2.0**: Memungkinkan pengguna login instan secara aman menggunakan akun Google terverifikasi melalui Laravel Socialite.
7. **Proteksi CSRF**: Mencegah Cross-Site Request Forgery di setiap form input menggunakan token `@csrf`.

#### B. Sistem Otorisasi (Middleware & Routing)
Otorisasi ditangani oleh middleware kustom untuk memeriksa status aktif akun dan memvalidasi peran pengguna sebelum mengakses rute tertentu:

* **`CheckActive` Middleware**: Memeriksa apakah status akun aktif (`is_active = true`). Jika admin menonaktifkan akun, sesi pengguna langsung dihapus (logout otomatis) pada request berikutnya.
```php
if ($request->user() && !$request->user()->is_active) {
    auth()->logout();
    return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan.');
}
```
* **`CheckRole` Middleware**: Membatasi akses rute berdasarkan peran yang dikirim sebagai parameter rute (contoh: `role:editor,admin`).
```php
if (!in_array($request->user()->role, $roles)) {
    abort(403, 'Anda tidak memiliki akses ke halaman ini.');
}
```

Rute-rute aplikasi dikelompokkan dengan jelas berdasarkan hak akses middleware:
* **Public Route** (Tanpa login): Home, FAQ, Form Kontak.
* **Guest Only Route**: Login, Register, OAuth Callback.
* **User Route** (Middleware `auth` & `active`): Katalog tanaman, detail tanaman, ulasan, favorit, profil.
* **Editor Route** (Middleware `role:editor,admin`): Mengelola tanaman (tambah & edit).
* **Admin Route** (Middleware `role:admin`): Dashboard admin, manajemen user, kategori, dan pesan kontak.

---

### 2.2 Secure Query & Proteksi SQL Injection

Semua interaksi database pada TanamanObat.id menggunakan **Eloquent ORM** yang secara otomatis mengamankan query dari SQL Injection dengan menerapkan Parameter Binding. Nilai input dari pengguna dipisahkan secara struktural dari perintah SQL.

#### Tabel Perbandingan Query:
| Model Query | Contoh Implementasi | Status Keamanan |
| :--- | :--- | :--- |
| **Raw Query (Rentan)** | `DB::select("SELECT * FROM plants WHERE local_name = '" . $request->name . "'")` | ❌ **TIDAK AMAN** (Rentan manipulasi SQL string) |
| **Eloquent ORM (Aman)** | `Plant::where('local_name', $request->name)->get()` | ✅ **AMAN** (Nilai dibinding secara otomatis oleh ORM) |
| **Raw Parameter Binding** | `DB::select("SELECT * FROM plants WHERE local_name = ?", [$request->name])` | ✅ **AMAN** (Parameter di-escape dan dibound secara manual) |

#### Mekanisme Pengamanan Input Tambahan:
* **Validasi Server-Side**: Semua input divalidasi tipe datanya sebelum diproses (contoh: aturan `email`, `unique:users`, `string`, `max:255`).
* **Mass Assignment Protection**: Pembatasan kolom database yang dapat diisi massal menggunakan properti `$fillable` dan menyembunyikan data sensitif menggunakan `$hidden` pada Model Laravel.
* **Session Regeneration**: Meregenerasi ID session saat login dan invalidasi total saat logout untuk mencegah *Session Fixation*.

---

### 2.3 Hak Akses Database & Skema RBAC

Sistem ini menerapkan RBAC minimalis namun efisien menggunakan struktur **Enum-Based Role** di dalam tabel `users` utama, menghindari kompleksitas join tabel relasional tambahan untuk sistem skala menengah.

#### A. Skema Basis Data (Penting Keamanan & RBAC)

##### 1. Tabel `users` (Manajemen Identitas & Autentikasi)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED (PK) | Auto Increment |
| `name` | VARCHAR(255) | Nama pengguna |
| `email` | VARCHAR(255) (UNIQUE) | Kredensial email |
| `email_verified_at`| TIMESTAMP (NULLABLE) | Tanggal verifikasi OTP |
| `password` | VARCHAR(255) | Hash password (Bcrypt) |
| `role` | ENUM('user','editor','admin')| Hak akses peran (default: 'user') |
| `is_active` | BOOLEAN | Status blokir admin (default: true) |
| `google_id` | VARCHAR(255) (NULLABLE) | ID unik OAuth Google |

##### 2. Tabel `plants` (Entitas Utama Data Konten)
| Kolom | Tipe | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED (PK) | Auto Increment |
| `category_id` | FK -> `categories` | Relasi Kategori Tanaman |
| `user_id` | FK -> `users` | Relasi Pembuat Konten (Editor/Admin) |
| `local_name` | VARCHAR(255) | Nama tanaman lokal |
| `latin_name` | VARCHAR(255) | Nama latin tanaman |
| `status` | ENUM('published','draft') | Status publikasi |

#### B. Matriks Hak Akses (Access Control Matrix)

| Fitur / Aksi | Guest | User | Editor | Admin |
| :--- | :---: | :---: | :---: | :---: |
| Melihat Landing Page, FAQ, Kontak | ✅ | ✅ | ✅ | ✅ |
| Kirim Pesan Kontak | ✅ | ✅ | ✅ | ✅ |
| Login & Register Akun | ✅ | ❌ | ❌ | ❌ |
| Akses Katalog & Detail Tanaman | ❌ | ✅ | ✅ | ✅ |
| Beri Komentar/Ulasan & Simpan Favorit | ❌ | ✅ | ✅ | ✅ |
| Kelola Profil Sendiri | ❌ | ✅ | ✅ | ✅ |
| Tambah & Edit Data Tanaman | ❌ | ❌ | ✅ | ✅ |
| Manajemen Kategori & Hapus Tanaman | ❌ | ❌ | ❌ | ✅ |
| Dashboard Admin, Manajemen User & Pesan | ❌ | ❌ | ❌ | ✅ |

---

### 2.4 Panduan Screenshot Tampilan Sistem

Dalam implementasinya, antarmuka sistem dilengkapi elemen pendukung visual keamanan berikut:
* **Halaman Login**: Menampilkan form dengan tombol OAuth "Masuk dengan Google" serta pesan sisa percobaan login apabila user salah memasukkan kredensial (Rate Limiter).
* **Halaman Register**: Dilengkapi form pilihan peran (User/Editor) dan widget **Google reCAPTCHA v2** di bagian bawah form.
* **Halaman Verifikasi OTP**: Form input 6-digit angka OTP yang dikirim via email SMTP.
* **Dashboard Admin (Manajemen User)**: Halaman khusus Admin untuk mengubah role pengguna, mengaktifkan/menonaktifkan akun (`is_active` toggle), dan menghapus pengguna.
* **Pengujian Akses Tidak Sah**: Tampilan halaman **403 Forbidden** ketika User biasa mencoba mengakses `/admin/dashboard` atau rute editor tanpa wewenang.

---
---

# BAB III
# PENGUJIAN DAN ANALISIS

---

### 3.1 Skenario Pengujian Fungsional Keamanan (Black-Box)

Berikut adalah rangkuman skenario pengujian aspek keamanan utama yang telah diuji dan divalidasi:

#### A. Tabel Pengujian Autentikasi & Proteksi Akun
| No | Skenario | Hasil yang Diharapkan | Hasil Aktual | Status |
| :--- | :--- | :--- | :--- | :--- |
| 1 | Login dengan kredensial benar | Berhasil masuk ke sistem, redirect ke beranda. | Berhasil masuk, menampilkan pesan sukses. | ✅ Pass |
| 2 | Login salah password berulang | Setelah 3x gagal, akses diblokir selama 2 menit. | Terblokir, muncul notifikasi sisa waktu. | ✅ Pass |
| 3 | Login dengan akun nonaktif | Login ditolak karena `is_active` bernilai `false`. | Menolak masuk, menampilkan pesan nonaktif. | ✅ Pass |
| 4 | Registrasi non-gmail | Pendaftaran ditolak oleh sistem validasi email. | Ditolak, muncul pesan error domain Gmail. | ✅ Pass |
| 5 | Registrasi tanpa reCAPTCHA | Proses ditolak karena verifikasi CAPTCHA gagal. | Form ditolak, kembali ke halaman register. | ✅ Pass |
| 6 | Verifikasi OTP benar | Akun aktif dan login otomatis setelah OTP cocok. | Akun sukses terbuat dan masuk ke sistem. | ✅ Pass |

#### B. Tabel Pengujian Otorisasi (RBAC) & Proteksi Serangan
| No | Skenario | Aktor | URL Akses | Hasil Diharapkan | Hasil Aktual | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 7 | Akses katalog tanpa login | Guest | `/plants` | Redirect ke `/login` | Diarahkan ke login | ✅ Pass |
| 8 | Akses dashboard admin | User | `/admin/dashboard`| Error 403 Forbidden | Muncul halaman 403 | ✅ Pass |
| 9 | Akses halaman editor | Editor | `/editor/plants` | Halaman kelola tampil | Halaman berhasil dimuat| ✅ Pass |
| 10| SQL Injection form login | Penyerang| `' OR 1=1 --` | Login gagal | Gagal (query aman) | ✅ Pass |
| 11| SQL Injection URL slug | Penyerang| `/plants/' OR 1=1`| Halaman 404 / Error | Error 404 Not Found | ✅ Pass |
| 12| POST data tanpa CSRF token| Penyerang| Hapus `_token` | Error 419 Expired | Muncul halaman 419 | ✅ Pass |

---

### 3.2 Analisis Kerentanan dan Mitigasi (Ringkasan)

Sistem ini berhasil memitigasi risiko keamanan utama berdasarkan standar OWASP Top 10:
1. **SQL Injection (Tinggi)**: Dimitigasi dengan penerapan parameter binding otomatis pada Eloquent ORM.
2. **Brute Force Login (Tinggi)**: Dimitigasi dengan pembatasan percobaan login (3 kali / 2 menit).
3. **Cross-Site Request Forgery (Sedang)**: Dimitigasi dengan verifikasi wajib CSRF Token di setiap request POST/PUT/DELETE.
4. **Broken Access Control (Tinggi)**: Ditegakkan menggunakan middleware `CheckRole` dan `CheckActive` untuk menyaring hak akses.
5. **Credential Leakage (Tinggi)**: Diantisipasi dengan enkripsi password menggunakan algoritma Bcrypt.

---
---

# BAB IV
# PENUTUP

---

### 4.1 Kesimpulan

Berdasarkan perancangan dan pengujian sistem keamanan TanamanObat.id, dapat disimpulkan bahwa:
1. **Sistem Autentikasi Aman**: Kombinasi Gmail validation, OTP, reCAPTCHA, Google OAuth, dan rate limiting berhasil melindungi sistem dari bot, spam registrasi, dan brute-force.
2. **RBAC Berjalan Efektif**: Pembagian peran Guest, User, Editor, dan Admin berhasil disaring dengan middleware otorisasi. Status penonaktifan pengguna oleh admin berdampak instan.
3. **Proteksi Database Terjamin**: Penggunaan Eloquent ORM secara konsisten membebaskan aplikasi dari celah kerentanan SQL Injection.

### 4.2 Saran

Untuk meningkatkan keamanan di masa mendatang, disarankan untuk:
1. Mengaktifkan protokol **HTTPS/SSL** untuk enkripsi transmisi data end-to-end.
2. Menerapkan fitur **Two-Factor Authentication (2FA)** opsional menggunakan Google Authenticator.
3. Membuat **Audit Log** otomatis untuk mencatat aktivitas sensitif admin dan editor.

---
---

# DAFTAR PUSTAKA

1. Laravel Documentation. (2024). *Authentication & Authorization in Laravel 10*. https://laravel.com/docs/10.x/
2. OWASP Foundation. (2021). *OWASP Top 10 Vulnerabilities*. https://owasp.org/www-project-top-ten/
3. Google Identity. (2024). *OAuth 2.0 Integration & Google reCAPTCHA v2 Developer Guides*.

---
---

# LAMPIRAN

### Lampiran A: Struktur Folder Utama Keamanan
```
TanamanObat.id/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/ (Dashboard, User, Category, Contact)
│   │   │   ├── Editor/ (Plant Management)
│   │   │   └── AuthController.php (Login, Register, OTP, OAuth)
│   │   └── Middleware/
│   │       ├── CheckActive.php (Verifikasi status akun aktif)
│   │       └── CheckRole.php (Verifikasi RBAC peran)
│   ├── Models/
│   │   ├── User.php (Model pengguna & flag RBAC)
│   │   └── Plant.php (Model tanaman & scope search aman)
│   └── Mail/
│       └── SendOtpMail.php (Pengiriman OTP)
├── database/
│   └── migrations/ (Migrasi users, role enum, & Google ID)
└── routes/
    └── web.php (Pengelompokan rute + middleware)
```

### Lampiran B: Ringkasan Middleware Stack (app/Http/Kernel.php)
```php
protected $middlewareAliases = [
    'auth'   => \App\Http\Middleware\Authenticate::class,
    'guest'  => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'active' => \App\Http\Middleware\CheckActive::class,
    'role'   => \App\Http\Middleware\CheckRole::class,
];
```

*— Akhir Laporan —*
