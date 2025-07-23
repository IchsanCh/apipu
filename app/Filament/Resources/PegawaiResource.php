<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pegawai;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PegawaiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PegawaiResource\RelationManagers;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pegawai Details')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Pegawai')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nomor_hp')
                            ->label('Nomor HP')
                            ->tel()
                            ->required()
                            ->maxLength(15),
                        TextInput::make('email')
                            ->label('Email Pegawai')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('posisi')
                            ->label('Posisi Pegawai')
                            ->required()
                            ->maxLength(255),
                        Select::make('unit_id')
                            ->relationship('unit', 'name')
                            ->label(('Unit Instansi'))
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nomor_hp')
                    ->label('Nomor HP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email Pegawai')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('posisi')
                    ->label('Posisi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit.name')
                    ->label('Unit Instansi')
                    ->searchable()
                    ->sortable()
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}
