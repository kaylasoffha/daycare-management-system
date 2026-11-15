<?php

namespace App\Filament\Resources\JadwalPenitipanResource\Pages;

use App\Filament\Resources\JadwalPenitipanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalPenitipan extends EditRecord
{
    protected static string $resource = JadwalPenitipanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
