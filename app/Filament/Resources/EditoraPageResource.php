<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EditoraPageResource\Pages;
use App\Filament\Resources\EditoraPageResource\RelationManagers;
use App\Editora\Models\Classes\EditoraPageModel;

class EditoraPageResource extends InstanceResource 
{
  protected static ?string $model = EditoraPageModel::class;

  public static function getPages(): array
  {
      return [
          'index' => Pages\ListEditoraPages::route('/'),
          'create' => Pages\CreateEditoraPage::route('/create'),
          'edit' => Pages\EditEditoraPage::route('/{record}/edit'),
      ];
  } 
}