<?php

namespace App\Filament\Resources\ActivityLogResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action since logs are auto-generated
        ];
    }

    public function getTitle(): string
    {
        return 'Kullanıcı Aktiviteleri';
    }

    public function getSubheading(): ?string
    {
        return 'Kullanıcıların tüm işlemlerini buradan takip edebilirsiniz';
    }
}
