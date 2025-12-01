<div class="container mt-4">

    <div class="card">
        <div class="card-body">

            <h4 class="card-title mb-3">Daftar Antrian Poli KIA & KB</h4>

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr style="text-align: center">
                            <th>No Antrian</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Jenis Kelamin</th>
                            <th>No HP</th>
                            <th>No KTP</th>
                            <th>Tgl. Antrian</th>
                            <th>Panggil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($polikia as $list)
                            <tr style="text-align: center">
                                <td>{{ $list->no_antrian }}</td>
                                <td>{{ $list->nama }}</td>
                                <td>{{ $list->alamat }}</td>
                                <td>{{ $list->jenis_kelamin }}</td>
                                <td>{{ $list->no_hp }}</td>
                                <td>{{ $list->no_ktp }}</td>

                                {{-- TANGGAL ANTRIAN --}}
                                <td>
                                    {{ \Carbon\Carbon::parse($list->tanggal_antrian)->translatedFormat('d F Y') }}

                                    @if ($list->tanggal_antrian == now()->toDateString())
                                        <span class="badge bg-success ms-1">Hari ini</span>
                                    @else
                                        <span class="badge bg-secondary ms-1">Bukan hari ini</span>
                                    @endif
                                </td>

                                {{-- TOMBOL PANGGIL --}}
                                <td>
                                    @if ($list->tanggal_antrian == now()->toDateString())
                                        {{-- ✅ HANYA HARI INI YANG BISA DIPANGGIL --}}
                                        <button
                                            type="button"
                                            class="btn btn-success"
                                            wire:click="setAntrian({{ $list->id }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#panggilAntrian"
                                        >
                                            <i class="bi bi-telephone-forward"></i>
                                        </button>
                                    @else
                                        {{-- ❌ TANGGAL LAIN: NONAKTIF --}}
                                        <button type="button"
                                                class="btn btn-secondary"
                                                disabled
                                                title="Hanya antrian dengan tanggal hari ini yang bisa dipanggil.">
                                            <i class="bi bi-telephone-forward"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION (kalau pakai) --}}
            <div class="mt-2">
                {{ $polikia->links() }}
            </div>

        </div>
    </div>

    {{-- ✅ PENTING: MODALNYA DI-INCLUDE DI SINI --}}
    @include('livewire.dashboard.daftar-poli.panggilAntrian')

</div>
