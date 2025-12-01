@extends('dashboard.layouts.main')

@section('content')
    <div class="container">

        {{-- Pesan sukses reset / info lainnya --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mt-3" style="height: 800px">
            <div class="card-body">
                <div class="card-title">Dashboard</div>

                <div class="col-xxl col-xl-12">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                Selamat Datang <span>| {{ auth()->user()->name }}</span>
                            </h5>
                            <div class="d-flex align-items-center">
                                <h2>PELAYANAN ANTRIAN ONLINE PUSKESMAS KALIGANDU</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-title">Antrian Masuk</div>

                <div class="row">
                    <div class="col-xxl col-md-3">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Poli Umum</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bxs-user-plus"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $poliUmum }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl col-md-3">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Poli Gigi</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bx-user-circle"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $poliGigi }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl col-md-3">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Poli THT</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bxs-user-voice"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $poliTht }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl col-md-3">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Polia Lansa & Disabilitas</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bxs-group"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $poliLansia }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Poli Balita</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bxs-group"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $poliBalita }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Poli KIA & KB</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bxs-group"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $poliKia }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Poli Nifas/PNC</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bxs-group"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $poliNifas }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Tombol Reset Antrian Hari Ini (rapi di bawah, rata kanan) --}}
    <div class="container my-3">
        <div class="d-flex justify-content-end">
            <form
                method="POST"
                action="{{ route('admin.dashboard.reset-antrian') }}"
                onsubmit="return confirm('Yakin ingin me-reset semua antrian HARI INI? Data akan dipindahkan ke Riwayat.');"
            >
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Reset Antrian Hari Ini
                </button>
            </form>
        </div>
    </div>
@endsection
