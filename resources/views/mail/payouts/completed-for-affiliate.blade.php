<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payout Berhasil Dibayarkan - Affiliatekan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            background-color: #F8FAFC;
            font-family: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #F8FAFC;
            padding: 40px 0;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 32px;
            border: 1px solid #E2E8F0;
            overflow: hidden;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.03);
        }
        .header {
            padding: 40px 40px 30px;
            text-align: center;
            background: linear-gradient(180deg, #FFF9F5 0%, #FFFFFF 100%);
            border-bottom: 1px solid #FFF5EE;
        }
        .logo {
            margin-bottom: 20px;
        }
        .logo img {
            height: 48px;
            width: auto;
            display: block;
            margin: 0 auto;
        }
        .badge {
            display: inline-block;
            background-color: #E6FBF3;
            color: #039855;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            padding: 6px 16px;
            border-radius: 100px;
            margin-bottom: 15px;
        }
        .title {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            color: #1E293B;
            line-height: 1.3;
            letter-spacing: -0.02em;
        }
        .content {
            padding: 40px;
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
        }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #1E293B;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .panel {
            background-color: #FFFDFB;
            border: 1px solid #FFEFE5;
            border-radius: 24px;
            padding: 24px;
            margin: 30px 0;
        }
        .panel-title {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #94A3B8;
        }
        .btn-container {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 10px;
        }
        .btn {
            display: inline-block;
            background-color: #FF6B00;
            color: #FFFFFF !important;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 18px;
            box-shadow: 0 10px 20px -5px rgba(255, 107, 0, 0.25);
        }
        .footer {
            max-width: 580px;
            margin: 0 auto;
            padding: 30px 20px 40px;
            text-align: center;
            font-size: 12px;
            color: #94A3B8;
            line-height: 1.5;
        }
        .footer a {
            color: #FF6B00;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="logo">
                    <img src="{{ asset('images/Pavicon - Affilaitekan.svg') }}" alt="Affiliatekan Logo">
                </div>
                <span class="badge">Pencairan Sukses</span>
                <h1 class="title">Komisi Berhasil Dibayarkan</h1>
            </div>
            
            <div class="content">
                <p class="greeting">Halo {{ $payout->affiliate?->user?->name ?? 'Afiliator' }},</p>
                <p>Kabar gembira! Pembayaran komisi Anda telah sukses ditransfer oleh pihak <strong>{{ $payout->vendor?->company_name ?? '-' }}</strong>. Dokumen bukti transfer resmi telah diunggah ke sistem kami.</p>
                
                <div class="panel">
                    <p class="panel-title">Rincian Pembayaran Komisi</p>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 8px 0; font-size: 13px; font-weight: 600; color: #64748B; border-bottom: 1px dashed #F1F5F9;">Vendor Pembayar</td>
                            <td style="padding: 8px 0; font-size: 13px; font-weight: 700; color: #1E293B; text-align: right; border-bottom: 1px dashed #F1F5F9;">{{ $payout->vendor?->company_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; font-size: 13px; font-weight: 600; color: #64748B; border-bottom: 1px dashed #F1F5F9;">Status Payout</td>
                            <td style="padding: 8px 0; font-size: 13px; font-weight: 800; color: #039855; text-align: right; border-bottom: 1px dashed #F1F5F9;">{{ strtoupper($payout->status) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0 0; font-size: 13px; font-weight: 600; color: #64748B;">Nominal Ditransfer</td>
                            <td style="padding: 12px 0 0; font-size: 20px; font-weight: 800; color: #FF6B00; text-align: right;">Rp {{ number_format((float) $payout->amount, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="btn-container">
                    <a href="{{ $proofUrl }}" class="btn">Unduh Bukti Transfer</a>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Terima kasih atas kerja keras Anda menghasilkan penjualan!<br>
            Kelola saldo dan ajukan payout baru di <a href="{{ config('app.url') }}">Dashboard Afiliator</a>.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
