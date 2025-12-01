<div class="pk-queue-status py-5">
    <div class="container">
        <div class="row justify-content-center">
            {{-- lebar kartu sedikit lebih besar di desktop --}}
            <div class="col-xl-8 col-lg-9">

                {{-- wire:poll supaya data orang di depan & estimasi terus update --}}
                <div class="pk-status-card shadow-sm" wire:poll.10s>

                    {{-- HEADER --}}
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">

                        <div>
                            <h2 class="pk-status-title mb-1">Status Antrian Anda</h2>
                            <p class="pk-status-subtitle mb-0">
                                Terima kasih sudah mendaftar. Mohon menunggu panggilan sesuai nomor antrian.
                            </p>
                        </div>

                        {{-- BADGE NOMOR ANTRIAN --}}
                        <div class="pk-ticket-badge text-center">
                            <span class="badge-label d-block mb-1">Nomor Antrian</span>
                            <div class="badge-number mb-1">
                                {{ $antrian->no_antrian ?? $antrian->nomor_antrian ?? '-' }}
                            </div>
                            <span class="badge-poli">
                                {{ strtoupper($antrian->poli ?? 'POLI') }}
                            </span>
                        </div>
                    </div>

                    {{-- GARIS PEMBATAS TIPIS --}}
                    <hr class="my-4">

                    {{-- INFO GRID --}}
                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="pk-info-item">
                                <span class="info-label">Poli</span>
                                <span class="info-value text-capitalize">
                                    {{ $antrian->poli ?? 'lansia & disabilitas' }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="pk-info-item">
                                <span class="info-label">Orang di depan Anda</span>
                                <span class="info-value">
                                    {{ $orangDiDepan }} orang
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="pk-info-item">
                                <span class="info-label">Estimasi waktu tunggu</span>
                                <span class="info-value">
                                    @if($estimasiMenit > 0)
                                        ± {{ $estimasiMenit }} menit
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- STATUS + LOGIKA TANGGAL & JAM --}}
                        <div class="col-md-6">
                            <div class="pk-info-item pk-info-status d-flex align-items-center gap-2">
                                <span class="dot-live"></span>
                                <div>
                                    <span class="info-label d-block">Status</span>

                                    @php
                                        use Carbon\Carbon;

                                        // tanggal antrian dari DB
                                        $tglAntrian = isset($antrian->tanggal_antrian)
                                            ? Carbon::parse($antrian->tanggal_antrian)
                                            : null;

                                        // ambil angka dari no antrian: U1, G2, dst → 1, 2, ...
                                        $rawNo  = $antrian->no_antrian ?? $antrian->nomor_antrian ?? null;
                                        $noUrut = $rawNo ? (int) preg_replace('/\D/', '', $rawNo) : null;

                                        // default: tidak ada teks jam
                                        $perkiraanJamText = null;

                                        if ($tglAntrian && $tglAntrian->isFuture() && $noUrut) {
                                            $jamBuka   = 8;   // buka 08:00
                                            $menitSlot = 15;  // 15 menit per pasien

                                            // mulai dari 08:00 di hari layanan
                                            $startTime    = $tglAntrian->copy()->setTime($jamBuka, 0);
                                            $perkiraanJam = $startTime->copy()->addMinutes(($noUrut - 1) * $menitSlot);

                                            // batasi maksimal sampai jam 17:00
                                            $jamTutup = $tglAntrian->copy()->setTime(17, 0);
                                            if ($perkiraanJam->lessThanOrEqualTo($jamTutup)) {
                                                $perkiraanJamText = $perkiraanJam->format('H:i') . ' WIB';
                                            }
                                        }
                                    @endphp

                                    {{-- TAMPILKAN STATUS --}}
                                    @if($tglAntrian && $tglAntrian->isToday())
                                        {{-- HARI INI: tanpa jam, hanya info akan dipanggil / menunggu --}}
                                        <span class="info-value">
                                            @if ($orangDiDepan === 0)
                                                Antrian Anda akan segera dipanggil.
                                            @else
                                                Sedang menunggu panggilan.
                                            @endif
                                        </span>

                                    @elseif($tglAntrian && $tglAntrian->isFuture())
                                        {{-- HARI BESOK / LUSA DLL: tampilkan tanggal + jam perkiraan --}}
                                        <span class="info-value d-block">
                                            Jadwal antrian Anda pada
                                            <strong>{{ $tglAntrian->translatedFormat('l, d F Y') }}</strong>
                                        </span>

                                        @if($perkiraanJamText)
                                            <small class="text-muted d-block">
                                                Perkiraan giliran sekitar
                                                <strong>{{ $perkiraanJamText }}</strong>
                                            </small>
                                        @endif

                                    @else
                                        {{-- fallback kalau tanggal tidak ada / sudah lewat --}}
                                        <span class="info-value">
                                            Status antrian tidak tersedia.
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                             {{-- ALERT / NOTE --}}
                    <div class="pk-status-alert mt-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Silakan menunggu di ruang tunggu. Halaman ini akan memperbarui data secara otomatis.
                    </div>

                    {{-- FOOTER TIPS --}}
                    <div class="pk-status-footer mt-3 text-center text-md-start">
                        <small>
                            Tips: Pastikan Anda tidak jauh dari ruang tunggu agar tidak terlewat saat nomor Anda dipanggil.
                        </small>
                    </div>

                    {{-- NOTE UNTUK CETAK --}}
<div class="mt-4 mb-2 text-center">
    <p style="
        font-size: 14px;
        color: #cbd5e1;
        opacity: 0.9;
        margin-bottom: 6px;
    ">
        Silakan cetak tiket antrian sebagai bukti dan tunjukkan kepada petugas saat dipanggil.
    </p>
</div>

{{-- TOMBOL CETAK --}}
<div class="text-center mt-1">
    <a href="{{ route('antrian.tiket', $antrian->id) }}"
       style="
            background: #2563eb;
            color: white;
            padding: 10px 22px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
       ">
        🖨️ Cetak Tiket Antrian
    </a>
</div>


                </div> {{-- end .pk-status-card --}}
                </div>

            </div>
        </div>
    </div>
</div>
