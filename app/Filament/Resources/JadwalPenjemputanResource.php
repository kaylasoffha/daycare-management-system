<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\jadwal_penjemputan;
use App\Models\jadwal_penitipan;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\BadgeColumn;
use App\Filament\Resources\JadwalPenjemputanResource\Pages;

class JadwalPenjemputanResource extends Resource
{
    protected static ?string $model = jadwal_penjemputan::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Jadwal Penjemputan';
    protected static ?int $navigationSort = 6;
    public static ?string $label = 'Jadwal Penjemputan Anak';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_penitipan')
                    ->label('ID Penitipan')
                    ->required()
                    ->options(jadwal_penitipan::pluck('id_penitipan', 'id_penitipan'))
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => self::syncPenitipanData($state, $set)),

                Select::make('id_anak')
                    ->label('ID Anak')
                    ->options(fn () => \App\Models\anak::pluck('nama_anak', 'id_anak')) // Ambil daftar anak dari tabel anak
                    ->searchable() // Bisa cari anak
                    ->required()
                    ->dehydrated(),

                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->disabled()
                    ->dehydrated(),

                TimePicker::make('jam_keluar')
                    ->label('Jam Keluar')
                    ->default(now()->format('H:i:s')),

                TextInput::make('penjemput')
                    ->label('Penjemput')
                    ->default(''),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Sudah dijemput' => 'Sudah dijemput',
                        'Belum dijemput' => 'Belum dijemput',
                    ])
                    ->default('Belum dijemput'),
            ]);
    }

    public static function syncPenitipanData($id_penitipan, callable $set)
    {
        $jadwal = jadwal_penitipan::find($id_penitipan);
        if ($jadwal) {
            $set('id_anak', $jadwal->id_anak);
            $set('tanggal', $jadwal->tanggal);
        } else {
            $set('id_anak', null);
            $set('tanggal', null);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_penitipan')
                    ->label('ID Penitipan')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('anak.nama_anak')
                    ->label('Nama Anak')
                    ->searchable(),

                TextColumn::make('tanggal')
                    ->label('Tanggal'),

                TextColumn::make('jam_keluar')
                    ->label('Jam Keluar'),

                TextColumn::make('penjemput')
                    ->label('Penjemput'),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->color(fn (string $state): string => match ($state) {
                        'Sudah dijemput' => 'success',
                        'Belum dijemput' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalPenjemputans::route('/'),
            'create' => Pages\CreateJadwalPenjemputan::route('/create'),
            'edit' => Pages\EditJadwalPenjemputan::route('/{record}/edit'),
        ];
    }
}
