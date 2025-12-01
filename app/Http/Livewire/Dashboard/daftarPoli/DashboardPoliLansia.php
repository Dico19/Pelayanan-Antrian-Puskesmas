<?php

namespace App\Http\Livewire\Dashboard\daftarPoli;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Antrian;

class DashboardPoliLansia extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // simpan ID antrian yang dipilih
    public $selectedId;

    // klik tombol panggil → set ID saja
    public function setAntrian($id)
    {
        $this->selectedId = $id;
    }

    // klik tombol "Ya" di modal → eksekusi panggil
    public function panggilAntrian()
    {
        if (!$this->selectedId) {
            session()->flash('success', 'Antrian belum dipilih');
            return;
        }

        $antrian = Antrian::findOrFail($this->selectedId);

        $antrian->update([
            'is_call' => 1,
            'status'  => 'dipanggil',
        ]);

        session()->flash('success', 'Berhasil memanggil antrian ini');

        // tutup modal
        $this->dispatchBrowserEvent('closeModal');

        // refresh layout sidebar
        $this->dispatchBrowserEvent('refreshPage');

        // reset id
        $this->selectedId = null;
    }

    public function render()
    {
        return view('livewire.dashboard.daftar-poli.dashboard-poli-lansia', [
            'poliLansia' => Antrian::where('poli', 'lansia & disabilitas')
                ->where('is_call', 0)
                ->orderBy('created_at', 'asc')
                ->paginate(10),
        ]);
    }
}
