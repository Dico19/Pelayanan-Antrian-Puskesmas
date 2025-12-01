<?php

namespace App\Http\Livewire\Dashboard\daftarPoli;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Antrian;

class DashboardPoliKia extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // simpan id antrian yang dipilih
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
        // ambil antrian poli KIA & KB yang belum dipanggil
        $polikia = Antrian::where('poli', 'kia & kb')
            ->where('is_call', 0)
            ->orderBy('tanggal_antrian')   // boleh diganti created_at kalau mau
            ->orderBy('no_antrian')
            ->paginate(10);

        // kirim ke blade dgn nama $polikia (sesuai yg dipakai di view)
        return view('livewire.dashboard.daftar-poli.dashboard-poli-kia', compact('polikia'));
    }
}
