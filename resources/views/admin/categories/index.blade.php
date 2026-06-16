@extends('layouts.admin')
@section('title', 'Kelola Kategori')
@section('page_title', 'Kelola Kategori')

@section('content')
<div style="display:grid;grid-template-columns:1fr 360px;gap:28px;align-items:start;">

    {{-- Categories List --}}
    <div class="table-wrap">
        <div class="table-header">
            <h3 class="table-title">🏷️ Daftar Kategori</h3>
            <span style="font-family:'Inter',sans-serif;font-size:13px;color:var(--text-muted);">{{ $categories->count() }} kategori</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Icon</th>
                    <th>Tanaman</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:8px;background:{{ $cat->color }}22;display:flex;align-items:center;justify-content:center;font-size:18px;">
                                {{ $cat->icon }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-family:'Inter',sans-serif;font-size:14px;">{{ $cat->name }}</div>
                                <div style="font-size:11px;color:var(--text-muted);">{{ $cat->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span style="font-size:22px;">{{ $cat->icon }}</span></td>
                    <td>
                        <span class="badge badge-green">{{ $cat->published_plants_count }} / {{ $cat->plants_count }}</span>
                    </td>
                    <td style="max-width:200px;">
                        <span style="font-family:'Inter',sans-serif;font-size:12px;color:var(--text-secondary);">{{ Str::limit($cat->description, 60) }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button class="btn btn-outline btn-sm" data-modal-open="editModal-{{ $cat->id }}" title="Edit">✏️</button>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus" data-confirm="Hapus kategori '{{ $cat->name }}'? Hanya bisa dihapus jika tidak ada tanaman.">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- Edit Modal --}}
                <div class="modal-overlay" id="editModal-{{ $cat->id }}">
                    <div class="modal">
                        <div class="modal__header">
                            <h3 class="modal__title">Edit Kategori: {{ $cat->name }}</h3>
                            <button class="modal-close" data-modal-close>✕</button>
                        </div>
                        <form method="POST" action="{{ route('admin.categories.update', $cat) }}">
                            @csrf @method('PUT')
                            <div class="modal__body">
                                <div class="form-group">
                                    <label class="form-label">Nama Kategori</label>
                                    <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                </div>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                                    <div class="form-group">
                                        <label class="form-label">Icon (Emoji)</label>
                                        <input type="text" name="icon" class="form-control" value="{{ $cat->icon }}" maxlength="10">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Warna</label>
                                        <input type="color" name="color" class="form-control" value="{{ $cat->color }}" style="height:45px;padding:4px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control" rows="3">{{ $cat->description }}</textarea>
                                </div>
                            </div>
                            <div class="modal__footer">
                                <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                                <button type="submit" class="btn btn-primary">💾 Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Add Category Form --}}
    <div class="card" style="padding:28px;">
        <h3 style="font-size:18px;margin-bottom:20px;font-family:'Playfair Display',serif;">➕ Tambah Kategori</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Kategori <span class="required">*</span></label>
                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    value="{{ old('name') }}" placeholder="cth: Antiinflamasi" required>
                @error('name') <div class="form-error">⚠ {{ $message }}</div> @enderror
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Icon (Emoji)</label>
                    <input type="text" name="icon" class="form-control" value="{{ old('icon', '🌿') }}" maxlength="10" placeholder="🌿">
                </div>
                <div class="form-group">
                    <label class="form-label">Warna</label>
                    <input type="color" name="color" class="form-control" value="{{ old('color', '#22c55e') }}" style="height:45px;padding:4px;">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat kategori...">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">✅ Tambah Kategori</button>
        </form>
    </div>
</div>

@endsection
