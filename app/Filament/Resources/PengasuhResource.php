<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pengasuh;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Card;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PengasuhResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PengasuhResource\RelationManagers;

class PengasuhResource extends Resource
{
    protected static ?string $model = Pengasuh::class;

    protected static ?string $navigationIcon = 'heroicon-o-hand-raised';
    protected static ?string $navigationLabel = 'Pengasuh';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Card::make()
            ->schema([
                TextInput::make('id_pengasuh')
                    ->label('ID Pengasuh')
                    ->required()
                    ->unique('pengasuh', 'id_pengasuh', ignoreRecord: true)
                    ->disabled() // ID otomatis
                    ->default(fn () => 'p' . str_pad((intval(preg_replace('/\D/', '', Pengasuh::max('id_pengasuh'))) + 1), 2, '0', STR_PAD_LEFT)),
                TextInput::make('nama_pengasuh')
                    -> required(),
                TextInput::make('alamat')
                    ->label('Alamat')
                    ->required(),
                TextInput::make('no_telepon')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(12)
                    ->required(),
                Select::make('jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
            ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('id_pengasuh')
                ->sortable()
                ->searchable(),
            TextColumn::make('nama_pengasuh')
                ->searchable(),
            TextColumn::make('no_telepon')
                ->searchable(),
            TextColumn::make('alamat'),
            TextColumn::make('jenis_kelamin'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengasuhs::route('/'),
            'create' => Pages\CreatePengasuh::route('/create'),
            'edit' => Pages\EditPengasuh::route('/{record}/edit'),
        ];
    }
}
