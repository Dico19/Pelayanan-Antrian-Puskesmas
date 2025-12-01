<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Pasien</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }
        .kartu {
            width: 320px;      /* kira-kira ukuran kartu */
            height: 200px;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 10px;
            margin: 10px auto;
            display: flex;
            flex-direction: row;
            gap: 10px;
        }
        .info {
            flex: 1;
        }
        .info h3 {
            margin: 0 0 4px;
            font-size: 13px;
        }
        .info p {
            margin: 2px 0;
        }
        .qr {
            width: 110px;
            text-align: center;
        }
        .qr img {
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body onload="window.print()">

<div class="kartu">
    <div class="info">
        <h3>PUSKESMAS KALIGANDU</h3>
        <p><strong>Kartu Pasien</strong></p>
        <p><strong>Nama:</strong> {{ $pasien->nama }}</p>
        <p><strong>NIK:</strong> {{ $pasien->no_ktp }}</p>
        <p><strong>No HP:</strong> {{ $pasien->no_hp }}</p>
        <p><strong>Tgl Lahir:</strong> {{ $pasien->tgl_lahir }}</p>
    </div>

    <div class="qr">
        {{-- QR mengarah ke halaman profil/riwayat pasien --}}
        <img
            src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(route('pasien.profil', $pasien->id)) }}"
            alt="QR Kartu Pasien"
        >
        <small>Scan untuk lihat profil & riwayat</small>
    </div>
</div>

</body>
</html>
