<div>
    <div class="container">
        <div class="card mt-3">
            <div class="card-body">

                {{-- JUDUL --}}
                <h5 class="card-title mb-3">Laporan Antrian</h5>

                {{-- ALERT SUCCESS (kalau ada) --}}
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- FILTER + BUTTON DI SATU BARIS --}}
                <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">

                    {{-- FILTER TANGGAL / POLI / SEARCH --}}
                    <div class="row g-2 flex-grow-1">
                        <div class="col-md-3">
                            <select wire:model="tanggal_antrian" class="form-control">
                                <option value="">Semua Tanggal</option>
                                <option value="today">Hari ini</option>
                                <option value="week">Minggu ini</option>
                                <option value="month">Bulan ini</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select wire:model="poli" class="form-control">
                                <option value="">Semua Poli</option>
                                <option value="umum">Poli Umum</option>
                                <option value="gigi">Poli Gigi</option>
                                <option value="tht">Poli THT</option>
                                <option value="lansia & disabilitas">Lansia & Disabilitas</option>
                                <option value="balita">Balita</option>
                                <option value="kia & kb">KIA & KB</option>
                                <option value="nifas/pnc">Nifas / PNC</option>
                            </select>
                        </div>

                        <div class="col">
                            <input wire:model.debounce.500ms="search"
                                   type="search"
                                   class="form-control"
                                   placeholder="Cari Nama Pasien...">
                        </div>
                    </div>

                    {{-- BUTTON DATA PASIEN + PDF + EXCEL --}}
                    <div class="d-flex gap-2">
                        {{-- 🔵 Data Pasien --}}
                        <a href="{{ route('admin.pasien.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-people"></i> Data Pasien
                        </a>

                        {{-- 🔴 PDF --}}
                        <a href="{{ route('admin.laporan.pdf') }}" target="_blank" class="btn btn-danger">
                            <i class="bi bi-filetype-pdf"></i> PDF
                        </a>

                        {{-- 🟢 Excel --}}
                        <a href="{{ route('admin.laporan.excel') }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel"></i> Excel
                        </a>
                    </div>
                </div>

                {{-- TABEL LAPORAN --}}
                <div class="table-responsive">
                    <table class="table table-bordered" id="table_id">
                        <thead>
                        <tr class="text-center">
                            <th>No Antrian</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Jenis Kelamin</th>
                            <th>Nomor HP</th>
                            <th>Nomor KTP</th>
                            <th>Tgl. Lahir</th>
                            <th>Pekerjaan</th>
                            <th>Poli</th>
                            <th>Tgl. Antrian</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($laporan as $list)
                            <tr class="text-center">
                                <td>{{ $list->no_antrian }}</td>
                                <td>{{ $list->nama }}</td>
                                <td>{{ $list->alamat }}</td>
                                <td>{{ $list->jenis_kelamin }}</td>
                                <td>{{ $list->no_hp }}</td>
                                <td>{{ $list->no_ktp }}</td>
                                <td>{{ $list->tgl_lahir }}</td>
                                <td>{{ $list->pekerjaan }}</td>
                                <td>{{ strtoupper($list->poli) }}</td>
                                <td>{{ $list->tanggal_antrian }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">
                                    Data laporan belum ada.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINASI --}}
                <div class="mt-3">
                    {{ $laporan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
