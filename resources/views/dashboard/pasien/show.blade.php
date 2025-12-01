@extends('dashboard.layouts.main')

@section('content')
<div class="container">
    <div class="card mt-3 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">
                Riwayat Pasien: {{ $patient->nama }}
            </h5>

            <p><strong>NIK:</strong> {{ $patient->no_ktp }}</p>
            <p><strong>No HP:</strong> {{ $patient->no_hp }}</p>
            <p><strong>Jenis Kelamin:</strong> {{ $patient->jenis_kelamin }}</p>
            <p><strong>Tgl Lahir:</strong> {{ $patient->tgl_lahir }}</p>

            <hr>

            <h6 class="mb-3">Riwayat Kunjungan</h6>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>No Antrian</th>
                            <th>Poli</th>
                            <th>Tgl Antrian</th>
                            <th>Pekerjaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $antrian)
                            <tr class="text-center">
                                <td>{{ $antrian->no_antrian }}</td>
                                <td>{{ strtoupper($antrian->poli) }}</td>
                                <td>{{ $antrian->tanggal_antrian }}</td>
                                <td>{{ $antrian->pekerjaan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Belum ada riwayat kunjungan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admin.pasien.index') }}" class="btn btn-secondary btn-sm mt-3">
                &laquo; Kembali ke Data Pasien
            </a>
        </div>
    </div>
</div>
@endsection
