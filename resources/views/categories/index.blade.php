@extends('layouts.app')
@section('title', 'Kategori Tanaman Obat')

@section('content')
<div class="page-header">
    <div class="container">
        <div class="breadcrumb"><a href="{{ route('home') }}">Beranda</a><span>›</span><span>Kategori</span></div>
        <h1 class="page-header__title">🏷️ Kategori Tanaman Obat</h1>
        <p class="page-header__subtitle">Temukan tanaman berdasarkan manfaat dan khasiatnya</p>
    </div>
</div>

<div class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:20px;">
            @foreach($categories as $cat)
            <a href="{{ route('categories.show', $cat->slug) }}" class="category-card reveal" style="animation-delay:{{ $loop->index * 0.05 }}s;">
                <div class="category-card__icon" style="background:{{ $cat->color }}22;width:72px;height:72px;font-size:32px;">
                    {{ $cat->icon }}
                </div>
                <div class="category-card__name">{{ $cat->name }}</div>
                <p style="font-family:'Inter',sans-serif;font-size:12px;color:var(--text-muted);margin-top:6px;text-align:center;line-height:1.5;">{{ Str::limit($cat->description, 60) }}</p>
                <div style="margin-top:12px;background:var(--green-100);color:var(--green-700);padding:4px 12px;border-radius:20px;font-size:12px;font-weight:700;font-family:'Inter',sans-serif;">
                    {{ $cat->published_plants_count }} Tanaman
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
