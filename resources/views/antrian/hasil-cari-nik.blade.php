@extends('layouts.main')
@include('partials.navbar')

@section('content')
<section id="hasil-antrian" class="py-5" style="margin-top: 90px;">
    <div class="container">

        {{-- JUDUL --}}
        <h2 class="text-center fw-bold mb-2 text-uppercase">
            HASIL PENCARIAN ANTRIAN
        </h2>

        {{-- INFO NIK + TOMBOL KEMBALI --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 mb-3">
            <div class="mb-2 mb-md-0">
                <span class="fw-semibold">NIK:</span>
                <span class="ms-1">{{ $nik }}</span>
            </div>

            <a href="{{ route('antrian.cari') }}" class="btn btn-outline-secondary btn-sm">
                ← Cari NIK lain
            </a>
        </div>

        {{-- TABEL HASIL --}}
        <div class="table-responsive">
            <table id="tabel-hasil-antrian"
                   class="table table-bordered table-hover align-middle mb-0">
                <thead class="small text-uppercase">
                    <tr class="text-center">
                        <th style="width: 60px;">No</th>
                        <th style="width: 120px;">No Antrian</th>
                        <th>Nama</th>
                        <th style="width: 150px;">Poli</th>
                        <th style="width: 130px;">Tgl. Antrian</th>
                        <th style="width: 210px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($antrians as $index => $antrian)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $antrian->no_antrian }}</td>
                            <td>{{ $antrian->nama }}</td>
                            <td class="text-center">{{ $antrian->poli }}</td>
                            <td class="text-center">{{ $antrian->tanggal_antrian }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- EDIT --}}
                                    <a href="{{ route('antrian.edit', $antrian->id) }}"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    {{-- STATUS --}}
                                    <a href="{{ route('antrian.status', $antrian->id) }}"
                                       class="btn btn-info btn-sm text-white">
                                        Status
                                    </a>

                                    {{-- HAPUS --}}
                                    <form action="{{ route('antrian.destroy', $antrian->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus antrian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</section>

{{-- STYLING KHUSUS HALAMAN INI --}}
<style>
    /* Biar cell enak dibaca */
    #tabel-hasil-antrian th,
    #tabel-hasil-antrian td {
        vertical-align: middle;
        font-size: 0.92rem;
    }

    /* Group tombol kecil tapi rapi */
    #tabel-hasil-antrian .btn-sm {
        padding: 0.25rem 0.6rem;
        font-size: 0.78rem;
    }

    /* Hover row: sesuaikan warna teks antara dark & light mode */
    .dark-mode #tabel-hasil-antrian tbody tr:hover td {
        color: #ffffff !important;           /* tetap putih di mode gelap */
        background-color: rgba(255,255,255,0.04);
    }

    body:not(.dark-mode) #tabel-hasil-antrian tbody tr:hover td {
        color: #000000 !important;           /* tetap hitam di mode terang */
    }
</style>
@endsection
