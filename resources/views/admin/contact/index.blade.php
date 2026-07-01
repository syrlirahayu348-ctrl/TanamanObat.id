@extends('layouts.admin')
@section('title', 'Pesan Masuk')
@section('page_title', 'Pesan Masuk — Hubungi Kami')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
    <p style="font-family:'Inter',sans-serif;font-size:14px;color:var(--text-muted);">
        {{ $messages->total() }} pesan ditemukan
    </p>
</div>

@if($messages->isEmpty())
    <div style="text-align:center;padding:60px 20px;background:white;border-radius:16px;border:1px dashed #d1d5db;">
        <div style="font-size:48px;margin-bottom:12px;">📭</div>
        <h3 style="font-family:'Playfair Display',serif;font-size:20px;color:var(--text-primary);margin:0 0 8px;">Tidak Ada Pesan</h3>
        <p style="color:var(--text-muted);font-size:14px;font-family:'Inter',sans-serif;">Belum ada pesan masuk dari pengguna.</p>
    </div>
@else
    <div style="display:flex;flex-direction:column;gap:20px;">
        @foreach($messages as $msg)
        <div class="card-glass" style="padding:24px;border-radius:16px;border-left:4px solid var(--green-500);" id="msg-{{ $msg->id }}">
            {{-- Header --}}
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;flex-wrap:wrap;gap:12px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--green-500),var(--green-700));color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:18px;flex-shrink:0;">
                        {{ strtoupper(substr($msg->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:15px;color:var(--text-primary);font-family:'Inter',sans-serif;">{{ $msg->name }}</div>
                        <div style="font-size:13px;color:var(--text-muted);font-family:'Inter',sans-serif;">
                            <a href="mailto:{{ $msg->email }}" style="color:var(--green-600);text-decoration:none;">{{ $msg->email }}</a>
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:12px;color:var(--text-muted);font-family:'Inter',sans-serif;">
                        🕐 {{ $msg->created_at->diffForHumans() }}
                    </span>
                    {{-- Tombol Hapus Tanpa Balas --}}
                    <form method="POST" action="{{ route('admin.contact.destroy', $msg) }}" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus tanpa membalas"
                            data-confirm="Hapus pesan dari {{ $msg->name }}? Pesan ini tidak akan dibalas.">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>

            {{-- Subjek --}}
            <div style="background:var(--green-50);border-radius:8px;padding:10px 14px;margin-bottom:16px;">
                <span style="font-size:12px;font-weight:600;color:var(--green-700);font-family:'Inter',sans-serif;">SUBJEK</span>
                <p style="margin:4px 0 0;font-weight:700;font-size:15px;color:var(--text-primary);font-family:'Inter',sans-serif;">{{ $msg->subject }}</p>
            </div>

            {{-- Isi pesan --}}
            <p style="font-family:'Inter',sans-serif;font-size:14px;color:var(--text-primary);line-height:1.7;white-space:pre-line;margin:0 0 20px;">{{ $msg->message }}</p>

            {{-- Form Balas --}}
            <div style="border-top:1px dashed var(--green-200);padding-top:16px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <span style="font-size:14px;font-weight:700;color:var(--green-700);font-family:'Inter',sans-serif;">💬 Balas ke {{ $msg->name }}</span>
                    <button type="button" onclick="toggleReply({{ $msg->id }})"
                        style="background:var(--green-600);color:white;border:none;padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;">
                        ✉️ Balas
                    </button>
                </div>

                <div id="reply-{{ $msg->id }}" style="display:none;">
                    <form method="POST" action="{{ route('admin.contact.reply', $msg) }}">
                        @csrf
                        <div style="margin-bottom:12px;">
                            <label style="display:block;font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:6px;font-family:'Inter',sans-serif;">
                                Balasan (akan dikirim ke: <strong>{{ $msg->email }}</strong>)
                            </label>
                            <textarea name="reply" rows="5"
                                style="width:100%;border:1.5px solid var(--green-200);border-radius:10px;padding:12px;font-family:'Inter',sans-serif;font-size:14px;outline:none;resize:vertical;box-sizing:border-box;"
                                placeholder="Tulis balasan Anda di sini..." required></textarea>
                            @error('reply') <p style="color:#dc2626;font-size:12px;margin:4px 0 0;">{{ $message }}</p> @enderror
                        </div>
                        <div style="display:flex;gap:8px;justify-content:flex-end;">
                            <button type="button" onclick="toggleReply({{ $msg->id }})"
                                style="background:#f1f5f9;border:none;color:#475569;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary" style="font-size:13px;">
                                📤 Kirim Balasan & Hapus Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="pagination" style="margin-top:24px;">
        {{ $messages->links('vendor.pagination.custom') }}
    </div>
@endif

@endsection

@push('scripts')
<script>
function toggleReply(id) {
    const el = document.getElementById('reply-' + id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>
@endpush
