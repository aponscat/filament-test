<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstanceResource\Pages;
use App\Filament\Resources\InstanceResource\RelationManagers;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Editora\InstanceModel;

class InstanceResource extends Resource
{
    protected static ?string $model = InstanceModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $recordTitleAttribute = 'key_fields';

    protected static int $classID=22;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->ofClass(self::$classID);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key_fields'),
                Forms\Components\Select::make('status')
                  ->options([
                    'O' => 'Published'
                    , 'R' => 'In Review'
                    , 'P' => 'Pending'
                  ]),
                Forms\Components\DateTimePicker::make('publishing_begins'),
                Forms\Components\DateTimePicker::make('publishing_ends'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key_fields'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('publishing_begins'),
                Tables\Columns\TextColumn::make('publishing_ends'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListInstances::route('/'),
            'create' => Pages\CreateInstance::route('/create'),
            'edit' => Pages\EditInstance::route('/{record}/edit'),
        ];
    } 
}
