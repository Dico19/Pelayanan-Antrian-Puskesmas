<?php

namespace App\Http\Livewire\Dashboard\daftarPoli;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Antrian;

class DashboardPoliNifas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // simpan ID antrian yang dipilih
    public $selectedId;

    // klik tombol panggil → hanya set ID
    public function setAntrian($id)
    {
        $this->selectedId = $id;
    }

    // klik tombol "Ya" di modal → memanggil
    public function panggilAntrian()
    {
        if (!$this->selectedId) {
            session()->flash('success', 'Antrian belum dipilih');
            return;
        }

        $antrian = Antrian::findOrFail($this->selectedId);

        // update status = dipanggil
        $antrian->update([
            'is_call' => 1,
            'status'  => 'dipanggil',
        ]);

        session()->flash('success', 'Berhasil memanggil antrian ini');

        // tutup modal
        $this->dispatchBrowserEvent('closeModal');

        // refresh sidebar
        $this->dispatchBrowserEvent('refreshPage');

        // reset id supaya aman
        $this->selectedId = null;
    }

    public function render()
    {
        return view('livewire.dashboard.daftar-poli.dashboard-poli-nifas', [
            'poliNifas' => Antrian::where('poli', 'nifas/pnc')
                ->where('is_call', 0)
                ->orderBy('created_at', 'asc')
                ->paginate(10),
        ]);
    }
}
