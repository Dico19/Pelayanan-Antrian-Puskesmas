<?php

namespace App\Http\Livewire\Antrian;

use Livewire\Component;
use App\Models\Antrian;

class StatusAntrian extends Component
{
    public Antrian $antrian;
    public int $orangDiDepan = 0;
    public int $estimasiMenit = 0;

    /**
     * Dipanggil dari route:
     * Route::get('/antrian/status/{antrian}', StatusAntrian::class)
     * Laravel akan mengisi $antrian otomatis (route model binding).
     */
    public function mount(Antrian $antrian): void
    {
        $this->antrian = $antrian;
        $this->updateEstimasi();
    }

    /**
     * Hitung jumlah orang di depan + estimasi menit.
     */
    protected function updateEstimasi(): void
    {
        // Orang di depan:
        // - poli sama
        // - tanggal_antrian sama
        // - belum dipanggil (is_call = 0)
        // - id lebih kecil (daftar lebih dulu)
        $orangDiDepan = Antrian::where('poli', $this->antrian->poli)
            ->whereDate('tanggal_antrian', $this->antrian->tanggal_antrian)
            ->where('is_call', 0)
            ->where('id', '<', $this->antrian->id)
            ->count();

        $this->orangDiDepan  = $orangDiDepan;
        $this->estimasiMenit = $orangDiDepan * 10; // ganti 10 kalau mau
    }

    public function render()
    {
        // Karena view pakai wire:poll, setiap render kita update estimasi
        $this->updateEstimasi();

        return view('livewire.antrian.status-antrian')
            ->layout('layouts.main'); // layout front yang kamu pakai (home, dll)
    }
}
