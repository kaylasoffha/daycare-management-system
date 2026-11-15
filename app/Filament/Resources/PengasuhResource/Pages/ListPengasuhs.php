<?php

namespace App\Filament\Resources\PengasuhResource\Pages;

use App\Filament\Resources\PengasuhResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengasuhs extends ListRecords
{
    protected static string $resource = PengasuhResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
