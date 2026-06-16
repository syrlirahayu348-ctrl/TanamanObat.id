@extends('layouts.app')
@section('title', 'Tanaman Favorit Saya')

@section('content')
<div class="page-header">
    <div class="container">
        <div class="breadcrumb"><a href="{{ route('home') }}">Beranda</a><span>›</span><span>Favorit Saya</span></div>
        <h1 class="page-header__title">❤️ Tanaman Favorit Saya</h1>
        <p class="page-header__subtitle">Koleksi tanaman obat yang Anda simpan</p>
    </div>
</div>

<div class="section">
    <div class="container">
        @if($favorites->count() > 0)
        <div style="margin-bottom:20px;font-family:'Inter',sans-serif;font-size:14px;color:var(--text-muted);">
            <strong style="color:var(--green-700);">{{ $favorites->total() }}</strong> tanaman tersimpan
        </div>
        <div class="grid-plants">
            @foreach($favorites as $plant)
            <article class="plant-card reveal">
                <a href="{{ route('plants.show', $plant->slug) }}" style="text-decoration:none;color:inherit;">
                    <div class="plant-card__image-wrap">
                        <img src="{{ $plant->image_url }}" alt="{{ $plant->local_name }}" class="plant-card__image" loading="lazy" onerror="this.src='https://placehold.co/400x200/dcfce7/166534?text=🌿'">
                        <div style="position:absolute;top:10px;right:10px;">
                            <span style="background:rgba(239,68,68,0.9);color:white;padding:4px 8px;border-radius:20px;font-size:12px;">❤️ Favorit</span>
                        </div>
                    </div>
                    <div class="plant-card__body">
                        <span class="plant-card__category">{{ $plant->category->name }}</span>
                        <h3 class="plant-card__title">{{ $plant->local_name }}</h3>
                        <p class="plant-card__latin">{{ $plant->latin_name }}</p>
                        <p class="plant-card__excerpt">{{ Str::limit(strip_tags($plant->benefits), 90) }}</p>
                    </div>
                </a>
                <div class="plant-card__footer" style="gap:8px;">
                    <a href="{{ route('plants.show', $plant->slug) }}" class="btn btn-primary btn-sm">Selengkapnya</a>
                    <form method="POST" action="{{ route('favorites.toggle', $plant) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm" style="background:#fef2f2;color:#dc2626;border:1.5px solid #fecaca;">
                            🗑 Hapus
                        </button>
                    </form>
                </div>
            </article>
            @endforeach
        </div>
        <div class="pagination">{{ $favorites->links('vendor.pagination.custom') }}</div>
        @else
        <div class="empty-state">
            <div class="empty-state__icon">💚</div>
            <h3 class="empty-state__title">Belum ada favorit</h3>
            <p class="empty-state__desc">Simpan tanaman obat yang Anda sukai untuk akses cepat</p>
            <a href="{{ route('plants.index') }}" class="btn btn-primary" style="margin-top:24px;">🌿 Jelajahi Tanaman</a>
        </div>
        @endif
    </div>
</div>
@endsection
