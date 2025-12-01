<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Antrian;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // kirim data counts ke layout dashboard
        view()->composer('dashboard.layouts.main', function ($view) {

            $counts = [
                'umum'   => Antrian::where('poli', 'umum')->where('is_call', 0)->count(),
                'gigi'   => Antrian::where('poli', 'gigi')->where('is_call', 0)->count(),
                'tht'    => Antrian::where('poli', 'tht')->where('is_call', 0)->count(),
                'lansia' => Antrian::where('poli', 'lansia & disabilitas')->where('is_call', 0)->count(),
                'balita' => Antrian::where('poli', 'balita')->where('is_call', 0)->count(),
                'kia'    => Antrian::where('poli', 'kia & kb')->where('is_call', 0)->count(),
                'nifas'  => Antrian::where('poli', 'nifas/pnc')->where('is_call', 0)->count(),
            ];

            $view->with('counts', $counts);
        });
    }
}
