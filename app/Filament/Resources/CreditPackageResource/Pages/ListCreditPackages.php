<?php

namespace App\Filament\Resources\CreditPackageResource\Pages;

use App\Filament\Resources\CreditPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreditPackages extends ListRecords
{
    protected static string $resource = CreditPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
