@extends('layouts.main')
@include('partials.navbar')

@section('content')
<section id="antrian" class="py-5" style="margin-top: 90px;">
    <div class="container">

        {{-- JUDUL HALAMAN --}}
        <h2 class="text-center fw-bold mb-2 text-uppercase">
            ANTRIANKU
        </h2>
        <p class="text-center text-muted mb-4">
            Cek, ubah, atau hapus antrian Anda dengan memasukkan NIK (No KTP) 
            yang digunakan saat pendaftaran.
        </p>

        {{-- TOMBOL AMBIL ANTRIAN BARU (DI TENGAH) --}}
        <div class="d-flex justify-content-center mb-4">
            <a href="{{ url('/antrian') }}"
               class="btn btn-primary d-inline-flex align-items-center px-4 py-2"
               style="font-size: 15px; border-radius: 999px;">
                <i class="bi bi-clipboard-plus me-2"></i>
                Ambil Antrian Baru
            </a>
        </div>

        {{-- CARD FORM CARI NIK --}}
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">

                        {{-- ALERT JIKA NIK TIDAK DITEMUKAN --}}
                        @if (session('nik_not_found'))
                            <div class="alert alert-warning mb-4" role="alert">
                                {{ session('nik_not_found') }}
                            </div>
                        @endif

                        {{-- HEADER KECIL DI DALAM CARD --}}
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 46px; height: 46px; background: rgba(13,110,253,0.12);">
                                <i class="bi bi-search text-primary fs-5"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-semibold">Cari Antrian Anda</h5>
                                <small class="text-muted">
                                    Masukkan NIK (No KTP) yang digunakan saat mengambil nomor antrian.
                                </small>
                            </div>
                        </div>

                        {{-- FORM --}}
                        <form action="{{ route('antrian.cari.proses') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="no_ktp" class="form-label fw-semibold">NIK (No KTP)</label>
                                <input
                                    type="text"
                                    name="no_ktp"
                                    id="no_ktp"
                                    class="form-control @error('no_ktp') is-invalid @enderror"
                                    placeholder="3273xxxxxxxxxxxx"
                                    value="{{ old('no_ktp') }}"
                                >
                                @error('no_ktp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit"
                                        class="btn btn-primary px-4 d-inline-flex align-items-center"
                                        style="border-radius: 999px;">
                                    <i class="bi bi-search me-2"></i>
                                    Cari Antrian
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
