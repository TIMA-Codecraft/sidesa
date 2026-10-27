<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Salinan Kartu Keluarga - {{ $family->family_card_number }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm 1.2cm 1cm 1.2cm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
            line-height: 1.25;
            color: #111;
        }
        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }
        .header .gov-title {
            font-size: 9.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0;
        }
        .header h1 {
            font-size: 15pt;
            font-weight: 800;
            letter-spacing: 1.5px;
            margin: 3px 0 2px 0;
            text-transform: uppercase;
        }
        .header .kk-number {
            font-size: 12pt;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            letter-spacing: 2px;
            margin: 0;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 8px;
            font-size: 8pt;
        }
        .meta-table td {
            vertical-align: top;
            padding: 1px 2px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 7.5pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #333;
            padding: 3.5px 3px;
            text-align: left;
        }
        .data-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-center {
            text-align: center !important;
        }
        .text-right {
            text-align: right !important;
        }
        .font-mono {
            font-family: 'Courier New', Courier, monospace;
        }
        .disclaimer-box {
            border: 1px dashed #64748b;
            background-color: #f8fafc;
            padding: 5px 8px;
            margin-top: 6px;
            margin-bottom: 8px;
            font-size: 7pt;
            color: #334155;
            line-height: 1.3;
        }
        .disclaimer-box strong {
            color: #0f172a;
        }
        .signatures {
            width: 100%;
            margin-top: 5px;
            font-size: 8pt;
        }
        .signatures td {
            vertical-align: top;
            text-align: center;
            width: 50%;
        }
        .signature-space {
            height: 48px;
        }
        .footer-audit {
            margin-top: 6px;
            padding-top: 4px;
            border-top: 1px dotted #94a3b8;
            font-size: 6.5pt;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- Header Dokumen -->
    <div class="header">
        <div class="gov-title">
            PEMERINTAH KABUPATEN {{ strtoupper($regencyName) }} &bull; KECAMATAN {{ strtoupper($districtName) }}
        </div>
        <h1>SALINAN KARTU KELUARGA (REGISTER DESA)</h1>
        <div class="kk-number">No. {{ $family->family_card_number }}</div>
    </div>

    <!-- Informasi Biodata Header -->
    <table class="meta-table">
        <tr>
            <td style="width: 17%;">Nama Kepala Keluarga</td>
            <td style="width: 1%;">:</td>
            <td style="width: 32%;"><strong>{{ $headOfFamily ? strtoupper($headOfFamily->name) : 'BELUM DITENTUKAN' }}</strong></td>
            
            <td style="width: 16%;">Kecamatan</td>
            <td style="width: 1%;">:</td>
            <td style="width: 33%;">{{ strtoupper($districtName) }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ strtoupper($family->address ?: 'DUSUN ' . ($family->hamlet ?: $villageName)) }}</td>
            
            <td>Kabupaten / Kota</td>
            <td>:</td>
            <td>{{ strtoupper($regencyName) }}</td>
        </tr>
        <tr>
            <td>RT / RW</td>
            <td>:</td>
            <td>{{ str_pad($family->rt, 3, '0', STR_PAD_LEFT) }} / {{ str_pad($family->rw, 3, '0', STR_PAD_LEFT) }}</td>
            
            <td>Kode Pos</td>
            <td>:</td>
            <td>{{ $postalCode }}</td>
        </tr>
        <tr>
            <td>Desa / Kelurahan</td>
            <td>:</td>
            <td>{{ strtoupper($villageName) }}</td>
            
            <td>Provinsi</td>
            <td>:</td>
            <td>{{ strtoupper($provinceName) }}</td>
        </tr>
    </table>

    <!-- Tabel 1: Data Identitas & Sosial Warga -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Lengkap</th>
                <th style="width: 110px;">NIK</th>
                <th style="width: 70px;">Jenis Kelamin</th>
                <th style="width: 85px;">Tempat Lahir</th>
                <th style="width: 65px;">Tgl Lahir</th>
                <th style="width: 65px;">Agama</th>
                <th style="width: 85px;">Pendidikan</th>
                <th>Jenis Pekerjaan</th>
                <th style="width: 35px;">Gol. Darah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $index => $member)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ strtoupper($member->name) }}</strong></td>
                    <td class="text-center font-mono">{{ $member->nik }}</td>
                    <td class="text-center">{{ $member->gender === 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</td>
                    <td>{{ strtoupper($member->birth_place ?: '-') }}</td>
                    <td class="text-center">{{ $member->birth_date ? $member->birth_date->format('d-m-Y') : '-' }}</td>
                    <td class="text-center">{{ strtoupper($member->religion ?: '-') }}</td>
                    <td>{{ strtoupper($member->education_level ?: '-') }}</td>
                    <td>{{ strtoupper($member->occupation ?: '-') }}</td>
                    <td class="text-center">{{ strtoupper($member->blood_type ?: '-') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 10px; font-style: italic; color: #64748b;">
                        Belum ada data anggota keluarga yang terdaftar pada Kartu Keluarga ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tabel 2: Hubungan Keluarga, Status & Orang Tua -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 100px;">Status Perkawinan</th>
                <th style="width: 130px;">Status Hubungan Dalam Keluarga</th>
                <th style="width: 85px;">Kewarganegaraan</th>
                <th style="width: 90px;">No. Paspor / Dokumen</th>
                <th>Nama Ayah</th>
                <th>Nama Ibu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $index => $member)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ strtoupper($member->marital_status ?: '-') }}</td>
                    <td class="text-center"><strong>{{ strtoupper($member->family_relationship_status ?: '-') }}</strong></td>
                    <td class="text-center">{{ strtoupper($member->nationality ?: 'WNI') }}</td>
                    <td class="text-center font-mono">-</td>
                    <td>{{ strtoupper($member->father_name ?: '-') }}</td>
                    <td>{{ strtoupper($member->mother_name ?: '-') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 10px; font-style: italic; color: #64748b;">
                        Belum ada data anggota keluarga.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Kotak Penyangkal Resmi (Legal Disclaimer Sesuai UU Adminduk) -->
    <div class="disclaimer-box">
        <strong>PERHATIAN / PENYANGKAL RESMI (UU No. 24/2013 & Permendagri No. 47/2016):</strong><br>
        Dokumen ini merupakan <strong>Salinan Biodata Keluarga Register Kependudukan Desa</strong> yang bersumber dari Pangkalan Data Kependudukan Pemerintah Desa {{ village_name(withPrefix: false) }}. 
        Dokumen ini <u>BUKAN</u> merupakan pengganti Kartu Keluarga Asli bertanda tangan elektronik resmi yang diterbitkan oleh Dinas Kependudukan dan Pencatatan Sipil. 
        Dokumen ini sah digunakan untuk keperluan administrasi internal pelayanan desa, verifikasi program perlindungan sosial/bantuan desa, dan registrasi arsip warga.
    </div>

    <!-- Tanda Tangan -->
    <table class="signatures">
        <tr>
            <td>
                <div>Tanda Tangan/Cap Jempol</div>
                <div><strong>Kepala Keluarga</strong></div>
                <div class="signature-space"></div>
                <div>( <strong>{{ $headOfFamily ? strtoupper($headOfFamily->name) : '...................................' }}</strong> )</div>
            </td>
            <td>
                <div>{{ $villageName }}, {{ $currentDateIndo }}</div>
                <div><strong>Kepala Desa {{ village_name(withPrefix: false) }}</strong></div>
                <div class="signature-space"></div>
                <div>( <strong>{{ strtoupper($kadesName) }}</strong> )</div>
                @if($kadesNip)
                    <div style="font-size: 7.5pt;">NIP. {{ $kadesNip }}</div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Footer Audit Trail Jejak Digital (Kepatuhan UU PDP No. 27/2022) -->
    <div class="footer-audit">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left;">
                    SiDesa v2.0 &bull; Register Kependudukan Desa {{ village_name(withPrefix: false) }} &bull; Terproteksi UU PDP No. 27/2022
                </td>
                <td style="text-align: right;">
                    Diunduh oleh: <strong>{{ $downloadedByName }}</strong> pada {{ $downloadedAt }}
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
