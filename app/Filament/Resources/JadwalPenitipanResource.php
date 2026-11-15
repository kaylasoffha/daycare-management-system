<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Anak;
use Filament\Tables;
use App\Models\pengasuh;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\JadwalPenitipan;
use App\Models\jadwal_penjemputan;  
use App\Models\jadwal_penitipan;
use App\Models\pendaftaran_anak;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\JadwalPenitipanResource\Pages;
use App\Filament\Resources\JadwalPenitipanResource\RelationManagers;

class JadwalPenitipanResource extends Resource
{
    protected static ?string $model = jadwal_penitipan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Jadwal Penitipan';
    protected static ?int $navigationSort = 5;
    public static ?string $label = 'Jadwal Penitipan Anak';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Card::make()
                    ->schema([
                        TextInput::make('id_penitipan')
                            -> required()
                            ->unique('jadwal_penitipan', 'id_penitipan', ignoreRecord: true)
                            ->disabled() // ID otomatis
                            ->default(fn () => 'JP' . str_pad((intval(preg_replace('/\D/', '', jadwal_penitipan::max('id_penitipan'))) + 1), 3, '0', STR_PAD_LEFT)),
                        Select::make('id_pendaftaran')
                            ->label('ID Pendaftaran')
                            ->options(pendaftaran_anak::query()
                                ->join('anak', 'pendaftaran_anak.id_anak', '=', 'anak.id_anak')
                                ->selectRaw('pendaftaran_anak.id_pendaftaran, CONCAT(pendaftaran_anak.id_pendaftaran, " - ", anak.nama_anak) as label')
                                ->pluck('label', 'id_pendaftaran'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => 
                                $set('nama_anak', pendaftaran_anak::join('anak', 'pendaftaran_anak.id_anak', '=', 'anak.id_anak')
                                    ->where('id_pendaftaran', $state)
                                    ->value('nama_anak'))),
                        Select::make('id_pengasuh')
                            ->label('Nama Pengasuh')
                            ->options(pengasuh::pluck('nama_pengasuh', 'id_pengasuh'))
                            ->searchable()
                            ->required(),
                            TimePicker::make('jam_masuk')
                            ->default('08:00:00')
                            ->disabled()
                            ->helperText('Jam masuk tetap: 08:00'),
                        
                        TimePicker::make('jam_keluar')
                            ->default('16:00:00')
                            ->disabled()
                            ->helperText('Jam keluar tetap: 16:00'),
                        
                        DatePicker::make('tanggal')
                            -> required()
                    ])
                        ->columns(1),
                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_penitipan')
                    ->searchable(),
                TextColumn::make('pengasuh.nama_pengasuh')
                    ->searchable(),
                TextColumn::make('id_pendaftaran')
                    ->searchable(),
                TextColumn::make('pendaftaran_anak.anak.nama_anak')
                    ->label('Nama Anak')
                    ->searchable(),
                TextColumn::make('jam_masuk'),
                TextColumn::make('jam_keluar'),
                TextColumn::make('tanggal'),                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make(),                
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // Fungsi untuk menyimpan data pada kedua tabel
    public static function afterSave(Form $form, $record)
    {
        // Mendapatkan data dari form
        $data = $form->getState();

        // Simpan data ke Jadwal Penjemputan dengan ID yang sama
        $jadwalPenjemputan = new jadwal_penjemputan();
        $jadwalPenjemputan->id_penitipan = $record->id_penitipan;
        $jadwalPenjemputan->id_pendaftaran = $data['id_pendaftaran'];
        $jadwalPenjemputan->tanggal = $data['tanggal'];
        $jadwalPenjemputan->jam_keluar = $data['jam_keluar'];
        $jadwalPenjemputan->status = 'Belum dijemput'; // Default status
        $jadwalPenjemputan->save();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalPenitipans::route('/'),
            'create' => Pages\CreateJadwalPenitipan::route('/create'),
            'edit' => Pages\EditJadwalPenitipan::route('/{record}/edit'),
        ];
    }
}
