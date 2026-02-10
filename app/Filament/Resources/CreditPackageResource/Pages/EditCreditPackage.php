<?php

namespace App\Filament\Resources\CreditPackageResource\Pages;

use App\Filament\Resources\CreditPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCreditPackage extends EditRecord
{
    protected static string $resource = CreditPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
