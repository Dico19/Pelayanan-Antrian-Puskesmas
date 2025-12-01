<?php

namespace App\Http\Livewire\Dashboard\daftarPoli;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Antrian;

class DashboardPoliBalita extends Component
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

        $this->dispatchBrowserEvent('closeModal');
        $this->dispatchBrowserEvent('refreshPage');

        $this->selectedId = null;
    }

    public function render()
    {
        return view('livewire.dashboard.daftar-poli.dashboard-poli-balita', [
            'poliBalita' => Antrian::where('poli', 'balita')
                ->where('is_call', 0)
                ->orderBy('created_at', 'asc')
                ->paginate(10),
        ]);
    }
}
