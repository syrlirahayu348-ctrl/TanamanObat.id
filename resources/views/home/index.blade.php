@extends('layouts.app')

@section('title', 'Beranda')
@section('meta_desc', 'Temukan informasi lengkap tanaman obat tradisional Indonesia di TanamanObat.id — ensiklopedia herbal nusantara terlengkap.')

@section('content')

{{-- ═══ HERO ═══════════════════════════════════════════════════════════════ --}}
<section class="hero">
    <div class="hero__particles" id="heroParticles"></div>

    <div class="container" style="position:relative;z-index:2;">
        <div class="hero__content">
            <div class="hero__badge animate-fade-in">
                🌿 Ensiklopedia Tanaman Obat #1 Indonesia
            </div>

            <h1 class="hero__title">
                Temukan Khasiat<br>
                <span class="highlight">Tanaman Obat</span><br>
                Nusantara
            </h1>

            <p class="hero__subtitle">
                Ensiklopedia digital lengkap tanaman obat tradisional Indonesia. Dari Sabang sampai Merauke, kami dokumentasikan kekayaan herbal nusantara untuk kesehatan Anda.
            </p>

            {{-- Hero Search --}}
            <form action="{{ route('search') }}" method="GET" style="margin-bottom:28px;max-width:480px;animation:fadeInUp 0.7s ease 0.25s both;">
                <div class="hero-search">
                    <span style="font-size:20px;">🔍</span>
                    <input type="text" name="q" placeholder="Cari tanaman obat, manfaat..." autocomplete="off">
                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                </div>
            </form>

            <div class="hero__actions">
                <a href="{{ route('plants.index') }}" class="btn btn-primary btn-lg">
                    🌿 Jelajahi Tanaman
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-white btn-lg">
                    📚 Lihat Kategori
                </a>
            </div>

            <div class="hero__stats">
                <div>
                    <div class="hero__stat-number" data-count="{{ $totalPlants }}">{{ $totalPlants }}</div>
                    <div class="hero__stat-label">Tanaman Obat</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,0.12);"></div>
                <div>
                    <div class="hero__stat-number" data-count="{{ $totalCategories }}">{{ $totalCategories }}</div>
                    <div class="hero__stat-label">Kategori</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,0.12);"></div>
                <div>
                    <div class="hero__stat-number">100%</div>
                    <div class="hero__stat-label">Gratis & Terbuka</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Decorative overlay --}}
    <div style="position:absolute;inset:0;background:radial-gradient(circle at 70% 50%, rgba(34,197,94,0.08) 0%, transparent 60%);pointer-events:none;"></div>
</section>

{{-- ═══ CATEGORIES ══════════════════════════════════════════════════════════ --}}
<section class="section bg-light-section">
    <div class="container">
        <div class="section__header reveal">
            <span class="section__eyebrow">Jelajahi</span>
            <h2 class="section__title">Kategori Tanaman Obat</h2>
            <p class="section__desc">Temukan tanaman obat berdasarkan khasiat dan manfaat kesehatannya</p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(150px,1fr));gap:16px;">
            @foreach($categories as $cat)
            <a href="{{ route('categories.show', $cat->slug) }}" class="category-card reveal" style="animation-delay:{{ $loop->index * 0.05 }}s;">
                <div class="category-card__icon" style="background:{{ $cat->color }}22;">
                    {{ $cat->icon }}
                </div>
                <div class="category-card__name">{{ $cat->name }}</div>
                <div class="category-card__count">{{ $cat->published_plants_count }} tanaman</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ FEATURED PLANTS ═════════════════════════════════════════════════════ --}}
<section class="section">
    <div class="container">
        <div class="section__header reveal" style="display:flex;align-items:flex-end;justify-content:space-between;text-align:left;">
            <div>
                <span class="section__eyebrow">Terpopuler</span>
                <h2 class="section__title" style="margin-bottom:0;">Tanaman Paling Dicari</h2>
            </div>
            <a href="{{ route('plants.index') }}?sort=popular" class="btn btn-outline" style="flex-shrink:0;">Lihat Semua →</a>
        </div>

        <div class="grid-plants">
            @foreach($featuredPlants as $plant)
            <article class="plant-card reveal" style="animation-delay:{{ $loop->index * 0.08 }}s;">
                <div class="plant-card__image-wrap">
                    <img
                        src="{{ $plant->image_url }}"
                        alt="{{ $plant->local_name }}"
                        class="plant-card__image"
                        loading="lazy"
                        onerror="this.src='https://placehold.co/400x200/dcfce7/166534?text=🌿+{{ urlencode($plant->local_name) }}'"
                    >
                    {{-- Views badge --}}
                    <div style="position:absolute;top:12px;left:12px;background:rgba(0,0,0,0.6);backdrop-filter:blur(8px);border-radius:20px;padding:4px 10px;font-size:11px;color:white;font-family:'Inter',sans-serif;">
                        👁 {{ number_format($plant->views) }}
                    </div>
                </div>
                <div class="plant-card__body">
                    <span class="plant-card__category">{{ $plant->category->name }}</span>
                    <h3 class="plant-card__title">{{ $plant->local_name }}</h3>
                    <p class="plant-card__latin">{{ $plant->latin_name }}</p>
                    <p class="plant-card__excerpt">{{ Str::limit(strip_tags($plant->benefits), 90) }}</p>
                </div>
                <div class="plant-card__footer">
                    <a href="{{ route('plants.show', $plant->slug) }}" class="btn btn-primary btn-sm">Selengkapnya →</a>
                    <div class="plant-card__views">
                        <span>🌿</span>
                        <span>{{ Str::limit($plant->latin_name, 18) }}</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ LATEST PLANTS ═══════════════════════════════════════════════════════ --}}
<section class="section bg-light-section">
    <div class="container">
        <div class="section__header reveal" style="display:flex;align-items:flex-end;justify-content:space-between;text-align:left;">
            <div>
                <span class="section__eyebrow">Terbaru</span>
                <h2 class="section__title" style="margin-bottom:0;">Tanaman Terbaru Ditambahkan</h2>
            </div>
            <a href="{{ route('plants.index') }}" class="btn btn-outline" style="flex-shrink:0;">Lihat Semua →</a>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;">
            @foreach($latestPlants as $plant)
            <a href="{{ route('plants.show', $plant->slug) }}" class="reveal" style="text-decoration:none;animation-delay:{{ $loop->index * 0.06 }}s;">
                <div style="display:flex;align-items:center;gap:14px;background:white;border-radius:14px;padding:16px;border:1px solid rgba(0,0,0,0.05);box-shadow:0 2px 8px rgba(0,0,0,0.06);transition:all 0.25s ease;" onmouseenter="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(34,197,94,0.15)';" onmouseleave="this.style.transform='';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.06)';">
                    <div style="width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,var(--green-100),var(--green-200));display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0;">
                        🌿
                    </div>
                    <div style="min-width:0;">
                        <div style="font-family:'Inter',sans-serif;font-size:11px;font-weight:600;color:var(--green-600);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">{{ $plant->category->name }}</div>
                        <div style="font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $plant->local_name }}</div>
                        <div style="font-size:12px;color:var(--text-muted);font-style:italic;font-family:'Inter',sans-serif;">{{ $plant->latin_name }}</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ CTA SECTION ═════════════════════════════════════════════════════════ --}}
<section class="bg-green-section">
    <div class="container text-center">
        <div class="reveal">
            <div style="font-size:56px;margin-bottom:20px;">🌿</div>
            <h2 style="font-size:clamp(28px,4vw,42px);color:white;margin-bottom:16px;">Bergabunglah dengan Komunitas</h2>
            <p style="font-size:18px;color:rgba(255,255,255,0.75);margin-bottom:36px;max-width:560px;margin-left:auto;margin-right:auto;font-family:'Inter',sans-serif;">
                Daftar sekarang untuk menyimpan tanaman favorit Anda dan mendapatkan informasi terbaru seputar tanaman obat nusantara.
            </p>
            @guest
                <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                    <a href="{{ route('register') }}" class="btn btn-white btn-lg">🌟 Daftar Gratis</a>
                    <a href="{{ route('login') }}" class="btn btn-outline btn-lg" style="border-color:rgba(255,255,255,0.4);color:white;">Sudah punya akun? Masuk</a>
                </div>
            @else
                <a href="{{ route('plants.index') }}" class="btn btn-white btn-lg">🌿 Jelajahi Ensiklopedia</a>
            @endguest
        </div>
    </div>
</section>

@endsection
