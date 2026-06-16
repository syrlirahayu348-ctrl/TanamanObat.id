@extends('layouts.app')

@section('title', 'Ensiklopedia Tanaman Obat')
@section('meta_desc', 'Jelajahi database lengkap tanaman obat tradisional Indonesia. Filter berdasarkan kategori, nama, atau manfaat.')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span>›</span>
            <span>Ensiklopedia</span>
        </div>
        <h1 class="page-header__title">🌿 Ensiklopedia Tanaman Obat</h1>
        <p class="page-header__subtitle">Jelajahi koleksi lengkap tanaman obat tradisional Indonesia</p>
    </div>
</div>

<div class="section">
    <div class="container">

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('plants.index') }}" id="filterForm">
            <div class="filter-bar">
                {{-- Search --}}
                <div style="flex:1;min-width:200px;" class="search-bar">
                    <span>🔍</span>
                    <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Cari nama tanaman, manfaat..." data-live-search>
                </div>

                {{-- Category filter --}}
                <select name="category" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($categoryId ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon }} {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Sort --}}
                <select name="sort" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="latest" {{ ($sort ?? 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="popular" {{ ($sort ?? '') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
                    <option value="az" {{ ($sort ?? '') === 'az' ? 'selected' : '' }}>A–Z</option>
                </select>

                <button type="submit" class="btn btn-primary btn-sm">Cari</button>

                @if($query || $categoryId)
                    <a href="{{ route('plants.index') }}" class="btn btn-outline btn-sm">✕ Reset</a>
                @endif
            </div>
        </form>

        {{-- Results count --}}
        <div style="font-family:'Inter',sans-serif;font-size:14px;color:var(--text-muted);margin-bottom:24px;">
            Menampilkan <strong style="color:var(--green-700);">{{ $plants->total() }}</strong> tanaman obat
            @if($query) untuk "<strong>{{ $query }}</strong>" @endif
        </div>

        {{-- Plants Grid --}}
        @if($plants->count() > 0)
            <div class="grid-plants">
                @foreach($plants as $plant)
                <article class="plant-card reveal">
                    <a href="{{ route('plants.show', $plant->slug) }}" style="text-decoration:none;color:inherit;">
                        <div class="plant-card__image-wrap">
                            <img
                                src="{{ $plant->image_url }}"
                                alt="{{ $plant->local_name }}"
                                class="plant-card__image"
                                loading="lazy"
                                onerror="this.src='https://placehold.co/400x200/dcfce7/166534?text=🌿'"
                            >
                            <div style="position:absolute;top:10px;right:10px;">
                                <span class="badge {{ $plant->status === 'published' ? 'badge-published' : 'badge-draft' }}">
                                    {{ $plant->status === 'published' ? '✓ Terbit' : '✎ Draft' }}
                                </span>
                            </div>
                        </div>
                        <div class="plant-card__body">
                            <span class="plant-card__category">{{ $plant->category->name }}</span>
                            <h3 class="plant-card__title">{{ $plant->local_name }}</h3>
                            <p class="plant-card__latin">{{ $plant->latin_name }}</p>
                            <p class="plant-card__excerpt">{{ Str::limit(strip_tags($plant->benefits), 100) }}</p>
                        </div>
                    </a>
                    <div class="plant-card__footer">
                        <a href="{{ route('plants.show', $plant->slug) }}" class="btn btn-primary btn-sm">Selengkapnya →</a>
                        <div class="plant-card__views">
                            <span>👁</span>
                            <span>{{ number_format($plant->views) }}</span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pagination">
                {{ $plants->links('vendor.pagination.custom') }}
            </div>

        @else
            <div class="empty-state">
                <div class="empty-state__icon">🌵</div>
                <h3 class="empty-state__title">Tanaman tidak ditemukan</h3>
                <p class="empty-state__desc">Coba kata kunci lain atau reset filter pencarian</p>
                <a href="{{ route('plants.index') }}" class="btn btn-primary" style="margin-top:20px;">Reset Pencarian</a>
            </div>
        @endif
    </div>
</div>

@endsection
