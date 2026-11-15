<?php

namespace App\Filament\Resources\PendaftaranAnakResource\Pages;

use App\Filament\Resources\PendaftaranAnakResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPendaftaranAnaks extends ListRecords
{
    protected static string $resource = PendaftaranAnakResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
