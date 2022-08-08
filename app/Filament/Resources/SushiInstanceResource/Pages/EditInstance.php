<?php

namespace App\Filament\Resources\SushiInstanceResource\Pages;

use App\Filament\Resources\SushiInstanceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstance extends EditRecord
{
    protected static string $resource = SushiInstanceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
