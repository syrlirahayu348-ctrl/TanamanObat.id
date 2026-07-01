<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesan Anda Diterima - TanamanObat.id</title>
<style>
  body { margin:0; padding:0; background:#f1f5f9; font-family:'Segoe UI',Arial,sans-serif; }
  .wrapper { max-width:600px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
  .header { background:linear-gradient(135deg,#166534,#15803d); padding:32px 36px; text-align:center; }
  .header h1 { color:#fff; margin:0; font-size:22px; font-weight:700; }
  .header p { color:rgba(255,255,255,0.8); margin:6px 0 0; font-size:14px; }
  .body { padding:32px 36px; }
  .greeting { font-size:18px; font-weight:700; color:#111827; margin-bottom:12px; }
  .text { color:#6b7280; font-size:14px; line-height:1.7; }
  .message-box { background:#f8fafc; border-left:4px solid #16a34a; border-radius:0 8px 8px 0; padding:16px 20px; margin:20px 0; white-space:pre-line; color:#374151; font-size:14px; line-height:1.7; }
  .info-box { background:#ecfdf5; border-radius:10px; padding:16px 20px; margin:20px 0; }
  .info-box p { margin:0 0 6px; font-size:13px; color:#166534; }
  .info-box p:last-child { margin-bottom:0; }
  .footer { background:#f8fafc; padding:20px 36px; text-align:center; color:#9ca3af; font-size:12px; border-top:1px solid #e5e7eb; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>🌿 TanamanObat.id</h1>
    <p>Konfirmasi Pesan Anda Telah Diterima</p>
  </div>
  <div class="body">
    <p class="greeting">Halo, {{ $contactMessage->name }}! 👋</p>
    <p class="text">Terima kasih telah menghubungi kami. Pesan Anda telah berhasil kami terima dan akan segera ditindaklanjuti oleh tim admin kami.</p>

    <div class="info-box">
      <p>📋 <strong>Ringkasan Pesan Anda:</strong></p>
      <p>Subjek: <strong>{{ $contactMessage->subject }}</strong></p>
      <p>Dikirim: {{ $contactMessage->created_at->format('d M Y, H:i') }} WIB</p>
    </div>

    <p style="font-weight:700;color:#374151;margin-bottom:8px;">Pesan yang Anda kirimkan:</p>
    <div class="message-box">{{ $contactMessage->message }}</div>

    <p class="text">Tim kami biasanya membalas dalam waktu <strong>1×24 jam</strong> di hari kerja. Balasan akan dikirim ke email Anda: <strong>{{ $contactMessage->email }}</strong></p>
    <p class="text" style="margin-top:16px;">Salam hangat,<br><strong>Tim TanamanObat.id</strong> 🌿</p>
  </div>
  <div class="footer">
    TanamanObat.id · Ensiklopedia Tanaman Obat Nusantara<br>
    Ini adalah email konfirmasi otomatis. Balasan akan dikirim oleh admin secara terpisah.
  </div>
</div>
</body>
</html>
