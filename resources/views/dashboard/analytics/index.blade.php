@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid px-4">

    <h2 class="fw-bold text-primary-custom">Analitik Layanan Antrian</h2>
<p class="subtext-muted mb-4">Ringkasan data antrian dan pola kunjungan pasien</p>

    <div class="row g-3 mb-4">

        {{-- Total Antrian --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <small>Total Antrian Hari Ini</small>
                        <h3>{{ $totalToday }}</h3>
                    </div>
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pasien Unik --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <small>Pasien Unik Hari Ini</small>
                        <h3>{{ $uniquePatientsToday }}</h3>
                    </div>
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="bi bi-person-check-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rata-rata Waktu Tunggu --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <small>Rata-rata Waktu Tunggu</small>
                        <h3>{{ $avgWait }} menit</h3>
                    </div>
                    <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="bi bi-clock-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jam Tersibuk --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <small>Jam Tersibuk Hari Ini</small>
                        <h3>{{ $busiestHour ?? '-' }}</h3>
                    </div>
                    <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" style="width:45px; height:45px;">
                        <i class="bi bi-lightning-charge-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Grafik --}}
    <div class="row g-3">

        {{-- Grafik Poli --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3">
                <h6>Jumlah Pasien per Poli (Hari Ini)</h6>
                <canvas id="chartPoli"></canvas>
            </div>
        </div>

        {{-- Grafik Jam --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3">
                <h6>Jam Kunjungan Tersibuk (Hari Ini)</h6>
                <canvas id="chartJam"></canvas>
            </div>
        </div>

        {{-- Grafik Harian --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3">
                <h6>Tren Harian (7 Hari Terakhir)</h6>
                <canvas id="chartHarian"></canvas>
            </div>
        </div>

        {{-- Grafik Bulanan --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3">
                <h6>Tren Bulanan (6 Bulan Terakhir)</h6>
                <canvas id="chartBulanan"></canvas>
            </div>
        </div>

    </div>

</div>
@endsection

@section('script')
<script>
    // Data dari controller (array of object)
    const perPoli   = @json($perPoli);
    const perJam    = @json($perJam);
    const daily     = @json($dailyTrend);
    const monthly   = @json($monthlyTrend);

    // 1. Grafik Jumlah Pasien per Poli
    if (perPoli.length) {
        new Chart(document.getElementById('chartPoli'), {
            type: 'bar',
            data: {
                labels: perPoli.map(item => item.poli),
                datasets: [{
                    label: 'Pasien',
                    data: perPoli.map(item => item.total),
                    backgroundColor: 'rgba(54, 162, 235, .5)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    // 2. Grafik Jam Kunjungan Tersibuk
    if (perJam.length) {
        new Chart(document.getElementById('chartJam'), {
            type: 'bar',
            data: {
                labels: perJam.map(item => String(item.jam).padStart(2, '0') + ':00'),
                datasets: [{
                    label: 'Pasien',
                    data: perJam.map(item => item.total),
                    backgroundColor: 'rgba(255, 206, 86, .5)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    // 3. Grafik Tren Harian
    if (daily.length) {
        new Chart(document.getElementById('chartHarian'), {
            type: 'line',
            data: {
                labels: daily.map(item => item.tanggal),
                datasets: [{
                    label: 'Pasien',
                    data: daily.map(item => item.total),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: .4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

    // 4. Grafik Tren Bulanan
    if (monthly.length) {
        new Chart(document.getElementById('chartBulanan'), {
            type: 'line',
            data: {
                labels: monthly.map(item => item.bulan),
                datasets: [{
                    label: 'Pasien',
                    data: monthly.map(item => item.total),
                    borderColor: '#6610f2',
                    backgroundColor: 'rgba(102, 16, 242, 0.1)',
                    tension: .4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }
</script>
@endsection
