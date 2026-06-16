@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card__icon" style="background:var(--green-100);">🌿</div>
        <div class="stat-card__number" data-count="{{ $stats['total_plants'] }}">{{ $stats['total_plants'] }}</div>
        <div class="stat-card__label">Total Tanaman</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background:#dcfce7;">✓</div>
        <div class="stat-card__number" data-count="{{ $stats['published_plants'] }}">{{ $stats['published_plants'] }}</div>
        <div class="stat-card__label">Tanaman Terbit</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background:#fef9c3;">✎</div>
        <div class="stat-card__number" data-count="{{ $stats['draft_plants'] }}">{{ $stats['draft_plants'] }}</div>
        <div class="stat-card__label">Draft</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background:#ede9fe;">👥</div>
        <div class="stat-card__number" data-count="{{ $stats['total_users'] }}">{{ $stats['total_users'] }}</div>
        <div class="stat-card__label">Total Pengguna</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background:#dbeafe;">🏷️</div>
        <div class="stat-card__number" data-count="{{ $stats['total_categories'] }}">{{ $stats['total_categories'] }}</div>
        <div class="stat-card__label">Kategori</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background:#fee2e2;">❤️</div>
        <div class="stat-card__number" data-count="{{ $stats['total_favorites'] }}">{{ $stats['total_favorites'] }}</div>
        <div class="stat-card__label">Total Favorit</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:24px;">
    {{-- Recent Plants --}}
    <div class="table-wrap">
        <div class="table-header">
            <h3 class="table-title">🌿 Tanaman Terbaru</h3>
            <a href="{{ route('editor.plants.create') }}" class="btn btn-primary btn-sm">➕ Tambah</a>
        </div>
        <table>
            <thead><tr><th>Tanaman</th><th>Kategori</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($recentPlants as $plant)
                <tr>
                    <td>
                        <div>
                            <div style="font-weight:600;font-family:'Inter',sans-serif;font-size:14px;">{{ $plant->local_name }}</div>
                            <div style="font-size:11px;color:var(--text-muted);font-style:italic;">{{ $plant->latin_name }}</div>
                        </div>
                    </td>
                    <td>{{ $plant->category->icon }} {{ $plant->category->name }}</td>
                    <td>
                        <span class="badge {{ $plant->status === 'published' ? 'badge-published' : 'badge-draft' }}">
                            {{ $plant->status === 'published' ? '✓' : '✎' }} {{ ucfirst($plant->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:12px 20px;">
            <a href="{{ route('editor.plants.index') }}" style="font-family:'Inter',sans-serif;font-size:13px;color:var(--green-600);font-weight:600;">Lihat semua tanaman →</a>
        </div>
    </div>

    {{-- Popular Plants --}}
    <div class="table-wrap">
        <div class="table-header">
            <h3 class="table-title">🔥 Tanaman Terpopuler</h3>
        </div>
        <table>
            <thead><tr><th>Tanaman</th><th>Views</th><th>Favorit</th></tr></thead>
            <tbody>
                @foreach($popularPlants as $plant)
                <tr>
                    <td>
                        <div style="font-weight:600;font-family:'Inter',sans-serif;font-size:14px;">{{ $plant->local_name }}</div>
                    </td>
                    <td>
                        <span style="font-family:'Inter',sans-serif;font-size:13px;font-weight:600;color:var(--green-700);">{{ number_format($plant->views) }}</span>
                    </td>
                    <td>
                        <span style="font-family:'Inter',sans-serif;font-size:13px;">❤️ {{ $plant->favorites->count() }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Quick Actions --}}
<div style="margin-top:24px;display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:16px;">
    <a href="{{ route('editor.plants.create') }}" class="card" style="padding:20px;text-align:center;text-decoration:none;color:inherit;">
        <div style="font-size:32px;margin-bottom:8px;">➕</div>
        <div style="font-family:'Inter',sans-serif;font-size:14px;font-weight:600;">Tambah Tanaman</div>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="card" style="padding:20px;text-align:center;text-decoration:none;color:inherit;">
        <div style="font-size:32px;margin-bottom:8px;">🏷️</div>
        <div style="font-family:'Inter',sans-serif;font-size:14px;font-weight:600;">Kelola Kategori</div>
    </a>
    <a href="{{ route('admin.users.index') }}" class="card" style="padding:20px;text-align:center;text-decoration:none;color:inherit;">
        <div style="font-size:32px;margin-bottom:8px;">👥</div>
        <div style="font-family:'Inter',sans-serif;font-size:14px;font-weight:600;">Kelola Pengguna</div>
    </a>
    <a href="{{ route('home') }}" class="card" style="padding:20px;text-align:center;text-decoration:none;color:inherit;" target="_blank">
        <div style="font-size:32px;margin-bottom:8px;">🌐</div>
        <div style="font-family:'Inter',sans-serif;font-size:14px;font-weight:600;">Lihat Website</div>
    </a>
</div>

@endsection
