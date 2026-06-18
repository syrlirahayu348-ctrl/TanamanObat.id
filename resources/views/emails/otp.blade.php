<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi - TanamanObat.id</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            color: #334155;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding: 40px 0;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #15803d, #166534);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 26px;
            margin: 0;
            font-weight: 800;
            letter-spacing: -0.5px;
            display: inline-flex;
            align-items: center;
        }
        .header p {
            color: #bbf7d0;
            margin: 10px 0 0 0;
            font-size: 14px;
        }
        .content {
            padding: 40px 35px;
            line-height: 1.6;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .lead-text {
            font-size: 15px;
            color: #475569;
            margin-bottom: 30px;
        }
        .otp-box {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 2px dashed #86efac;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            margin-bottom: 30px;
        }
        .otp-label {
            font-size: 12px;
            font-weight: 700;
            color: #166534;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }
        .otp-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 38px;
            font-weight: 800;
            color: #14532d;
            letter-spacing: 6px;
            margin: 0;
        }
        .warning-text {
            font-size: 13px;
            color: #64748b;
            background-color: #f1f5f9;
            border-left: 4px solid #94a3b8;
            padding: 12px 16px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 30px;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 24px 35px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .footer a {
            color: #15803d;
            text-decoration: none;
            font-weight: 600;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div style="font-size: 40px; margin-bottom: 10px;">🌿</div>
                <h1>TanamanObat.id</h1>
                <p>Ensiklopedia & Komunitas Tanaman Obat Indonesia</p>
            </div>
            
            <!-- Content -->
            <div class="content">
                <p class="greeting">Halo, {{ $name }}!</p>
                <p class="lead-text">
                    Terima kasih telah bergabung di <strong>TanamanObat.id</strong>. Untuk menyelesaikan proses verifikasi akun Anda, silakan gunakan kode OTP di bawah ini:
                </p>
                
                <!-- OTP Box -->
                <div class="otp-box">
                    <div class="otp-label">Kode OTP Verifikasi</div>
                    <div class="otp-code">{{ $otp }}</div>
                </div>
                
                <div class="warning-text">
                    <strong>Penting:</strong> Kode OTP ini hanya berlaku selama 15 menit. Mohon untuk tidak membagikan kode ini kepada siapapun demi keamanan akun Anda.
                </div>
                
                <p style="margin-bottom: 0; font-size: 14px; color: #475569;">
                    Jika Anda tidak melakukan pendaftaran ini, Anda dapat mengabaikan email ini dengan aman.
                </p>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0 0 8px 0;">&copy; {{ date('Y') }} TanamanObat.id. Hak Cipta Dilindungi.</p>
                <p style="margin: 0;">
                    Butuh bantuan? Hubungi kami melalui <a href="{{ url('/contact') }}">Halaman Kontak</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
