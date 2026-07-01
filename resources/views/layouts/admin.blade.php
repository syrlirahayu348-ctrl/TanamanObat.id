<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — TanamanObat.id Admin</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌿</text></svg>">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body style="background:#f8fafc;">

{{-- Toast messages --}}
@if(session('success'))
    <span data-toast="{{ session('success') }}" data-toast-type="success" class="hidden"></span>
@endif
@if(session('error'))
    <span data-toast="{{ session('error') }}" data-toast-type="error" class="hidden"></span>
@endif

<div class="admin-layout">
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar__logo">
            <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;color:white;">
                <div class="sidebar__logo-icon">🌿</div>
                <span style="font-size:16px;">TanamanObat.id</span>
            </a>
        </div>

        <nav class="sidebar__nav">
            @if(auth()->user()->isAdmin())
                <div class="sidebar__section">Dashboard</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="sidebar__link-icon">📊</span> Dashboard
                </a>
            @endif

            <div class="sidebar__section">Konten</div>
            <a href="{{ route('editor.plants.index') }}" class="sidebar__link {{ request()->routeIs('editor.plants.*') ? 'active' : '' }}">
                <span class="sidebar__link-icon">🌿</span> Kelola Tanaman
            </a>
            <a href="{{ route('editor.plants.create') }}" class="sidebar__link {{ request()->routeIs('editor.plants.create') ? 'active' : '' }}">
                <span class="sidebar__link-icon">➕</span> Tambah Tanaman
            </a>

            @if(auth()->user()->isAdmin())
                <div class="sidebar__section">Admin</div>
                <a href="{{ route('admin.categories.index') }}" class="sidebar__link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <span class="sidebar__link-icon">🏷️</span> Kategori
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar__link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="sidebar__link-icon">👥</span> Pengguna
                </a>
                @php $msgCount = \App\Models\ContactMessage::count(); @endphp
                <a href="{{ route('admin.contact.index') }}" class="sidebar__link {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}" style="display:flex;align-items:center;justify-content:space-between;">
                    <span><span class="sidebar__link-icon">📬</span> Pesan Masuk</span>
                    @if($msgCount > 0)
                        <span style="background:#ef4444;color:white;font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;min-width:18px;text-align:center;">{{ $msgCount }}</span>
                    @endif
                </a>
            @endif

            <div class="sidebar__section">Lainnya</div>
            <a href="{{ route('home') }}" class="sidebar__link">
                <span class="sidebar__link-icon">🌐</span> Lihat Website
            </a>
            <a href="{{ route('profile.edit') }}" class="sidebar__link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span class="sidebar__link-icon">👤</span> Profil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar__link" style="width:100%;text-align:left;background:none;border:none;color:rgba(255,255,255,0.55);cursor:pointer;">
                    <span class="sidebar__link-icon">🚪</span> Keluar
                </button>
            </form>
        </nav>

        {{-- User info at bottom --}}
        <div style="padding:16px 12px;border-top:1px solid rgba(255,255,255,0.06);">
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="user-avatar" style="width:36px;height:36px;font-size:14px;">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="">
                    @else
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    @endif
                </div>
                <div>
                    <div style="font-size:13px;font-weight:600;color:white;font-family:'Inter',sans-serif;">{{ Str::limit(auth()->user()->name, 16) }}</div>
                    <div style="font-size:11px;color:rgba(255,255,255,0.4);font-family:'Inter',sans-serif;">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="admin-main">
        {{-- Topbar --}}
        <div class="admin-topbar">
            <div style="display:flex;align-items:center;gap:16px;">
                <button id="sidebarToggle" style="background:none;border:none;cursor:pointer;font-size:20px;color:var(--text-secondary);display:none;" class="hidden">☰</button>
                <div>
                    <h1 style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:var(--text-primary);">@yield('page_title', 'Dashboard')</h1>
                    @hasSection('breadcrumb')
                        <div style="font-family:'Inter',sans-serif;font-size:12px;color:var(--text-muted);margin-top:2px;">@yield('breadcrumb')</div>
                    @endif
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                <a href="{{ route('home') }}" class="btn btn-outline btn-sm">🌐 Lihat Website</a>
            </div>
        </div>

        {{-- Content --}}
        <div class="admin-content">
            @yield('content')
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
