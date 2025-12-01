<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Antrian - {{ $antrian->no_antrian }}</title>

    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "Helvetica Neue", Arial, sans-serif;
            font-size: 10px;
        }

        .ticket-wrapper {
            width: 58mm;
            margin: 0 auto;
            padding: 6mm 4mm 8mm;
            text-align: center;
        }

        .logo {
            margin-bottom: 4px;
        }

        .logo img {
            max-width: 22mm;
            height: auto;
        }

        .clinic-name {
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
        }

        .ticket-title {
            margin-top: 2px;
            font-size: 9px;
            letter-spacing: 0.5px;
        }

        hr {
            border: 0;
            border-top: 1px dashed #999;
            margin: 6px 0;
        }

        .queue-number {
            font-size: 32px;
            font-weight: 800;
            margin: 4px 0 0;
        }

        .queue-label {
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .poli-badge {
            display: inline-block;
            font-size: 9px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            border: 1px solid #000;
            margin-top: 2px;
            text-transform: uppercase;
        }

        .info-block {
            margin-top: 4px;
            text-align: left;
            font-size: 9px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .info-label {
            font-weight: 600;
        }

        .info-value {
            text-align: right;
            max-width: 35mm;
        }

        .qr-wrapper {
            margin-top: 6px;
            margin-bottom: 2px;
        }

        .qr-wrapper img {
            width: 24mm;
            height: 24mm;
        }

        .qr-caption {
            font-size: 8px;
            color: #555;
            margin-top: 2px;
        }

        .ticket-note {
            font-size: 8px;
            margin-top: 4px;
            line-height: 1.3;
        }

        .thanks {
            margin-top: 4px;
            font-size: 9px;
            font-weight: 600;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body onload="window.print()">

@php
    // URL untuk STATUS ANTRIAN
    $statusUrl = route('antrian.status', $antrian->id);

    // URL Google Form survei kepuasan (GANTI dengan punyamu sendiri)
    $surveyUrl = 'https://docs.google.com/forms/d/e/1FAIpQLSdoPhLJcn4n4TdPje5dvg9AiBh-uVzu58DHW6ZvML6wjLlsgg/viewform?usp=header';

    // API QR (tidak perlu composer / library)
    $statusQrSrc = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' . urlencode($statusUrl);
    $surveyQrSrc = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' . urlencode($surveyUrl);
@endphp

<div class="ticket-wrapper">

    {{-- LOGO PUSKESMAS --}}
    <div class="logo">
        <img src="{{ asset('assets/img/logo-puskesmas.png') }}" alt="Logo Puskesmas">
    </div>

    {{-- NAMA PUSKESMAS --}}
    <div class="clinic-name">
        PUSKESMAS KALIGANDU
    </div>
    <div class="ticket-title">
        TIKET ANTRIAN ONLINE
    </div>

    <hr>

    {{-- NOMOR ANTRIAN --}}
    <div class="queue-label">Nomor Antrian</div>
    <div class="queue-number">{{ $antrian->no_antrian }}</div>
    <div class="poli-badge">
        {{ strtoupper($antrian->poli) }}
    </div>

    <hr>

    {{-- DETAIL SINGKAT --}}
    <div class="info-block">
        <div class="info-row">
            <span class="info-label">Nama</span>
            <span class="info-value">{{ $antrian->nama }}</span>
        </div>

        <div class="info-row">
            <span class="info-label">Tanggal</span>
            <span class="info-value">
                {{ \Carbon\Carbon::parse($antrian->tanggal_antrian)->translatedFormat('d F Y') }}
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Waktu</span>
            <span class="info-value">
                {{ \Carbon\Carbon::parse($antrian->created_at)->format('H:i') }} WIB
            </span>
        </div>
    </div>

    {{-- QR STATUS ANTRIAN --}}
    <div class="qr-wrapper">
        <img src="{{ $statusQrSrc }}" alt="QR Status Antrian">
        <div class="qr-caption">Scan untuk cek status antrian Anda.</div>
    </div>

    {{-- QR SURVEY KEPUASAN --}}
    <div class="qr-wrapper">
        <img src="{{ $surveyQrSrc }}" alt="QR Survey Kepuasan">
        <div class="qr-caption">Scan untuk mengisi Survey Kepuasan Pasien.</div>
    </div>

    {{-- CATATAN --}}
<div class="ticket-note" style="
    margin-top: 10px;
    font-size: 9px;
    line-height: 1.4;
    text-align: center;
">
    Harap datang beberapa menit sebelum nomor Anda dipanggil.<br>
    Simpan tiket ini dan tunjukkan kepada petugas saat diminta.
</div>

{{-- TERIMA KASIH --}}
<div style="
    margin-top: 8px;
    font-size: 10px;
    font-weight: 600;
    text-align: center;
">
    Terima kasih telah menggunakan layanan antrian<br>
    <strong>Puskesmas Kaligandu</strong>.
</div>

{{-- FOOTER KECIL --}}
<div style="
    margin-top: 6px;
    font-size: 8px;
    color: #666;
    text-align: center;
">
    Semoga layanan kami bermanfaat bagi Anda.
</div>

</body>
</html>
