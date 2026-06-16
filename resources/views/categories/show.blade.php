@extends('layouts.app')
@section('title', $category->name . ' — Kategori')

@section('content')
<div class="page-header" style="background:linear-gradient(135deg, {{ $category->color }}44, var(--dark-800));">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a><span>›</span>
            <a href="{{ route('categories.index') }}">Kategori</a><span>›</span>
            <span style="color:rgba(255,255,255,0.8);">{{ $category->name }}</span>
        </div>
        <div style="display:flex;align-items:center;gap:20px;">
            <div style="width:72px;height:72px;border-radius:16px;background:{{ $category->color }}33;border:2px solid {{ $category->color }}66;display:flex;align-items:center;justify-content:center;font-size:36px;">
                {{ $category->icon }}
            </div>
            <div>
                <h1 class="page-header__title">{{ $category->name }}</h1>
                <p class="page-header__subtitle">{{ $category->description }}</p>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div style="margin-bottom:24px;font-family:'Inter',sans-serif;font-size:14px;color:var(--text-muted);">
            <strong style="color:var(--green-700);">{{ $plants->total() }}</strong> tanaman dalam kategori ini
        </div>

        @if($plants->count() > 0)
        <div class="grid-plants">
            @foreach($plants as $plant)
            <article class="plant-card reveal">
                <a href="{{ route('plants.show', $plant->slug) }}" style="text-decoration:none;color:inherit;">
                    <div class="plant-card__image-wrap">
                        <img src="{{ $plant->image_url }}" alt="{{ $plant->local_name }}" class="plant-card__image" loading="lazy" onerror="this.src='https://placehold.co/400x200/dcfce7/166534?text=🌿'">
                    </div>
                    <div class="plant-card__body">
                        <h3 class="plant-card__title">{{ $plant->local_name }}</h3>
                        <p class="plant-card__latin">{{ $plant->latin_name }}</p>
                        <p class="plant-card__excerpt">{{ Str::limit(strip_tags($plant->benefits), 90) }}</p>
                    </div>
                </a>
                <div class="plant-card__footer">
                    <a href="{{ route('plants.show', $plant->slug) }}" class="btn btn-primary btn-sm">Selengkapnya →</a>
                    <div class="plant-card__views"><span>👁</span><span>{{ number_format($plant->views) }}</span></div>
                </div>
            </article>
            @endforeach
        </div>
        <div class="pagination">{{ $plants->links('vendor.pagination.custom') }}</div>
        @else
        <div class="empty-state">
            <div class="empty-state__icon">{{ $category->icon }}</div>
            <h3 class="empty-state__title">Belum ada tanaman</h3>
            <p class="empty-state__desc">Kategori ini belum memiliki tanaman yang dipublikasikan</p>
        </div>
        @endif
    </div>
</div>
@endsection
