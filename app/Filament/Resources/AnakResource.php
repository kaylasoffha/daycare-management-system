<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnakResource\Pages;
use Filament\Forms;
use App\Models\Anak;
use App\Models\Wali;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Contracts\HasTable;

class AnakResource extends Resource
{
    protected static ?string $model = Anak::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    // Set navigation sort order - higher number appears second
    protected static ?string $navigationLabel = 'Anak';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                        TextInput::make('id_anak')
                            ->label('ID Anak')
                            ->unique('anak', 'id_anak', ignoreRecord: true)
                            ->disabled() // ID otomatis
                            ->default(fn () => 'w' . str_pad((intval(preg_replace('/\D/', '', Wali::max('id_wali'))) + 1), 2, '0', STR_PAD_LEFT)),
                        
                        TextInput::make('nama_anak')
                            ->label('Nama Anak')
                            ->required()
                            ->placeholder('Masukkan Nama Anak'),

                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),
                        
                        Select::make('id_wali')
                            ->label('Nama Wali')
                            ->options(Wali::pluck('nama_wali', 'id_wali'))
                            ->searchable()
                            ->required(),

                        Textarea::make('catatan')
                            ->label('Catatan')
                            ->nullable(),

                        Select::make('jenis_kelamin')
                            ->options([
                                'L' => 'L',
                                'P' => 'P',
                    ])
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('id_anak')
                ->label('ID Anak')
                ->sortable()
                ->searchable(),
            TextColumn::make('nama_anak')
                ->label('Nama Anak')
                ->sortable()
                ->searchable(),
            TextColumn::make('tanggal_lahir')
                ->label('Tanggal Lahir')
                ->date()
                ->sortable(),
            TextColumn::make('wali.nama_wali')
                ->label('Nama Wali')
                ->sortable()
                ->searchable(),
            TextColumn::make('umur') // Menampilkan umur
                ->label('Umur')
                ->sortable()
                ->getStateUsing(fn ($record) => $record->umur),
            TextColumn::make('catatan')
                ->label('Catatan')
                ->wrap()
                ->searchable(),
            TextColumn::make('wali.hubungan')
                ->label('Hubungan')
                ->sortable(),
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
            'index' => Pages\ListAnak::route('/'),
            // 'create' => Pages\CreateAnak::route('/create'),
            'edit' => Pages\EditAnak::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
{
    return false; // Kembalikan false untuk menonaktifkan tombol Create
}
}
