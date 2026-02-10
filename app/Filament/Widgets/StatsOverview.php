<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Payment;
use App\Models\Credit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount') / 100;
        $totalCreditsUsed = Credit::where('amount', '<', 0)->sum('amount') * -1;
        $newUsersToday = User::whereDate('created_at', today())->count();

        return [
            Stat::make('Toplam Kullanici', number_format($totalUsers))
                ->description('Kayitli kullanici sayisi')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 12, 18, 25, 32, 45, $totalUsers]),

            Stat::make('Toplam Gelir', number_format($totalRevenue, 2) . ' TL')
                ->description('Tum zamanlar')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([200, 450, 780, 1200, 1800, 2400, $totalRevenue]),

            Stat::make('Kullanilan Kredi', number_format($totalCreditsUsed, 1))
                ->description('Toplam harcanan')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('warning')
                ->chart([50, 120, 280, 450, 680, 900, $totalCreditsUsed]),

            Stat::make('Bugun Kayit', $newUsersToday)
                ->description('Yeni kullanicilar')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),
        ];
    }
}
