@extends('layouts.admin')
@section('title', 'Kelola Pengguna')
@section('page_title', 'Kelola Pengguna')

@section('content')
<div class="table-wrap">
    <div class="table-header">
        <h3 class="table-title">👥 Daftar Pengguna</h3>
        <span style="font-family:'Inter',sans-serif;font-size:13px;color:var(--text-muted);">{{ $users->total() }} pengguna terdaftar</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Pengguna</th>
                <th>Role</th>
                <th>Status</th>
                <th>Bergabung</th>
                <th>Favorit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr style="{{ !$user->is_active ? 'opacity:0.6;' : '' }}">
                <td>{{ $loop->iteration + ($users->currentPage()-1)*$users->perPage() }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div class="user-avatar" style="width:40px;height:40px;font-size:16px;">
                            @if($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}" alt="">
                            @else
                                {{ strtoupper(substr($user->name,0,1)) }}
                            @endif
                        </div>
                        <div>
                            <div style="font-weight:600;font-family:'Inter',sans-serif;font-size:14px;">
                                {{ $user->name }}
                                @if($user->id === auth()->id()) <span style="font-size:10px;color:var(--green-600);">(Anda)</span> @endif
                            </div>
                            <div style="font-size:12px;color:var(--text-muted);">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>{!! $user->role_badge !!}</td>
                <td>
                    @if($user->is_active)
                        <span class="badge badge-published">● Aktif</span>
                    @else
                        <span class="badge" style="background:#fee2e2;color:#dc2626;">✕ Nonaktif</span>
                    @endif
                </td>
                <td style="font-family:'Inter',sans-serif;font-size:13px;">{{ $user->created_at->format('d M Y') }}</td>
                <td style="font-family:'Inter',sans-serif;font-size:13px;">❤️ {{ $user->favorites()->count() }}</td>
                <td>
                    @if($user->id !== auth()->id())
                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                        {{-- Change Role --}}
                        <button class="btn btn-outline btn-sm" data-modal-open="roleModal-{{ $user->id }}" title="Ubah Role">👑</button>

                        {{-- Toggle Active --}}
                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline' : 'btn-primary' }}" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}" data-confirm="{{ $user->is_active ? 'Nonaktifkan akun '.$user->name.'?' : 'Aktifkan akun '.$user->name.'?' }}">
                                {{ $user->is_active ? '🚫' : '✅' }}
                            </button>
                        </form>

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus" data-confirm="Hapus akun {{ $user->name }}? Data favorit juga akan terhapus.">🗑️</button>
                        </form>
                    </div>

                    {{-- Role Modal --}}
                    <div class="modal-overlay" id="roleModal-{{ $user->id }}">
                        <div class="modal">
                            <div class="modal__header">
                                <h3 class="modal__title">Ubah Role: {{ $user->name }}</h3>
                                <button class="modal-close" data-modal-close>✕</button>
                            </div>
                            <form method="POST" action="{{ route('admin.users.role', $user) }}">
                                @csrf @method('PATCH')
                                <div class="modal__body">
                                    <p style="font-family:'Inter',sans-serif;font-size:14px;color:var(--text-secondary);margin-bottom:20px;">
                                        Mengubah role pengguna akan mengubah hak akses mereka di sistem.
                                    </p>
                                    <div style="display:flex;flex-direction:column;gap:10px;">
                                        @foreach(['admin', 'editor', 'user'] as $role)
                                        <label style="display:flex;align-items:center;gap:12px;padding:14px;border:2px solid {{ $user->role === $role ? 'var(--green-500)' : '#e2e8f0' }};border-radius:10px;cursor:pointer;transition:all 0.2s;">
                                            <input type="radio" name="role" value="{{ $role }}" {{ $user->role === $role ? 'checked' : '' }} style="accent-color:var(--green-500);">
                                            <div>
                                                <div style="font-family:'Inter',sans-serif;font-weight:700;font-size:14px;">
                                                    {{ $role === 'admin' ? '🔑 Admin' : ($role === 'editor' ? '✏️ Editor' : '👤 User') }}
                                                </div>
                                                <div style="font-size:12px;color:var(--text-muted);">
                                                    {{ $role === 'admin' ? 'Akses penuh ke semua fitur' : ($role === 'editor' ? 'Dapat tambah & edit tanaman' : 'Hanya dapat melihat & menyimpan favorit') }}
                                                </div>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="modal__footer">
                                    <button type="button" class="btn btn-outline" data-modal-close>Batal</button>
                                    <button type="submit" class="btn btn-primary">✅ Simpan Role</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @else
                        <span style="font-size:12px;color:var(--text-muted);">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination">{{ $users->links('vendor.pagination.custom') }}</div>
@endsection
