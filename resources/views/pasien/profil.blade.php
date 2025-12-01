<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Pasien</title>

    {{-- gunakan stylesheet utama jika ada --}}
    <link rel="stylesheet" href="{{ asset('css/puskesmas-theme.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container py-4">

    {{-- HEADER + TOMBOL --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Profil Pasien</h2>

        <a href="{{ route('pasien.kartu', $pasien->id) }}"
           target="_blank"
           class="btn btn-primary btn-sm">
            Cetak Kartu Pasien
        </a>
    </div>

    {{-- KARTU DATA PASIEN --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <h4 class="card-title mb-3">
                {{ $pasien->nama }}
            </h4>

            <p class="mb-1"><strong>NIK:</strong> {{ $pasien->no_ktp }}</p>
            <p class="mb-1"><strong>No HP:</strong> {{ $pasien->no_hp }}</p>
            <p class="mb-1"><strong>Jenis Kelamin:</strong> {{ ucfirst($pasien->jenis_kelamin) }}</p>
            <p class="mb-1"><strong>Tanggal Lahir:</strong> {{ $pasien->tgl_lahir }}</p>
            <p class="mb-0"><strong>Alamat:</strong> {{ $pasien->alamat }}</p>

        </div>
    </div>

    {{-- RIWAYAT --}}
    <h5 class="mb-2">Riwayat Antrian Terakhir</h5>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-striped table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No Antrian</th>
                        <th>Poli</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $item)
                        <tr>
                            <td>{{ $item->no_antrian }}</td>
                            <td>{{ strtoupper($item->poli) }}</td>
                            <td>{{ $item->tanggal_antrian }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-3">
                                Belum ada riwayat antrian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>
