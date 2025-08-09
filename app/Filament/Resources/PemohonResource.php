<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemohonResource\Pages;
use App\Filament\Resources\PemohonResource\RelationManagers;
use App\Models\Pemohon;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PemohonResource extends Resource
{
    protected static ?string $model = Pemohon::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pemohon Details')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Pemohon')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nomor_hp')
                            ->label('Nomor HP')
                            ->tel()
                            ->required()
                            ->maxLength(15),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'proses' => 'Process',
                                'selesai' => 'Selesai',
                            ])
                            ->default('active'),
                        Forms\Components\TextInput::make('nama_proses')
                            ->label('Tahapan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('izin_id')
                            ->relationship('izin', 'nama_izin')
                            ->label('Jenis Izin')
                            ->required(),
                        TextInput::make('no_permohonan')
                            ->label('Nomor permohonan')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('link_izin')
                            ->label('Link Izin')
                            ->nullable()
                            ->maxLength(255),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_hp')
                    ->label('Nomor HP')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_proses')
                    ->label('Tahapan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('izin.nama_izin')
                    ->label('Jenis Izin')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('no_permohonan')
                    ->label('Nomor Permohonan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListPemohons::route('/'),
            'create' => Pages\CreatePemohon::route('/create'),
            'edit' => Pages\EditPemohon::route('/{record}/edit'),
        ];
    }
}
