<?php

namespace App\Filament\Resources\EditoraPageResource\Pages;

use App\Filament\Resources\EditoraPageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEditoraPage extends EditRecord
{
  protected static string $resource = EditoraPageResource::class;

  protected function getActions(): array
  {
      return [
          Actions\DeleteAction::make(),
      ];
  }
}
