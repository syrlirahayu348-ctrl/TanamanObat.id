@extends('layouts.admin')
@section('title', 'Tambah Tanaman')
@section('page_title', 'Tambah Tanaman Baru')

@section('content')
<div style="max-width:860px;">
    @if($errors->any())
    <div class="alert alert-error" data-dismiss>
        ❌ Harap perbaiki kesalahan berikut: {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('editor.plants.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            {{-- Left Column --}}
            <div>
                <div class="card" style="padding:28px;margin-bottom:24px;">
                    <h3 style="font-size:17px;margin-bottom:20px;font-family:'Playfair Display',serif;color:var(--green-700);">🌿 Informasi Dasar</h3>

                    <div class="form-group">
                        <label class="form-label">Nama Lokal / Umum <span class="required">*</span></label>
                        <input type="text" name="local_name" class="form-control {{ $errors->has('local_name') ? 'is-invalid' : '' }}"
                            value="{{ old('local_name') }}" placeholder="cth: Jahe, Kunyit, Temulawak" required>
                        @error('local_name') <div class="form-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Latin <span class="required">*</span></label>
                        <input type="text" name="latin_name" class="form-control {{ $errors->has('latin_name') ? 'is-invalid' : '' }}"
                            value="{{ old('latin_name') }}" placeholder="cth: Zingiber officinale" required>
                        @error('latin_name') <div class="form-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select name="category_id" class="form-control {{ $errors->has('category_id') ? 'is-invalid' : '' }}" required>
                            <option value="">— Pilih Kategori —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="form-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div class="form-group">
                            <label class="form-label">Asal Daerah</label>
                            <input type="text" name="origin" class="form-control" value="{{ old('origin') }}" placeholder="cth: Jawa, Sumatera">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Musim Panen</label>
                            <input type="text" name="harvest_season" class="form-control" value="{{ old('harvest_season') }}" placeholder="cth: Sepanjang tahun">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status Publikasi <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>✓ Publikasikan</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>✎ Simpan sebagai Draft</option>
                        </select>
                    </div>
                </div>

                {{-- Image Upload --}}
                <div class="card" style="padding:28px;">
                    <h3 style="font-size:17px;margin-bottom:16px;font-family:'Playfair Display',serif;color:var(--green-700);">📷 Foto Tanaman</h3>
                    <div class="file-upload" onclick="document.getElementById('imageInput').click();">
                        <img id="imagePreview" src="" alt="" style="display:none;max-height:180px;margin:0 auto 12px;border-radius:10px;">
                        <div id="uploadPlaceholder">
                            <div style="font-size:40px;margin-bottom:8px;">🖼️</div>
                            <p style="font-family:'Inter',sans-serif;font-size:14px;color:var(--green-700);font-weight:600;">Klik untuk upload foto</p>
                            <p style="font-family:'Inter',sans-serif;font-size:12px;color:var(--text-muted);">PNG, JPG, WEBP • Max 4MB</p>
                        </div>
                    </div>
                    <input type="file" name="image" id="imageInput" class="hidden" accept="image/*" data-image-preview="imagePreview"
                        onchange="document.getElementById('uploadPlaceholder').style.display='none';document.getElementById('imagePreview').style.display='block';">
                    @error('image') <div class="form-error">⚠ {{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Right Column --}}
            <div>
                <div class="card" style="padding:28px;">
                    <h3 style="font-size:17px;margin-bottom:20px;font-family:'Playfair Display',serif;color:var(--green-700);">📝 Informasi Detail</h3>

                    <div class="form-group">
                        <label class="form-label">Deskripsi Tanaman <span class="required">*</span></label>
                        <textarea name="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                            rows="4" placeholder="Deskripsi umum tentang tanaman ini..." required>{{ old('description') }}</textarea>
                        @error('description') <div class="form-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Manfaat & Khasiat <span class="required">*</span></label>
                        <textarea name="benefits" class="form-control {{ $errors->has('benefits') ? 'is-invalid' : '' }}"
                            rows="4" placeholder="Pisahkan setiap manfaat dengan koma. cth: Antiinflamasi, Meningkatkan imun, ..." required>{{ old('benefits') }}</textarea>
                        <div class="form-hint">💡 Pisahkan setiap manfaat dengan tanda koma (,)</div>
                        @error('benefits') <div class="form-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cara Penggunaan <span class="required">*</span></label>
                        <textarea name="usage" class="form-control {{ $errors->has('usage') ? 'is-invalid' : '' }}"
                            rows="4" placeholder="Jelaskan cara mengolah dan menggunakan tanaman ini..." required>{{ old('usage') }}</textarea>
                        @error('usage') <div class="form-error">⚠ {{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Efek Samping & Peringatan</label>
                        <textarea name="side_effects" class="form-control" rows="3"
                            placeholder="Efek samping yang mungkin terjadi (opsional)...">{{ old('side_effects') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" class="btn btn-primary btn-lg">💾 Simpan Tanaman</button>
            <a href="{{ route('editor.plants.index') }}" class="btn btn-outline btn-lg">Batal</a>
        </div>
    </form>
</div>
@endsection
