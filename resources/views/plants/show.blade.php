@extends('layouts.app')

@section('title', $plant->local_name . ' — ' . $plant->latin_name)
@section('meta_desc', Str::limit(strip_tags($plant->description), 160))

@section('content')

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span>›</span>
            <a href="{{ route('plants.index') }}">Ensiklopedia</a>
            <span>›</span>
            <a href="{{ route('categories.show', $plant->category->slug) }}">{{ $plant->category->name }}</a>
            <span>›</span>
            <span style="color:rgba(255,255,255,0.8);">{{ $plant->local_name }}</span>
        </div>
        <h1 class="page-header__title">{{ $plant->local_name }}</h1>
        <p class="page-header__subtitle" style="font-style:italic;">{{ $plant->latin_name }}</p>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="plant-detail-grid" style="display:grid;grid-template-columns:1fr 380px;gap:48px;align-items:start;">

            {{-- ── LEFT: Main Content ─────────────────────────────────── --}}
            <div>
                {{-- Main Image --}}
                <div style="position:relative;margin-bottom:32px;">
                    <img
                        src="{{ $plant->image_url }}"
                        alt="{{ $plant->local_name }}"
                        class="plant-detail__image"
                        onerror="this.src='https://placehold.co/800x450/dcfce7/166534?text=🌿+{{ urlencode($plant->local_name) }}'"
                    >

                    {{-- Favorite button --}}
                    <div style="position:absolute;top:16px;right:16px;">
                        <button
                            class="btn-favorite {{ $isFavorited ? 'active' : '' }}"
                            data-favorite
                            data-plant-id="{{ $plant->id }}"
                            {{ auth()->check() ? 'data-auth=1' : '' }}
                            title="{{ $isFavorited ? 'Hapus dari favorit' : 'Simpan ke favorit' }}"
                        >
                            <span class="fav-icon">{{ $isFavorited ? '❤️' : '🤍' }}</span>
                        </button>
                    </div>
                </div>

                {{-- Plant Meta --}}
                <div class="plant-meta">
                    <div class="plant-meta__item">
                        <div class="plant-meta__label">📍 Kategori</div>
                        <div class="plant-meta__value">
                            <a href="{{ route('categories.show', $plant->category->slug) }}" style="color:var(--green-600);">
                                {{ $plant->category->icon }} {{ $plant->category->name }}
                            </a>
                        </div>
                    </div>
                    <div class="plant-meta__item">
                        <div class="plant-meta__label">🔬 Nama Latin</div>
                        <div class="plant-meta__value" style="font-style:italic;">{{ $plant->latin_name }}</div>
                    </div>
                    @if($plant->origin)
                    <div class="plant-meta__item">
                        <div class="plant-meta__label">🗺️ Asal Daerah</div>
                        <div class="plant-meta__value">{{ $plant->origin }}</div>
                    </div>
                    @endif
                    @if($plant->harvest_season)
                    <div class="plant-meta__item">
                        <div class="plant-meta__label">🌤️ Musim Panen</div>
                        <div class="plant-meta__value">{{ $plant->harvest_season }}</div>
                    </div>
                    @endif
                </div>

                {{-- Description --}}
                <div class="info-section">
                    <h2 class="info-section__title">
                        <div class="icon">📖</div>
                        Deskripsi Tanaman
                    </h2>
                    <p class="info-section__content">{{ $plant->description }}</p>
                </div>

                {{-- Benefits --}}
                <div class="info-section" style="background:var(--green-50);padding:24px;border-radius:var(--radius-lg);border:1px solid var(--green-100);">
                    <h2 class="info-section__title">
                        <div class="icon" style="background:var(--green-200);">✨</div>
                        Manfaat & Khasiat
                    </h2>
                    <div class="info-section__content">
                        @foreach(explode(',', $plant->benefits) as $benefit)
                            <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:8px;">
                                <span style="color:var(--green-500);font-size:16px;flex-shrink:0;margin-top:2px;">✓</span>
                                <span>{{ trim($benefit) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Usage --}}
                <div class="info-section">
                    <h2 class="info-section__title">
                        <div class="icon">🫖</div>
                        Cara Penggunaan
                    </h2>
                    <div class="info-section__content">{{ $plant->usage }}</div>
                </div>

                {{-- Side Effects --}}
                @if($plant->side_effects)
                <div class="info-section">
                    <h2 class="info-section__title">
                        <div class="icon" style="background:#fee2e2;">⚠️</div>
                        <span style="color:#dc2626;">Efek Samping & Peringatan</span>
                    </h2>
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:var(--radius-md);padding:20px;">
                        <p class="info-section__content">{{ $plant->side_effects }}</p>
                    </div>
                </div>
                @endif

                {{-- Disclaimer --}}
                <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:var(--radius-md);padding:16px;margin-top:24px;">
                    <p style="font-family:'Inter',sans-serif;font-size:13px;color:#92400e;">
                        ⚠️ <strong>Disclaimer:</strong> Informasi ini hanya untuk referensi pendidikan. Konsultasikan dengan tenaga medis profesional sebelum menggunakan tanaman obat sebagai pengobatan.
                    </p>
                </div>
            </div>

            {{-- ── RIGHT: Sidebar ─────────────────────────────────────── --}}
            <div style="position:sticky;top:90px;">

                {{-- Quick Info Card --}}
                <div class="card-glass" style="padding:24px;margin-bottom:24px;">
                    <h3 style="font-size:17px;margin-bottom:16px;font-family:'Playfair Display',serif;">Informasi Cepat</h3>

                    <div style="display:flex;flex-direction:column;gap:12px;font-family:'Inter',sans-serif;font-size:14px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid var(--green-100);">
                            <span style="color:var(--text-muted);">👁 Dilihat</span>
                            <strong>{{ number_format($plant->views) }}x</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid var(--green-100);">
                            <span style="color:var(--text-muted);">❤️ Difavoritkan</span>
                            <strong class="fav-count">{{ $plant->favorites->count() }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid var(--green-100);">
                            <span style="color:var(--text-muted);">🏷️ Kategori</span>
                            <strong>{{ $plant->category->name }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid var(--green-100);">
                            <span style="color:var(--text-muted);">📅 Ditambahkan</span>
                            <strong>{{ $plant->created_at->format('d M Y') }}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="color:var(--text-muted);">✏️ Oleh</span>
                            <strong>{{ $plant->user->name }}</strong>
                        </div>
                    </div>

                    <div style="margin-top:20px;">
                        <button
                            class="btn btn-{{ $isFavorited ? 'danger' : 'primary' }} w-full"
                            data-favorite
                            data-plant-id="{{ $plant->id }}"
                            {{ auth()->check() ? 'data-auth=1' : '' }}
                            style="width:100%;justify-content:center;"
                        >
                            <span class="fav-icon">{{ $isFavorited ? '❤️ Hapus Favorit' : '🤍 Simpan Favorit' }}</span>
                        </button>
                    </div>

                    @if(auth()->user()?->canManagePlants())
                    <div style="margin-top:10px;display:flex;gap:8px;">
                        <a href="{{ route('editor.plants.edit', $plant) }}" class="btn btn-outline btn-sm" style="flex:1;justify-content:center;">✏️ Edit</a>
                        @if(auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('admin.plants.destroy', $plant) }}" style="flex:1;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center;" data-confirm="Hapus tanaman ini? Tindakan tidak bisa dibatalkan.">🗑️ Hapus</button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Related Plants --}}
                @if($relatedPlants->count() > 0)
                <div class="card" style="overflow:hidden;">
                    <div style="padding:16px 20px;border-bottom:1px solid var(--green-100);background:var(--green-50);">
                        <h3 style="font-size:16px;font-family:'Playfair Display',serif;">Tanaman Serupa</h3>
                    </div>
                    @foreach($relatedPlants as $related)
                    <a href="{{ route('plants.show', $related->slug) }}" style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid #f8fafc;transition:background 0.2s;" onmouseenter="this.style.background='var(--green-50)'" onmouseleave="this.style.background=''">
                        <div style="width:44px;height:44px;border-radius:10px;background:var(--green-100);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">🌿</div>
                        <div>
                            <div style="font-family:'Inter',sans-serif;font-size:14px;font-weight:600;color:var(--text-primary);">{{ $related->local_name }}</div>
                            <div style="font-size:12px;color:var(--text-muted);font-style:italic;">{{ $related->latin_name }}</div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    @media (max-width: 768px) {
        .plant-detail-grid { grid-template-columns: 1fr !important; }
    }
</style>
@endpush
