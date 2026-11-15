<?php

namespace App\Filament\Resources\JadwalPenitipanResource\Pages;

use App\Filament\Resources\JadwalPenitipanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalPenitipans extends ListRecords
{
    protected static string $resource = JadwalPenitipanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
