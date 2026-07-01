<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesan Baru - TanamanObat.id</title>
<style>
  body { margin:0; padding:0; background:#f1f5f9; font-family:'Segoe UI',Arial,sans-serif; }
  .wrapper { max-width:600px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
  .header { background:linear-gradient(135deg,#166534,#15803d); padding:32px 36px; text-align:center; }
  .header h1 { color:#fff; margin:0; font-size:22px; font-weight:700; }
  .header p { color:rgba(255,255,255,0.8); margin:6px 0 0; font-size:14px; }
  .body { padding:32px 36px; }
  .badge { display:inline-block; background:#dcfce7; color:#166534; font-weight:700; font-size:12px; padding:4px 12px; border-radius:20px; margin-bottom:20px; }
  .info-row { display:flex; margin-bottom:12px; border-bottom:1px solid #f1f5f9; padding-bottom:12px; }
  .info-label { font-weight:700; color:#374151; min-width:90px; font-size:14px; }
  .info-value { color:#6b7280; font-size:14px; word-break:break-word; }
  .message-box { background:#f8fafc; border-left:4px solid #16a34a; border-radius:0 8px 8px 0; padding:16px 20px; margin:20px 0; white-space:pre-line; color:#374151; font-size:14px; line-height:1.7; }
  .cta { text-align:center; margin-top:24px; }
  .btn { background:linear-gradient(135deg,#166534,#15803d); color:#fff; text-decoration:none; padding:12px 28px; border-radius:10px; font-weight:700; font-size:14px; display:inline-block; }
  .footer { background:#f8fafc; padding:20px 36px; text-align:center; color:#9ca3af; font-size:12px; border-top:1px solid #e5e7eb; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>🌿 TanamanObat.id</h1>
    <p>Notifikasi Pesan Baru dari Formulir Kontak</p>
  </div>
  <div class="body">
    <span class="badge">📬 Pesan Baru Masuk</span>
    <div class="info-row">
      <span class="info-label">Nama</span>
      <span class="info-value">{{ $contactMessage->name }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Email</span>
      <span class="info-value">{{ $contactMessage->email }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Subjek</span>
      <span class="info-value">{{ $contactMessage->subject }}</span>
    </div>
    <div class="info-row" style="border:none;padding:0;margin-bottom:0;">
      <span class="info-label">Waktu</span>
      <span class="info-value">{{ $contactMessage->created_at->format('d M Y, H:i') }} WIB</span>
    </div>

    <p style="font-weight:700;color:#374151;margin-top:20px;margin-bottom:8px;">Isi Pesan:</p>
    <div class="message-box">{{ $contactMessage->message }}</div>

    <div class="cta">
      <a href="{{ url('/admin/messages') }}" class="btn">Balas Pesan di Dashboard →</a>
    </div>
  </div>
  <div class="footer">
    TanamanObat.id · Ensiklopedia Tanaman Obat Nusantara<br>
    Email ini dikirim otomatis, tidak perlu dibalas langsung.
  </div>
</div>
</body>
</html>
