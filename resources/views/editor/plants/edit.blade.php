@extends('layouts.admin')
@section('title', 'Edit ' . $plant->local_name)
@section('page_title', 'Edit Tanaman: ' . $plant->local_name)

@section('content')
<div style="max-width:860px;">
    @if($errors->any())
    <div class="alert alert-error" data-dismiss>❌ {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('editor.plants.update', $plant) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            <div>
                <div class="card" style="padding:28px;margin-bottom:24px;">
                    <h3 style="font-size:17px;margin-bottom:20px;font-family:'Playfair Display',serif;color:var(--green-700);">🌿 Informasi Dasar</h3>

                    <div class="form-group">
                        <label class="form-label">Nama Lokal <span class="required">*</span></label>
                        <input type="text" name="local_name" class="form-control" value="{{ old('local_name', $plant->local_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Latin <span class="required">*</span></label>
                        <input type="text" name="latin_name" class="form-control" value="{{ old('latin_name', $plant->latin_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (old('category_id', $plant->category_id)) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="form-group">
                            <label class="form-label">Asal Daerah</label>
                            <input type="text" name="origin" class="form-control" value="{{ old('origin', $plant->origin) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Musim Panen</label>
                            <input type="text" name="harvest_season" class="form-control" value="{{ old('harvest_season', $plant->harvest_season) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="published" {{ old('status', $plant->status) === 'published' ? 'selected' : '' }}>✓ Terbit</option>
                            <option value="draft" {{ old('status', $plant->status) === 'draft' ? 'selected' : '' }}>✎ Draft</option>
                        </select>
                    </div>
                </div>

                <div class="card" style="padding:28px;">
                    <h3 style="font-size:17px;margin-bottom:16px;font-family:'Playfair Display',serif;color:var(--green-700);">📷 Foto Tanaman</h3>
                    @if($plant->image)
                    <div style="margin-bottom:12px;">
                        <img src="{{ $plant->image_url }}" id="imagePreview" alt="Foto saat ini" style="width:100%;max-height:180px;object-fit:cover;border-radius:10px;">
                        <p style="font-size:12px;color:var(--text-muted);font-family:'Inter',sans-serif;margin-top:6px;">📌 Foto saat ini</p>
                    </div>
                    @else
                    <img id="imagePreview" src="" alt="" style="display:none;width:100%;max-height:180px;object-fit:cover;border-radius:10px;margin-bottom:12px;">
                    @endif
                    <div class="file-upload" onclick="document.getElementById('imageInput').click();">
                        <p style="font-family:'Inter',sans-serif;font-size:14px;color:var(--green-700);">📁 {{ $plant->image ? 'Ganti foto' : 'Upload foto' }}</p>
                        <p style="font-family:'Inter',sans-serif;font-size:12px;color:var(--text-muted);">PNG, JPG • Max 4MB</p>
                    </div>
                    <input type="file" name="image" id="imageInput" class="hidden" accept="image/*" data-image-preview="imagePreview"
                        onchange="document.getElementById('imagePreview').style.display='block';">
                </div>
            </div>

            <div>
                <div class="card" style="padding:28px;">
                    <h3 style="font-size:17px;margin-bottom:20px;font-family:'Playfair Display',serif;color:var(--green-700);">📝 Detail</h3>

                    <div class="form-group">
                        <label class="form-label">Deskripsi <span class="required">*</span></label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description', $plant->description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Manfaat & Khasiat <span class="required">*</span></label>
                        <textarea name="benefits" class="form-control" rows="4" required>{{ old('benefits', $plant->benefits) }}</textarea>
                        <div class="form-hint">💡 Pisahkan dengan koma (,)</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cara Penggunaan <span class="required">*</span></label>
                        <textarea name="usage" class="form-control" rows="4" required>{{ old('usage', $plant->usage) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Efek Samping</label>
                        <textarea name="side_effects" class="form-control" rows="3">{{ old('side_effects', $plant->side_effects) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" class="btn btn-primary btn-lg">💾 Update Tanaman</button>
            <a href="{{ route('editor.plants.index') }}" class="btn btn-outline btn-lg">Batal</a>
            @if(auth()->user()->isAdmin())
            <form method="POST" action="{{ route('admin.plants.destroy', $plant) }}" style="margin-left:auto;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-lg" data-confirm="Hapus tanaman '{{ $plant->local_name }}'?">🗑️ Hapus</button>
            </form>
            @endif
        </div>
    </form>
</div>
@endsection
