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
        <p class="page-header__subtitle" style="font-style:italic;margin-bottom:8px;">{{ $plant->latin_name }}</p>
        <div style="display:flex;align-items:center;gap:8px;font-family:'Inter',sans-serif;font-size:14px;color:rgba(255,255,255,0.9);">
            <span style="color:#fbbf24;font-size:18px;">★</span>
            <strong>{{ number_format($plant->averageRating(), 1) }}</strong> / 5.0
            <span style="opacity:0.75;">({{ $plant->comments->count() }} Ulasan)</span>
        </div>
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

                {{-- ── Ulasan & Rating Section ───────────────────────────── --}}
                <div style="margin-top:40px;border-top:1.5px solid var(--green-100);padding-top:32px;font-family:'Inter',sans-serif;">
                    <h2 style="font-family:'Playfair Display',serif;font-size:24px;color:var(--text-primary);margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                        <span>💬</span> Ulasan & Rating Pengguna
                    </h2>

                    {{-- Form Rating --}}
                    <div class="card-glass" style="padding:24px;border-radius:16px;margin-bottom:32px;">
                        @auth
                            @php
                                $isAdminOrEditor = auth()->user()->isAdmin() || auth()->user()->isEditor();
                            @endphp
                            <h3 style="font-size:16px;font-weight:700;margin:0 0 16px 0;color:var(--green-700);">
                                {{ $isAdminOrEditor ? '✨ Tambah Ulasan / Komentar (Admin/Editor)' : ($userComment ? '✏️ Perbarui Ulasan Anda' : '✨ Berikan Ulasan Anda') }}
                            </h3>
                            <form method="POST" action="{{ route('plants.comment.store', $plant) }}">
                                @csrf
                                <div style="margin-bottom:16px;">
                                    <label style="display:block;font-size:14px;font-weight:600;margin-bottom:8px;color:var(--text-primary);">Rating @if(!$isAdminOrEditor)<span style="color:#dc2626;">*</span>@else<span style="font-weight:normal;font-size:12px;color:var(--text-muted);"> (Opsional)</span>@endif</label>
                                    <div class="rating-stars-input">
                                        <input type="radio" id="star5" name="rating" value="5" {{ (!$isAdminOrEditor && old('rating', $userComment?->rating) == 5) ? 'checked' : '' }} {{ $isAdminOrEditor ? '' : 'required' }} />
                                        <label for="star5" title="5 Bintang">★</label>
                                        <input type="radio" id="star4" name="rating" value="4" {{ (!$isAdminOrEditor && old('rating', $userComment?->rating) == 4) ? 'checked' : '' }} />
                                        <label for="star4" title="4 Bintang">★</label>
                                        <input type="radio" id="star3" name="rating" value="3" {{ (!$isAdminOrEditor && old('rating', $userComment?->rating) == 3) ? 'checked' : '' }} />
                                        <label for="star3" title="3 Bintang">★</label>
                                        <input type="radio" id="star2" name="rating" value="2" {{ (!$isAdminOrEditor && old('rating', $userComment?->rating) == 2) ? 'checked' : '' }} />
                                        <label for="star2" title="2 Bintang">★</label>
                                        <input type="radio" id="star1" name="rating" value="1" {{ (!$isAdminOrEditor && old('rating', $userComment?->rating) == 1) ? 'checked' : '' }} />
                                        <label for="star1" title="1 Bintang">★</label>
                                    </div>
                                    @error('rating') <div style="color:#dc2626;font-size:12px;margin-top:4px;">⚠️ {{ $message }}</div> @enderror
                                </div>

                                <div style="margin-bottom:20px;">
                                    <label for="commentInput" style="display:block;font-size:14px;font-weight:600;margin-bottom:8px;color:var(--text-primary);">Komentar & Ulasan <span style="color:#dc2626;">*</span></label>
                                    <textarea name="comment" id="commentInput" rows="4" style="width:100%;border:1px solid var(--green-200);border-radius:12px;padding:12px;font-family:inherit;font-size:14px;outline:none;resize:vertical;" placeholder="Ceritakan pengalaman Anda menggunakan tanaman obat ini..." required>{{ $isAdminOrEditor ? '' : old('comment', $userComment?->comment) }}</textarea>
                                    @error('comment') <div style="color:#dc2626;font-size:12px;margin-top:4px;">⚠️ {{ $message }}</div> @enderror
                                </div>

                                <button type="submit" class="btn btn-primary" style="background:linear-gradient(135deg, var(--green-600), var(--green-700));border:none;font-weight:600;">
                                    {{ $isAdminOrEditor ? 'Kirim Ulasan' : ($userComment ? 'Perbarui Ulasan' : 'Kirim Ulasan') }}
                                </button>
                            </form>
                        @else
                            <div style="text-align:center;padding:16px 0;">
                                <p style="color:var(--text-muted);font-size:14px;margin-bottom:16px;">Silakan login terlebih dahulu untuk memberikan rating dan ulasan.</p>
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm" style="display:inline-flex;align-items:center;">🚪 Login Sekarang</a>
                            </div>
                        @endauth
                    </div>

                    {{-- Daftar Komentar --}}
                    <div style="display:flex;flex-direction:column;gap:20px;">
                        @forelse($plant->comments as $comment)
                            <div class="card-glass" style="padding:20px;border-radius:14px;position:relative;">
                                @if(auth()->check() && $comment->user_id === auth()->id() && !$comment->parent_id)
                                    <span style="position:absolute;top:16px;right:20px;background:var(--green-100);color:var(--green-800);font-size:11px;font-weight:700;padding:4px 8px;border-radius:12px;">Ulasan Anda</span>
                                @endif
                                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                                    <div style="width:40px;height:40px;border-radius:50%;background:var(--green-100);color:var(--green-700);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:16px;flex-shrink:0;">
                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:700;font-size:14px;color:var(--text-primary);display:flex;align-items:center;gap:6px;">
                                            {{ $comment->user->name }}
                                            @if($comment->user->isAdmin())
                                                <span style="font-size:10px;background:#fee2e2;color:#991b1b;padding:2px 6px;border-radius:8px;font-weight:600;">Admin</span>
                                            @elseif($comment->user->isEditor())
                                                <span style="font-size:10px;background:#e0f2fe;color:#075985;padding:2px 6px;border-radius:8px;font-weight:600;">Editor</span>
                                            @endif
                                        </div>
                                        <div style="display:flex;align-items:center;gap:6px;">
                                            @if($comment->rating)
                                                <span style="color:#fbbf24;font-size:14px;">
                                                    {!! str_repeat('★', $comment->rating) . str_repeat('☆', 5 - $comment->rating) !!}
                                                </span>
                                            @endif
                                            <span style="color:var(--text-muted);font-size:11px;">• {{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <p style="font-size:14px;line-height:1.6;color:var(--text-primary);margin:0;white-space:pre-line;">{{ $comment->comment }}</p>

                                {{-- List Balasan --}}
                                @if($comment->replies->count() > 0)
                                    <div style="margin-top: 16px; padding-left: 16px; border-left: 3px solid var(--green-200); display: flex; flex-direction: column; gap: 12px;">
                                        @foreach($comment->replies as $reply)
                                            <div style="background: rgba(255, 255, 255, 0.4); padding: 12px; border-radius: 10px;">
                                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                                    <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--green-600); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; flex-shrink: 0;">
                                                        {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div style="font-weight: 700; font-size: 13px; color: var(--text-primary); display: flex; align-items: center; gap: 6px;">
                                                            {{ $reply->user->name }}
                                                            @if($reply->user->isAdmin())
                                                                <span style="font-size: 9px; background: #fee2e2; color: #991b1b; padding: 1px 5px; border-radius: 8px; font-weight: 600;">Admin</span>
                                                            @elseif($reply->user->isEditor())
                                                                <span style="font-size: 9px; background: #e0f2fe; color: #075985; padding: 1px 5px; border-radius: 8px; font-weight: 600;">Editor</span>
                                                            @endif
                                                        </div>
                                                        <div style="color: var(--text-muted); font-size: 10px;">{{ $reply->created_at->diffForHumans() }}</div>
                                                    </div>
                                                </div>
                                                <p style="font-size: 13px; line-height: 1.5; color: var(--text-primary); margin: 0; white-space: pre-line;">{{ $reply->comment }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Tombol & Form Balasan Admin/Editor --}}
                                @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isEditor()))
                                    <div style="margin-top: 12px; text-align: right;">
                                        <button type="button" onclick="toggleReplyForm({{ $comment->id }})" style="background: none; border: none; color: var(--green-600); font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; padding: 0;">
                                            💬 Balas Ulasan
                                        </button>
                                    </div>

                                    <div id="reply-form-{{ $comment->id }}" style="display: none; margin-top: 12px; padding-top: 12px; border-top: 1px dashed var(--green-100);">
                                        <form method="POST" action="{{ route('plants.comment.store', $plant) }}">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <div style="margin-bottom: 8px;">
                                                <textarea name="comment" rows="2" style="width: 100%; border: 1px solid var(--green-200); border-radius: 8px; padding: 8px; font-family: inherit; font-size: 13px; outline: none; resize: vertical;" placeholder="Tulis balasan Anda..." required></textarea>
                                            </div>
                                            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                                <button type="button" onclick="toggleReplyForm({{ $comment->id }})" style="background: #f1f5f9; border: none; color: #475569; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">
                                                    Batal
                                                </button>
                                                <button type="submit" class="btn btn-primary btn-sm" style="background: var(--green-600); border: none; color: white; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; height: auto;">
                                                    Kirim
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div style="text-align:center;padding:40px 20px;background:#f8fafc;border-radius:12px;border:1px dashed var(--green-200);">
                                <span style="font-size:40px;display:block;margin-bottom:12px;">🌱</span>
                                <h4 style="font-family:'Playfair Display',serif;font-size:16px;margin:0 0 4px 0;color:var(--text-primary);">Belum ada ulasan</h4>
                                <p style="font-size:13px;color:var(--text-muted);margin:0;">Jadilah yang pertama memberikan ulasan untuk tanaman obat ini!</p>
                            </div>
                        @endforelse
                    </div>
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
    
    .rating-stars-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 4px;
    }
    .rating-stars-input input {
        display: none;
    }
    .rating-stars-input label {
        font-size: 32px;
        color: #cbd5e1;
        cursor: pointer;
        transition: color 0.2s;
    }
    .rating-stars-input label:hover,
    .rating-stars-input label:hover ~ label,
    .rating-stars-input input:checked ~ label {
        color: #fbbf24;
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        if (form) {
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    }
</script>
@endpush
