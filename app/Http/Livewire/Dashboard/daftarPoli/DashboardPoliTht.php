<?php

namespace App\Http\Livewire\Dashboard\daftarPoli;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Antrian;

class DashboardPoliTht extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selectedId;

    public function setAntrian($id)
    {
        $this->selectedId = $id;
    }

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

        // tutup modal & refresh sidebar badge
        $this->dispatchBrowserEvent('closeModal');
        $this->dispatchBrowserEvent('refreshPage');

        $this->selectedId = null;
    }

    public function render()
    {
        return view('livewire.dashboard.daftar-poli.dashboard-poli-tht', [
            'poliTht' => Antrian::where('poli', 'tht')
                ->where('is_call', 0)
                ->orderBy('created_at', 'asc')
                ->paginate(10),
        ]);
    }
}
