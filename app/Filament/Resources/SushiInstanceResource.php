<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SushiInstanceResource\Pages;
use App\Filament\Resources\SushiInstanceResource\RelationManagers;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Editora\Models\Sushi\InstanceModel;

class SushiInstanceResource extends Resource
{
    protected static ?string $model = InstanceModel::class;
    protected static ?string $navigationLabel = 'Editora Sushi Home';
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $recordTitleAttribute = 'key_fields';

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
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('key_fields')->label('Key')->sortable(),
                Tables\Columns\TextColumn::make('class.name')->sortable(),
                Tables\Columns\TextColumn::make('status')->view('editora.status'),
                Tables\Columns\TextColumn::make('publishing_begins')->label('Begin')->view('editora.begin'),
                Tables\Columns\TextColumn::make('publishing_ends')->label('End')->view('editora.end'),
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
