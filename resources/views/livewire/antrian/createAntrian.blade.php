<!-- Modal -->
<div wire:ignore.self class="modal fade" id="createAntrian" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                {{-- judul beda tergantung step --}}
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    @if ($step == 1)
                        Pilih Poli
                    @elseif($step == 2)
                        Pilih Tanggal
                    @else
                        Form Ambil Antrian
                    @endif
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="close_modal"
                    aria-label="Close"></button>
            </div>

            <form wire:submit.prevent="save">
                <div class="modal-body">

                    {{-- ===================== STEP 1 : PILIH POLI (PREMIUM) ===================== --}}
                    @if ($step == 1)
                        <div class="text-center mb-3">
                            <h5 class="fw-bold mb-1">Pilih Poli / Klinik</h5>
                            <div class="poli-subtitle">
                                Silakan klik salah satu poli untuk mengambil antrian.
                            </div>
                        </div>

                        <div class="poli-grid">
                            @foreach ($listPoli as $kode => $namaPoli)
                                <button type="button"
                                    class="poli-card"
                                    wire:click="pilihPoli('{{ $kode }}')">

                                    <div class="poli-icon">
                                        @switch($kode)
                                            @case('umum')
                                                <i class="fas fa-user-md"></i>
                                                @break

                                            @case('gigi')
                                                <i class="fas fa-tooth"></i>
                                                @break

                                            @case('tht')
                                                <i class="fas fa-ear-listen"></i>
                                                @break

                                            @case('balita')
                                                <i class="fas fa-child"></i>
                                                @break

                                            @case('lansia & disabilitas')
                                                <i class="fas fa-person-cane"></i>
                                                @break

                                            @case('kia & kb')
                                                <i class="fas fa-baby"></i>
                                                @break

                                            @case('nifas/pnc')
                                                <i class="fas fa-hospital-user"></i>
                                                @break

                                            @default
                                                <i class="fas fa-clinic-medical"></i>
                                        @endswitch
                                    </div>

                                    <div class="poli-label">
                                        {{ strtoupper($namaPoli) }}
                                    </div>

                                </button>
                            @endforeach
                        </div>

                    {{-- ===================== STEP 2 : PILIH TANGGAL ===================== --}}
                    @elseif($step == 2)
                        <div class="mb-3">
                            <div class="fw-semibold">Pilih Tanggal Layanan</div>
                            <small class="text-muted">
                                Maksimal 6 hari ke depan. Hari Minggu libur dan tidak bisa dipilih.
                            </small>
                        </div>

                        <div class="tanggal-grid">
                            @foreach ($tanggalPilihan as $item)
                                <button type="button"
                                    class="tanggal-card
                                        {{ $item['is_libur'] ? 'disabled' : '' }}
                                        {{ $tanggal_antrian === $item['date'] ? 'active' : '' }}"
                                    @if(!$item['is_libur'])
                                        wire:click="pilihTanggal('{{ $item['date'] }}')"
                                    @endif
                                    @if($item['is_libur']) disabled @endif>

                                    <div class="hari">{{ $item['hari'] }}</div>
                                    <div class="tanggal">{{ $item['tanggal'] }}</div>
                                    <div class="bulan">{{ $item['bulan_tahun'] }}</div>

                                    @if(!$item['is_libur'])
                                        <div class="jumlah">Antrian: {{ $item['jumlah'] }}</div>
                                    @else
                                        <div class="jumlah text-libur">Libur</div>
                                    @endif
                                </button>
                            @endforeach
                        </div>

                        <div class="mt-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                wire:click="kembaliKePilihPoli">
                                &larr; Ganti Poli
                            </button>
                        </div>

                    {{-- ===================== STEP 3 : FORM ANTRIAN ===================== --}}
                    @else
                        {{-- Info poli & tanggal yang dipilih --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <label class="form-label mb-0">Poli</label>
                                    <div class="fw-semibold">
                                        {{ $listPoli[$poli] ?? '-' }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <label class="form-label mb-0">Tanggal Layanan</label>
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($tanggal_antrian)->format('d-m-Y') }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-1 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    wire:click="kembaliKePilihTanggal">
                                    &larr; Ganti Tanggal
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    wire:click="kembaliKePilihPoli">
                                    &larr; Ganti Poli
                                </button>
                            </div>
                            <input type="hidden" wire:model="poli">
                            <input type="hidden" wire:model="tanggal_antrian">
                            @error('poli') <span class="text-danger">{{ $message }}</span> @enderror
                            @error('tanggal_antrian') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" wire:model="nama" class="form-control">
                            @error('nama')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Alamat</label>
                            <textarea class="form-control" wire:model="alamat" cols="20"></textarea>
                            @error('alamat')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Jenis Kelamin</label>
                            <select class="form-select" wire:model="jenis_kelamin" aria-label="Default select example">
                                <option value="">pilih Jenis Kelamin</option>
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Nomor HP</label>
                            <input type="text" wire:model="no_hp" class="form-control">
                            @error('no_hp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Nomor KTP</label>
                            <input type="text" wire:model="no_ktp" class="form-control">
                            @error('no_ktp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Tanggal Lahir</label>
                            <input type="date" wire:model="tgl_lahir" class="form-control">
                            @error('tgl_lahir')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Pekerjaan</label>
                            <input type="text" wire:model="pekerjaan" class="form-control">
                            @error('pekerjaan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="close_modal">Keluar</button>

                    {{-- tombol simpan cuma muncul di STEP 3 --}}
                    @if ($step == 3)
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    @endif
                </div>
            </form>

        </div>
    </div>
</div>
