@extends('layouts.admin')
@section('title', 'Kelola Tanaman')
@section('page_title', 'Kelola Tanaman')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
    <div>
        <p style="font-family:'Inter',sans-serif;font-size:14px;color:var(--text-muted);">
            {{ $plants->total() }} tanaman ditemukan
        </p>
    </div>
    <a href="{{ route('editor.plants.create') }}" class="btn btn-primary">➕ Tambah Tanaman</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanaman</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Views</th>
                @if(auth()->user()->isAdmin())<th>Dibuat Oleh</th>@endif
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plants as $plant)
            <tr>
                <td>{{ $loop->iteration + ($plants->currentPage()-1)*$plants->perPage() }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:44px;height:44px;border-radius:10px;overflow:hidden;background:var(--green-100);flex-shrink:0;">
                            <img src="{{ $plant->image_url }}" alt="" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='https://placehold.co/44x44/dcfce7/166534?text=🌿'">
                        </div>
                        <div>
                            <div style="font-weight:600;color:var(--text-primary);font-family:'Inter',sans-serif;">{{ $plant->local_name }}</div>
                            <div style="font-size:12px;color:var(--text-muted);font-style:italic;">{{ $plant->latin_name }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge badge-green" style="background:{{ $plant->category->color }}22;color:{{ $plant->category->color }};">
                        {{ $plant->category->icon }} {{ $plant->category->name }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $plant->status === 'published' ? 'badge-published' : 'badge-draft' }}">
                        {{ $plant->status === 'published' ? '✓ Terbit' : '✎ Draft' }}
                    </span>
                </td>
                <td>{{ number_format($plant->views) }}</td>
                @if(auth()->user()->isAdmin())
                <td style="font-family:'Inter',sans-serif;">{{ $plant->user->name }}</td>
                @endif
                <td style="font-family:'Inter',sans-serif;">{{ $plant->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('plants.show', $plant->slug) }}" class="btn btn-outline btn-sm" target="_blank" title="Lihat">👁</a>
                        <a href="{{ route('editor.plants.edit', $plant) }}" class="btn btn-outline btn-sm" title="Edit">✏️</a>
                        @if(auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('admin.plants.destroy', $plant) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus" data-confirm="Hapus tanaman '{{ $plant->local_name }}'? Tindakan ini tidak bisa dibatalkan.">🗑️</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:48px;color:var(--text-muted);font-family:'Inter',sans-serif;">
                    <div style="font-size:40px;margin-bottom:12px;">🌵</div>
                    Belum ada tanaman. <a href="{{ route('editor.plants.create') }}" style="color:var(--green-600);">Tambah sekarang</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination">{{ $plants->links('vendor.pagination.custom') }}</div>
@endsection
