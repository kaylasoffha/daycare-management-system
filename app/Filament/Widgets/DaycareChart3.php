<?php

namespace App\Filament\Widgets;

use App\Models\Anak;
use Filament\Widgets\ChartWidget;

class DaycareChart3 extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Umur Anak yang Terdaftar';
    protected static ?int $sort = 4;

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        // Ambil data jumlah pendaftaran berdasarkan umur
        $data = Anak::selectRaw('umur, COUNT(*) as count')
            ->groupBy('umur')
            ->orderBy('umur')
            ->get();

        // Ambil umur sebagai label dan jumlah sebagai data
        $labels = $data->pluck('umur')->toArray();
        $counts = $data->pluck('count')->toArray();

        // Warna untuk tiap umur (disesuaikan jika jumlah umur lebih banyak)
        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', 
            '#FF9F40', '#E7E9ED', '#71B37C', '#C9CBCF', '#5D62B5'
        ];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Pendaftaran',
                    'data' => $counts,
                    'backgroundColor' => array_slice($colors, 0, count($labels)), // Ambil warna sesuai jumlah data
                ],
            ],
        ];
    }
}
