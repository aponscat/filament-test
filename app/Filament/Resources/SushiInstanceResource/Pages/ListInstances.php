<?php

namespace App\Filament\Resources\SushiInstanceResource\Pages;

use App\Filament\Resources\SushiInstanceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstances extends ListRecords
{
    protected static string $resource = SushiInstanceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
