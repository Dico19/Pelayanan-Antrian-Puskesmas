@extends('dashboard.layouts.main')

{{-- class khusus untuk body (kalau mau di-style per halaman) --}}
@section('body-class', 'page-pasien')

{{-- HAPUS class "dashboard" di <section> untuk halaman ini --}}
@section('section-class', '')

@section('content')
<div class="container">
    <div class="card mt-3 mb-3">
        <div class="card-body">

            {{-- HEADER: Judul + Form Pencarian --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="card-title mb-0">Data Pasien</h5>

                <form method="GET"
                      action="{{ route('admin.pasien.index') }}"
                      class="d-flex gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control form-control-sm"
                        placeholder="Cari nama / NIK..."
                    >
                    <button type="submit" class="btn btn-primary btn-sm">
                        Cari
                    </button>
                </form>
            </div>

            {{-- TABEL DATA PASIEN --}}
            <div class="table-responsive">
                <table class="table table-bordered mb-2">
                    <thead>
                        <tr class="text-center">
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>No HP</th>
                            <th>Jenis Kelamin</th>
                            <th>Tgl Lahir</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($patients as $patient)
                            <tr class="text-center">
                                <td>{{ $patient->nama }}</td>
                                <td>{{ $patient->no_ktp }}</td>
                                <td>{{ $patient->no_hp }}</td>
                                <td>{{ $patient->jenis_kelamin }}</td>
                                <td>{{ $patient->tgl_lahir }}</td>
                                <td>
                                    <a href="{{ route('admin.pasien.show', $patient) }}"
                                       class="btn btn-info btn-sm">
                                        Riwayat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Belum ada data pasien.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION + INFO "MENAMPILKAN ..." --}}
            @if ($patients->count())
                <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap gap-2">
                    <div class="small text-muted">
                        Menampilkan
                        <strong>{{ $patients->firstItem() }}</strong>
                        sampai
                        <strong>{{ $patients->lastItem() }}</strong>
                        dari
                        <strong>{{ $patients->total() }}</strong>
                        data
                    </div>

                    <div>
                        {{-- pakai view pagination bootstrap biar cocok dengan Bootstrap --}}
                        {{ $patients->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
