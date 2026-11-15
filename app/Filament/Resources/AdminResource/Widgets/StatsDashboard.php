<?php

namespace App\Filament\Widgets;

use App\Models\Anak;
use App\Models\pengasuh;
use App\Models\jadwal_penjemputan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $countAnak = anak::count();
        $countPengasuh = pengasuh::count();
        $countBelumDijemput = jadwal_penjemputan::where('status', 'Belum dijemput')->count();
        $countSudahDijemput = jadwal_penjemputan::where('status', 'Sudah dijemput')->count();
        return [
            Stat::make('Jumlah Anak', $countAnak, ),
            Stat::make('Jumlah Pengasuh', $countPengasuh, ),
            Stat::make('Belum Dijemput', $countBelumDijemput, ),
            Stat::make('Sudah Dijemput', $countSudahDijemput, ),
        ];
    }
}