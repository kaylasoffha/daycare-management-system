<?php

namespace App\Filament\Widgets;

use App\Models\jadwal_penitipan;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class DaycareChart2 extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Penitipan Anak Berdasarkan Hari';
    protected static ?int $sort = 3;

    protected function getType(): string
    {
        return 'bar'; // Bisa diubah ke 'line' kalau mau grafik garis
    }

    protected function getData(): array
    {
        // Ambil data jumlah penitipan berdasarkan hari dalam seminggu
        $data = jadwal_penitipan::selectRaw('DAYOFWEEK(tanggal) as day_of_week, COUNT(*) as count')
            ->whereYear('tanggal', 2025)
            ->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get();

        // Mapping nomor hari (1 = Minggu, 2 = Senin, ..., 7 = Sabtu) ke nama hari
        $dayLabels = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // Array default untuk memastikan semua hari ada
        $counts = array_fill(0, 7, 0);

        // Masukkan data ke dalam array berdasarkan indeks hari
        foreach ($data as $row) {
            $index = $row->day_of_week - 1; // Karena DAYOFWEEK() mulai dari 1 (Minggu)
            $counts[$index] = $row->count;
        }

        return [
            'labels' => $dayLabels,
            'datasets' => [
                [
                    'label' => 'Jumlah Penitipan',
                    'data' => $counts,
                    'borderWidth' => 1,
                ],
            ],
        ];
    }
}
