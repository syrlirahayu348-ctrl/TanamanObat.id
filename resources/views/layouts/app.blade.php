<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TanamanObat.id') — Ensiklopedia Tanaman Obat Indonesia</title>
    <meta name="description" content="@yield('meta_desc', 'TanamanObat.id adalah ensiklopedia digital tanaman obat tradisional Indonesia. Temukan informasi lengkap tentang tanaman obat nusantara.')">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌿</text></svg>">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

{{-- Toast flash messages --}}
@if(session('success'))
    <span data-toast="{{ session('success') }}" data-toast-type="success" class="hidden"></span>
@endif
@if(session('error'))
    <span data-toast="{{ session('error') }}" data-toast-type="error" class="hidden"></span>
@endif

{{-- Navbar --}}
<nav class="navbar" id="navbar">
    <div class="container">
        <div class="navbar__inner">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="navbar__logo">
                <div class="navbar__logo-icon">🌿</div>
                TanamanObat<span style="color:var(--green-500)">.id</span>
            </a>

            {{-- Navigation Links --}}
            <ul class="navbar__nav">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('plants.index') }}" class="{{ request()->routeIs('plants.*') ? 'active' : '' }}">Ensiklopedia</a></li>
                <li><a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">Kategori</a></li>
                @auth
                    <li><a href="{{ route('favorites.index') }}" class="{{ request()->routeIs('favorites.*') ? 'active' : '' }}">❤️ Favorit</a></li>
                @endauth
            </ul>

            {{-- Search --}}
            <form action="{{ route('search') }}" method="GET" class="navbar__search">
                <span>🔍</span>
                <input type="text" name="q" placeholder="Cari tanaman..." value="{{ request('q') }}" autocomplete="off">
            </form>

            {{-- Auth Actions --}}
            <div class="navbar__actions">
                @auth
                    <div class="user-menu">
                        <button class="user-menu__trigger">
                            <div class="user-avatar">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            {{ Str::limit(auth()->user()->name, 12) }}
                            <span>▾</span>
                        </button>
                        <div class="user-menu__dropdown">
                            <div class="user-menu__header">
                                <div class="user-menu__name">{{ auth()->user()->name }}</div>
                                <div class="user-menu__email">{{ auth()->user()->email }}</div>
                                <div style="margin-top:6px">{!! auth()->user()->role_badge !!}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="user-menu__item">👤 Profil Saya</a>
                            <a href="{{ route('favorites.index') }}" class="user-menu__item">❤️ Favorit Saya</a>
                            @if(auth()->user()->canManagePlants())
                                <div class="user-menu__divider"></div>
                                <a href="{{ route('editor.plants.index') }}" class="user-menu__item">✏️ Kelola Tanaman</a>
                            @endif
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="user-menu__item">🔑 Dashboard Admin</a>
                            @endif
                            <div class="user-menu__divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="user-menu__item danger" style="border:none;background:none;width:100%;text-align:left;font-size:14px;font-family:inherit;cursor:pointer;">
                                    🚪 Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @endauth
            </div>

            {{-- Mobile toggle --}}
            <button class="navbar__mobile-toggle" id="mobileToggle" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>

    {{-- Mobile Navigation Overlay --}}
    <div class="mobile-nav">
        <a href="{{ route('home') }}" class="mobile-nav__link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
        <a href="{{ route('plants.index') }}" class="mobile-nav__link {{ request()->routeIs('plants.*') ? 'active' : '' }}">Ensiklopedia</a>
        <a href="{{ route('categories.index') }}" class="mobile-nav__link {{ request()->routeIs('categories.*') ? 'active' : '' }}">Kategori</a>
        @auth
            <a href="{{ route('favorites.index') }}" class="mobile-nav__link {{ request()->routeIs('favorites.*') ? 'active' : '' }}">❤️ Favorit Saya</a>
            <a href="{{ route('profile.edit') }}" class="mobile-nav__link {{ request()->routeIs('profile.*') ? 'active' : '' }}">👤 Profil Saya</a>
        @endauth
        <a href="{{ route('faq') }}" class="mobile-nav__link {{ request()->routeIs('faq') ? 'active' : '' }}">FAQ</a>
        <a href="{{ route('contact') }}" class="mobile-nav__link {{ request()->routeIs('contact') ? 'active' : '' }}">Hubungi Kami</a>
        <div style="padding:10px 16px;">
            <form action="{{ route('search') }}" method="GET" class="mobile-nav__search">
                <span>🔍</span>
                <input type="text" name="q" placeholder="Cari tanaman..." value="{{ request('q') }}" autocomplete="off" style="border:none;background:transparent;outline:none;font-family:'Inter',sans-serif;font-size:14px;color:var(--text-primary);width:100%;">
            </form>
        </div>
    </div>
</nav>

{{-- Main content --}}
<main>
    @yield('content')
</main>

{{-- Footer --}}
<footer class="footer">
    <div class="container">
        <div class="footer__grid">
            <div>
                <div class="footer__logo">
                    <div class="navbar__logo-icon">🌿</div>
                    TanamanObat.id
                </div>
                <p class="footer__desc">Ensiklopedia digital tanaman obat tradisional Indonesia. Temukan kekayaan herbal nusantara untuk kesehatan alami Anda.</p>
            </div>
            <div>
                <h4 class="footer__heading">Jelajahi</h4>
                <ul class="footer__links">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('plants.index') }}">Ensiklopedia</a></li>
                    <li><a href="{{ route('categories.index') }}">Kategori</a></li>
                    <li><a href="{{ route('search') }}">Pencarian</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer__heading">Akun</h4>
                <ul class="footer__links">
                    @guest
                        <li><a href="{{ route('login') }}">Masuk</a></li>
                        <li><a href="{{ route('register') }}">Daftar</a></li>
                    @else
                        <li><a href="{{ route('profile.edit') }}">Profil Saya</a></li>
                        <li><a href="{{ route('favorites.index') }}">Favorit Saya</a></li>
                    @endguest
                </ul>
            </div>
            <div>
                <h4 class="footer__heading">Informasi</h4>
                <ul class="footer__links">
                    <li><a href="{{ route('faq') }}">Tanya Jawab (FAQ)</a></li>
                    <li><a href="{{ route('contact') }}">Hubungi Kami</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                </ul>
                <div style="margin-top:16px;padding:12px;background:rgba(34,197,94,0.1);border-radius:8px;border:1px solid rgba(34,197,94,0.2);">
                    <p style="font-size:11px;color:rgba(255,255,255,0.4);line-height:1.5;">⚠️ <strong style="color:var(--green-400)">Disclaimer:</strong> Informasi ini bukan pengganti saran medis profesional.</p>
                </div>
            </div>
        </div>
        <div class="footer__bottom">
            <span>© {{ date('Y') }} TanamanObat.id — Dibuat dengan ❤️ untuk Nusantara</span>
            <span>🌿 Lestarikan Tanaman Obat Indonesia</span>
        </div>
    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
