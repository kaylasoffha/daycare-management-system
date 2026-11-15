<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Anak;
use App\Models\Wali;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Forms\Components\Card;
use Filament\Tables\Table;
use App\Models\PendaftaranAnak;
use App\Models\pendaftaran_anak;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PendaftaranAnakResource\Pages;
use App\Filament\Resources\PendaftaranAnakResource\RelationManagers;

class PendaftaranAnakResource extends Resource
{
    protected static ?string $model = pendaftaran_anak::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Pendaftaran Anak';
    protected static ?int $navigationSort = 3;
    public static ?string $label = 'Pendaftaran Anak';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
        Card::make()
        ->schema([
            TextInput::make('id_pendaftaran')
                -> required()
                ->unique('pendaftaran_anak', 'id_pendaftaran', ignoreRecord: true)
                ->disabled() // ID otomatis
                ->default(fn () => 'PA' . str_pad((intval(preg_replace('/\D/', '', pendaftaran_anak::max('id_pendaftaran'))) + 1), 2, '0', STR_PAD_LEFT)),
            Select::make('id_anak')
                ->label('Nama Anak')
                ->options(Anak::pluck('nama_anak', 'id_anak'))
                ->searchable()
                ->required(),
            Select::make('id_wali')
                ->label('Nama Wali')
                ->options(Wali::pluck('nama_wali', 'id_wali'))
                ->searchable()
                ->required(),
            DatePicker::make('tgl_pendaftaran')
                -> required()
        ])
            ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_pendaftaran')
                    -> searchable(),
                TextColumn::make('id_anak')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('anak.nama_anak')
                    ->label('Nama Anak')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('id_wali')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('wali.nama_wali')
                    ->label('Nama Wali')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tgl_pendaftaran')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendaftaranAnaks::route('/'),
            'create' => Pages\CreatePendaftaranAnak::route('/create'),
            'edit' => Pages\EditPendaftaranAnak::route('/{record}/edit'),
        ];
    }
}
