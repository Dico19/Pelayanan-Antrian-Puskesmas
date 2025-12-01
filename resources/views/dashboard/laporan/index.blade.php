@extends('dashboard.layouts.main')

@section('content')


<div class="d-flex justify-content-end gap-2 mb-3">

    {{-- Tombol Export PDF --}}
    <a href="{{ route('admin.laporan.pdf', request()->query()) }}"
       class="btn btn-danger btn-sm">
        <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
    </a>

    {{-- Tombol Export Excel (CSV) --}}
    <a href="{{ route('admin.laporan.excel', request()->query()) }}"
       class="btn btn-success btn-sm">
        <i class="bi bi-file-earmark-spreadsheet"></i> Export Excel
    </a>

</div>

{{-- Livewire Komponen Laporan --}}
<div>
    <livewire:dashboard.laporan.show-laporan />
</div>

@ends
