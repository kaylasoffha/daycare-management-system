<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Wali;
use Filament\Tables;
use App\Models\Anak;  
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\WaliResource\Pages; 


class WaliResource extends Resource
{
    protected static ?string $model = Wali::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';  
    protected static ?string $navigationLabel = 'Wali';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('id_wali')
                            ->label('ID Wali')
                            ->required()
                            ->unique('orangtua_wali', 'id_wali', ignoreRecord: true)
                            ->disabled() // ID otomatis
                            ->default(fn () => 'w' . str_pad((intval(preg_replace('/\D/', '', Wali::max('id_wali'))) + 1), 2, '0', STR_PAD_LEFT)),

                        TextInput::make('nama_wali')
                            ->label('Nama Wali')
                            ->required(),
                        
                        TextInput::make('no_telepon')
                            ->label('No. Telepon')
                            ->tel()
                            ->maxLength(12)
                            ->required(),
                        
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                        
                        TextInput::make('alamat')
                            ->label('Alamat')
                            ->required(),
                        
                        Select::make('hubungan')
                            ->label('Hubungan')
                            ->options([
                                'Ayah' => 'Ayah',
                                'Ibu' => 'Ibu',
                                'Wali' => 'Wali',
                            ]),

                        // Repeater untuk menambah anak
                        Repeater::make('anak')
                            ->relationship('anak')
                            ->label('Anak')
                            ->schema([
                                TextInput::make('id_anak')
                                    ->label('ID Anak')
                                    ->unique('anak', 'id_anak', ignoreRecord: true)
                                    ->disabled()
                                    ->default(fn ($get) => 'a' . str_pad(
                                        (int) DB::table('anak')->max(DB::raw("CAST(SUBSTRING(id_anak, 2, LENGTH(id_anak) - 1) AS UNSIGNED)")) + 
                                        count($get('../../anak') ?? []), 
                                        2, '0', STR_PAD_LEFT
                                    )),
                                TextInput::make('nama_anak')
                                    ->label('Nama Anak')
                                    ->required(),
                                DatePicker::make('tanggal_lahir')
                                    ->required()
                                    ->reactive() // Agar perubahan di tanggal_lahir dapat langsung mempengaruhi kolom umur
                                    ->afterStateUpdated(function (callable $set, $state) {
                                        $umur = \Carbon\Carbon::parse($state)->age;
                                        $set('umur', $umur); // Menghitung dan menyet umur
                                }),
                                Textarea::make('catatan')
                                    ->label('Catatan')
                                    ->nullable(),
                                Select::make('jenis_kelamin')
                                    ->options([
                                        'L' => 'L',
                                        'P' => 'P',
                            ])
                            ])
                            ->columns(2)
                            ->createItemButtonLabel('Tambah Anak'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_wali')
                    ->label('ID Wali')
                    ->sortable(),
                
                TextColumn::make('nama_wali')
                    ->label('Nama Wali')
                    ->searchable(),
                
                TextColumn::make('no_telepon')
                    ->label('No. Telepon'),
                
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                
                TextColumn::make('alamat')
                    ->label('Alamat'),
                
                TextColumn::make('hubungan')
                    ->label('Hubungan'),
            ])
            ->filters([ 
                // Tambahkan filter jika diperlukan
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
            // Tambahkan relasi jika ada
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWalis::route('/'), 
            'create' => Pages\CreateWali::route('/create'), 
            'edit' => Pages\EditWali::route('/{record}/edit'), 
        ];
    }
}
