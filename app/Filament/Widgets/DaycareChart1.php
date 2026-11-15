<?php

namespace App\Filament\Widgets;

use App\Models\jadwal_penitipan;
use Filament\Widgets\ChartWidget;

class DaycareChart1 extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Penitipan Anak Berdasarkan Bulan';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Ambil data jumlah Penitipan berdasarkan bulan
        $data = jadwal_penitipan::selectRaw('MONTH(tanggal) as month, COUNT(*) as count')
            ->whereYear('tanggal', 2025)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Label bulan (1 = Januari, dst)
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        // Persiapkan dataset berdasarkan bulan
        $counts = array_fill(0, 12, 0);

        foreach ($data as $row) {
            $counts[$row->month - 1] = $row->count;
        }

        return [
            'labels' => $monthLabels,
            'datasets' => [
                [
                    'label' => 'Jumlah Penitipan',
                    'data' => $counts,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
