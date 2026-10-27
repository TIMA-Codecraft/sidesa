<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $letter->template->name }} - {{ $letter->letter_number ?? $letter->request_number }}</title>
    <style>
        @page {
            margin: 2cm 2.5cm 2cm 2.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        .header {
            text-align: center;
            position: relative;
            margin-bottom: 15px;
        }
        .header h3 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 0;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header .address {
            font-size: 10pt;
            margin-top: 5px;
            font-style: italic;
        }
        .double-line {
            border: 0;
            border-top: 3px double #000;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .title-block {
            text-align: center;
            margin-bottom: 25px;
        }
        .title-block .title {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .title-block .number {
            font-size: 11pt;
            margin-top: 3px;
        }
        .content {
            text-align: justify;
        }
        .content p {
            margin-bottom: 12px;
            text-indent: 30px;
        }
        .content table {
            width: 100%;
            margin-top: 8px;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .content table td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 11pt;
        }
        .signature-block {
            margin-top: 35px;
            width: 100%;
        }
        .signature-block .sign-box {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-block .sign-date {
            margin-bottom: 5px;
        }
        .signature-block .sign-role {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .signature-block .qr-container {
            margin: 10px auto;
            text-align: center;
        }
        .signature-block .qr-container img {
            width: 85px;
            height: 85px;
        }
        .signature-block .tte-note {
            font-size: 8pt;
            color: #444;
            margin-top: 4px;
            line-height: 1.2;
            font-style: italic;
        }
        .signature-block .sign-name {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 10px;
        }
        .signature-block .sign-nip {
            font-size: 10pt;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <div class="header">
        @php
            $kopLogo = village_logo_base64();
        @endphp
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 0;">
            <tr>
                @if($kopLogo)
                    <td style="width: 80px; vertical-align: middle; text-align: center; padding-right: 12px;">
                        <img src="{{ $kopLogo }}" style="width: 70px; height: auto; max-height: 80px;" alt="Logo Desa">
                    </td>
                @endif
                <td style="vertical-align: middle; text-align: center;">
                    <h3>PEMERINTAH KABUPATEN {{ strtoupper($regencyName) }}</h3>
                    <h3>KECAMATAN {{ strtoupper($districtName) }}</h3>
                    <h2>DESA {{ strtoupper(village_name(withPrefix: false)) }}</h2>
                    <div class="address">
                        {{ $villageAddress }} | Kode Pos: {{ $postalCode }}<br>
                        Telp: {{ $villagePhone }} | Email: {{ $villageEmail }}
                    </div>
                </td>
                @if($kopLogo)
                    <td style="width: 80px;"></td>
                @endif
            </tr>
        </table>
        <div class="double-line"></div>
    </div>

    <!-- JUDUL SURAT -->
    <div class="title-block">
        <div class="title">{{ $letter->template->name }}</div>
        <div class="number">Nomor: {{ $letter->letter_number ?? $letter->request_number }}</div>
    </div>

    <!-- ISI SURAT -->
    <div class="content">
        {!! $content !!}
    </div>

    <!-- BLOK TANDA TANGAN (TTE) -->
    <div class="signature-block clearfix">
        <div class="sign-box">
            <div class="sign-date">{{ $villageName }}, {{ $dateFormatted }}</div>
            <div class="sign-role">Kepala Desa {{ village_name(withPrefix: false) }}</div>
            
            <div class="qr-container">
                <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" alt="QR Verifikasi TTE">
                <div class="tte-note">
                    @if($isCertifiedTte ?? false)
                        <b>TTE Tersertifikasi BSrE BSSN</b><br>
                        UU ITE No. 1/2024 &amp; PP No. 71/2019<br>
                        <span style="font-family: monospace; font-size: 6.5pt;">Hash: {{ $formattedDocHash ?? '-' }}</span>
                    @else
                        Ditandatangani secara elektronik (TTE)<br>
                        Scan QR Code untuk cek keaslian surat
                    @endif
                </div>
            </div>

            <div class="sign-name">{{ $kadesName }}</div>
            <div class="sign-nip">NIP. {{ $kadesNip }}</div>
        </div>
    </div>
</body>
</html>
