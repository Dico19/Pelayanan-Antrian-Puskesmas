<?php

namespace App\Http\Livewire\Antrian;

use App\Models\Antrian;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class ShowAntrian extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $antrian_id,
           $no_antrian,
           $nama,
           $alamat,
           $jenis_kelamin,
           $no_hp,
           $no_ktp,
           $tgl_lahir,
           $pekerjaan,
           $poli,
           $tanggal_antrian,
           $user_id,
           $data;

    // STEP
    // 1 = pilih poli, 2 = pilih tanggal, 3 = isi form
    public $step = 1;

    // list poli
    public $listPoli = [
        'umum'                 => 'Umum',
        'gigi'                 => 'Gigi',
        'tht'                  => 'THT',
        'lansia & disabilitas' => 'Lansia & Disabilitas',
        'balita'               => 'Balita',
        'kia & kb'             => 'KIA & KB',
        'nifas/pnc'            => 'Nifas / PNC',
    ];

    // pilihan tanggal (6 hari ke depan)
    public $tanggalPilihan = [];

    protected $rules = [
        'nama'            => 'required',
        'alamat'          => 'required',
        'jenis_kelamin'   => 'required',
        'no_hp'           => 'required|numeric',
        'no_ktp'          => 'required|numeric',
        'tgl_lahir'       => 'required',
        'pekerjaan'       => 'required',
        'poli'            => 'required',
        'tanggal_antrian' => 'required|date',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    // helper untuk amankan fitur admin
    private function isAdmin()
    {
        return auth()->check() && auth()->user()->role_id == 1;
    }

    /* =========================
     *  STEP 1 : PILIH POLI
     * =========================*/
    public function pilihPoli($kode)
    {
        if (! array_key_exists($kode, $this->listPoli)) {
            return;
        }

        $this->poli = $kode;
        $this->tanggal_antrian = null;
        $this->generateTanggalPilihan();
        $this->step = 2;
    }

    public function kembaliKePilihPoli()
    {
        $this->step = 1;
        $this->poli = '';
        $this->tanggal_antrian = null;
        $this->tanggalPilihan = [];
    }

    /* =========================
     *  STEP 2 : PILIH TANGGAL
     * =========================*/
    protected function generateTanggalPilihan()
    {
        $this->tanggalPilihan = [];

        $today = Carbon::today();

        for ($i = 0; $i < 6; $i++) {
            $tanggal = $today->copy()->addDays($i);
            $isMinggu = $tanggal->dayOfWeek === Carbon::SUNDAY;

            // hitung jumlah antrian utk poli & tanggal tsb (kecuali minggu/libur)
            $jumlah = 0;
            if ($this->poli && ! $isMinggu) {
                $jumlah = Antrian::where('poli', $this->poli)
                    ->where('tanggal_antrian', $tanggal->toDateString())
                    ->count();
            }

            $this->tanggalPilihan[] = [
                'date'        => $tanggal->toDateString(),
                'hari'        => $this->hariIndo($tanggal),
                'tanggal'     => $tanggal->format('d'),
                'bulan_tahun' => $this->bulanIndo($tanggal) . ' ' . $tanggal->format('Y'),
                'is_libur'    => $isMinggu,
                'jumlah'      => $jumlah,
            ];
        }
    }

    protected function hariIndo(Carbon $date)
    {
        $map = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu',
        ];

        return $map[$date->dayOfWeek] ?? '';
    }

    protected function bulanIndo(Carbon $date)
    {
        $map = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $map[(int) $date->format('n')] ?? '';
    }

    public function pilihTanggal($tanggal)
    {
        // pastikan tanggal yg dipilih ada di list & bukan Minggu/libur
        foreach ($this->tanggalPilihan as $item) {
            if ($item['date'] === $tanggal && ! $item['is_libur']) {
                $this->tanggal_antrian = $tanggal;
                $this->step = 3; // lanjut ke form
                return;
            }
        }
    }

    public function kembaliKePilihTanggal()
    {
        $this->step = 2;
    }

    /* =========================
     *  STEP 3 : SIMPAN ANTRIAN
     * =========================*/
    public function save()
    {
        // validasi semua field (poli + tanggal + data pasien)
        $validatedData = $this->validate();

        // ambil antrian terbaru utk poli & tanggal yang dipilih
        $latestAntrian = Antrian::where('poli', $this->poli)
            ->where('tanggal_antrian', $this->tanggal_antrian)
            ->latest('id')
            ->first();

        if (!$latestAntrian) {
            if ($this->poli === 'umum') {
                $this->no_antrian = 'U1';
            } elseif ($this->poli === 'gigi') {
                $this->no_antrian = 'G1';
            } elseif ($this->poli === 'tht') {
                $this->no_antrian = 'T1';
            } elseif ($this->poli === 'lansia & disabilitas') {
                $this->no_antrian = 'L1';
            } elseif ($this->poli === 'balita') {
                $this->no_antrian = 'B1';
            } elseif ($this->poli === 'kia & kb') {
                $this->no_antrian = 'K1';
            } elseif ($this->poli === 'nifas/pnc') {
                $this->no_antrian = 'N1';
            }
        } else {
            $kode_awal = substr($latestAntrian->no_antrian, 0, 1);
            $angka = (int) substr($latestAntrian->no_antrian, 1);
            $angka += 1;
            $this->no_antrian = $kode_awal . $angka;
        }

        $validatedData['no_antrian']      = $this->no_antrian;
        $validatedData['tanggal_antrian'] = $this->tanggal_antrian;

        $validatedData['user_id'] = null;
        $validatedData['is_call'] = 0;

        $antrian = Antrian::create($validatedData);

        session()->flash('success', 'Berhasil Mengambil Antrian');

        $this->emit('update');
        $this->resetInput();
        $this->dispatchBrowserEvent('closeModal');

        return redirect()->route('antrian.status', $antrian->id);
    }

    public function resetInput()
    {
        $this->no_antrian      = '';
        $this->nama            = '';
        $this->alamat          = '';
        $this->jenis_kelamin   = '';
        $this->no_hp           = '';
        $this->no_ktp          = '';
        $this->poli            = '';
        $this->tgl_lahir       = '';
        $this->pekerjaan       = '';
        $this->tanggal_antrian = null;
        $this->tanggalPilihan  = [];
        $this->step            = 1;
    }

    public function close_modal()
    {
        $this->resetInput();
    }

    /* =========================
     *  FITUR ADMIN (EDIT/DELETE)
     * =========================*/
    public function editAntrian($antrian_id)
    {
        if (!$this->isAdmin()) abort(403);

        $antrian = Antrian::find($antrian_id);
        if ($antrian) {
            $this->antrian_id       = $antrian->id;
            $this->no_antrian       = $antrian->no_antrian;
            $this->nama             = $antrian->nama;
            $this->alamat           = $antrian->alamat;
            $this->jenis_kelamin    = $antrian->jenis_kelamin;
            $this->no_hp            = $antrian->no_hp;
            $this->no_ktp           = $antrian->no_ktp;
            $this->poli             = $antrian->poli;
            $this->tgl_lahir        = $antrian->tgl_lahir;
            $this->pekerjaan        = $antrian->pekerjaan;
        } else {
            return redirect()->to('/');
        }
    }

    public function updateAntrian()
    {
        if (!$this->isAdmin()) abort(403);

        if ($this->poli === 'umum') {
            $this->no_antrian = 'U1';
        } elseif ($this->poli === 'gigi') {
            $this->no_antrian = 'G1';
        } elseif ($this->poli === 'tht') {
            $this->no_antrian = 'T1';
        } elseif ($this->poli === 'lansia & disabilitas') {
            $this->no_antrian = 'L1';
        } elseif ($this->poli === 'balita') {
            $this->no_antrian = 'B1';
        } elseif ($this->poli === 'kia & kb') {
            $this->no_antrian = 'K1';
        } elseif ($this->poli === 'nifas/pnc') {
            $this->no_antrian = 'N1';
        }

        $latest_no_antrian = Antrian::where('poli', $this->poli)
            ->latest()
            ->first();

        if ($latest_no_antrian) {
            $kode_awal = substr($latest_no_antrian->no_antrian, 0, 1);
            $angka = (int) substr($latest_no_antrian->no_antrian, 1);
            $angka += 1;
            $this->no_antrian = $kode_awal . $angka;
        }

        $validatedData = $this->validate([
            'no_antrian'    => 'required|unique:antrians',
            'nama'          => 'required',
            'alamat'        => 'required',
            'jenis_kelamin' => 'required',
            'no_hp'         => 'required',
            'no_ktp'        => 'required',
            'poli'          => 'required',
            'tgl_lahir'     => 'required',
            'pekerjaan'     => 'required',
        ]);

        Antrian::where('id', $this->antrian_id)->update([
            'no_antrian'    => $validatedData['no_antrian'],
            'nama'          => $validatedData['nama'],
            'alamat'        => $validatedData['alamat'],
            'jenis_kelamin' => $validatedData['jenis_kelamin'],
            'no_hp'         => $validatedData['no_hp'],
            'no_ktp'        => $validatedData['no_ktp'],
            'poli'          => $validatedData['poli'],
            'tgl_lahir'     => $validatedData['tgl_lahir'],
            'pekerjaan'     => $validatedData['pekerjaan'],
        ]);

        session()->flash('success', 'Berhasil Mengedit Data Antrian');
        $this->resetInput();
        $this->dispatchBrowserEvent('closeModal');
    }

    public function deleteAntrian($antrian_id)
    {
        if (!$this->isAdmin()) abort(403);
        $this->antrian_id = $antrian_id;
    }

    public function destroy()
    {
        if (!$this->isAdmin()) abort(403);

        Antrian::find($this->antrian_id)->delete();
        session()->flash('success', 'Berhasil Menghapus 1 Data');
        $this->resetInput();
        $this->dispatchBrowserEvent('closeModal');
    }

    public function showDetail($antrian_id)
    {
        $antrian = Antrian::find($antrian_id);
        if ($antrian) {
            $this->antrian_id       = $antrian->id;
            $this->no_antrian       = $antrian->no_antrian;
            $this->nama             = $antrian->nama;
            $this->alamat           = $antrian->alamat;
            $this->jenis_kelamin    = $antrian->jenis_kelamin;
            $this->no_hp            = $antrian->no_hp;
            $this->no_ktp           = $antrian->no_ktp;
            $this->poli             = $antrian->poli;
            $this->tgl_lahir        = $antrian->tgl_lahir;
            $this->pekerjaan        = $antrian->pekerjaan;
        } else {
            return redirect()->to('/');
        }
    }

    public function mount()
    {
        $this->data = Antrian::all();
    }

    public function render()
    {
        return view('livewire.antrian.show-antrian', [
            'antrian' => $this->poli
                ? Antrian::where('poli', $this->poli)->where('is_call', 0)->paginate(10)
                : Antrian::where('is_call', 0)->paginate(10),

            'cekAntrian'    => 0,
            'detailAntrian' => collect(),
        ]);
    }
}
