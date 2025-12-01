<div>
    <div class="container">
        <div class="card mt-3" style="height: 550px">
            <div class="card-body">
                <div class="card-title">Daftar Antrian Poli Nifas / PNC</div>

                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col">
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
                                        <th>Tgl. Antrian</th> {{-- ✅ kolom baru --}}
                                        <th>Panggil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($poliNifas as $list)
                                        <tr style="text-align: center">
                                            <td>{{ $list->no_antrian }}</td>
                                            <td>{{ $list->nama }}</td>
                                            <td>{{ $list->alamat }}</td>
                                            <td>{{ $list->jenis_kelamin }}</td>
                                            <td>{{ $list->no_hp }}</td>
                                            <td>{{ $list->no_ktp }}</td>

                                            {{-- ✅ TANGGAL ANTRIAN --}}
                                            <td>
                                                {{ \Carbon\Carbon::parse($list->tanggal_antrian)->translatedFormat('d F Y') }}

                                                @if ($list->tanggal_antrian == now()->toDateString())
                                                    <span class="badge bg-success ms-1">Hari ini</span>
                                                @else
                                                    <span class="badge bg-secondary ms-1">Bukan hari ini</span>
                                                @endif
                                            </td>

                                            {{-- ✅ TOMBOL PANGGIL: hanya aktif kalau HARI INI --}}
                                            <td>
                                                @if ($list->tanggal_antrian == now()->toDateString())
                                                    <a class="btn btn-success"
                                                       wire:click="setAntrian({{ $list->id }})"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#panggilAntrian">
                                                        <i class="bi bi-telephone-forward"></i>
                                                    </a>
                                                @else
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

                            {{ $poliNifas->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- modal konfirmasi sama --}}
    @include('livewire.dashboard.daftar-poli.panggilAntrian')
</div>
