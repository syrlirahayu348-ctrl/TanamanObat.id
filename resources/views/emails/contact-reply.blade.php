<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Balasan dari TanamanObat.id</title>
<style>
  body { margin:0; padding:0; background:#f1f5f9; font-family:'Segoe UI',Arial,sans-serif; }
  .wrapper { max-width:600px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
  .header { background:linear-gradient(135deg,#166534,#15803d); padding:32px 36px; text-align:center; }
  .header h1 { color:#fff; margin:0; font-size:22px; font-weight:700; }
  .header p { color:rgba(255,255,255,0.8); margin:6px 0 0; font-size:14px; }
  .body { padding:32px 36px; }
  .greeting { font-size:18px; font-weight:700; color:#111827; margin-bottom:12px; }
  .text { color:#6b7280; font-size:14px; line-height:1.7; }
  .reply-box { background:#ecfdf5; border:2px solid #bbf7d0; border-radius:12px; padding:20px 24px; margin:20px 0; color:#166534; font-size:14px; line-height:1.7; white-space:pre-line; }
  .original-box { background:#f8fafc; border-left:4px solid #d1d5db; border-radius:0 8px 8px 0; padding:14px 18px; margin:16px 0; white-space:pre-line; color:#9ca3af; font-size:13px; line-height:1.6; }
  .footer { background:#f8fafc; padding:20px 36px; text-align:center; color:#9ca3af; font-size:12px; border-top:1px solid #e5e7eb; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>🌿 TanamanObat.id</h1>
    <p>Balasan untuk Pesan Anda</p>
  </div>
  <div class="body">
    <p class="greeting">Halo, {{ $contactMessage->name }}! 👋</p>
    <p class="text">Tim admin TanamanObat.id telah membalas pesan Anda mengenai: <strong>{{ $contactMessage->subject }}</strong></p>

    <p style="font-weight:700;color:#166534;margin-bottom:8px;">💬 Balasan dari Admin:</p>
    <div class="reply-box">{{ $replyMessage }}</div>

    <p style="font-weight:700;color:#9ca3af;font-size:13px;margin-bottom:6px;">Pesan asli Anda:</p>
    <div class="original-box">{{ $contactMessage->message }}</div>

    <p class="text" style="margin-top:20px;">Jika Anda masih memiliki pertanyaan, silakan kunjungi halaman <a href="{{ url('/contact') }}" style="color:#16a34a;font-weight:600;">Hubungi Kami</a> dan kirimkan pesan baru.</p>

    <p class="text" style="margin-top:16px;">Salam hangat,<br><strong>Tim TanamanObat.id</strong> 🌿</p>
  </div>
  <div class="footer">
    TanamanObat.id · Ensiklopedia Tanaman Obat Nusantara<br>
    Email ini dikirim sebagai balasan resmi dari admin.
  </div>
</div>
</body>
</html>
