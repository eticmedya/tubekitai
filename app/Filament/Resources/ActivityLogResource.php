<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Activity Logs';

    protected static ?string $modelLabel = 'Activity Log';

    protected static ?string $pluralModelLabel = 'Activity Logs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Activity Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('action')
                            ->required(),
                        Forms\Components\TextInput::make('model_type'),
                        Forms\Components\TextInput::make('model_id'),
                        Forms\Components\Textarea::make('data')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('ip_address'),
                        Forms\Components\Textarea::make('user_agent')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable()
                    ->url(
                        fn(ActivityLog $record): ?string =>
                        $record->user_id ? UserResource::getUrl('edit', ['record' => $record->user_id]) : null
                    )
                    ->color('primary'),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('E-posta')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('action')
                    ->label('İşlem')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'login' => '🔐 Giriş',
                        'logout' => '🚪 Çıkış',
                        'register' => '📝 Kayıt',
                        'password_reset' => '🔑 Şifre Sıfırlama',
                        'purchase' => '💳 Satın Alma',
                        'channel_analysis' => '📊 Kanal Analizi',
                        'video_analysis' => '🎬 Video Analizi',
                        'comment_analysis' => '💬 Yorum Analizi',
                        'cover_analysis' => '🖼️ Kapak Analizi',
                        'niche_analysis' => '🎯 Niş Analizi',
                        'translation' => '🌐 Çeviri',
                        'ai_generation' => '🤖 AI İçerik',
                        'credit_purchase' => '💰 Kredi Satın Alma',
                        'credit_usage' => '📉 Kredi Kullanımı',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'login', 'register' => 'success',
                        'logout' => 'gray',
                        'password_reset' => 'warning',
                        'purchase', 'credit_purchase' => 'info',
                        'channel_analysis', 'video_analysis', 'comment_analysis', 'cover_analysis', 'niche_analysis' => 'primary',
                        'translation', 'ai_generation' => 'purple',
                        'credit_usage' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('model_type')
                    ->label('İlgili Model')
                    ->formatStateUsing(fn(?string $state): string => $state ? class_basename($state) : '-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Adresi')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Kullanıcı'),
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'login' => 'Giriş',
                        'logout' => 'Çıkış',
                        'register' => 'Kayıt',
                        'password_reset' => 'Şifre Sıfırlama',
                        'purchase' => 'Satın Alma',
                        'channel_analysis' => 'Kanal Analizi',
                        'video_analysis' => 'Video Analizi',
                        'comment_analysis' => 'Yorum Analizi',
                        'cover_analysis' => 'Kapak Analizi',
                        'niche_analysis' => 'Niş Analizi',
                        'translation' => 'Çeviri',
                        'ai_generation' => 'AI İçerik',
                        'credit_purchase' => 'Kredi Satın Alma',
                        'credit_usage' => 'Kredi Kullanımı',
                    ])
                    ->label('İşlem Tipi'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Başlangıç'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Bitiş'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn($query, $date) => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn($query, $date) => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->columns(2),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Aktivite Detayı'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Kullanıcı Bilgileri')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Kullanıcı'),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label('E-posta'),
                    ])->columns(2),
                Infolists\Components\Section::make('Aktivite Detayları')
                    ->schema([
                        Infolists\Components\TextEntry::make('action')
                            ->label('İşlem')
                            ->badge(),
                        Infolists\Components\TextEntry::make('model_type')
                            ->label('İlgili Model')
                            ->formatStateUsing(fn(?string $state): string => $state ? class_basename($state) : '-'),
                        Infolists\Components\TextEntry::make('model_id')
                            ->label('Model ID'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Tarih')
                            ->dateTime('d M Y H:i:s'),
                    ])->columns(2),
                Infolists\Components\Section::make('Ek Bilgiler')
                    ->schema([
                        Infolists\Components\TextEntry::make('data')
                            ->label('Detay Verisi')
                            ->formatStateUsing(fn($state) => $state ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '-')
                            ->columnSpanFull()
                            ->prose(),
                        Infolists\Components\TextEntry::make('ip_address')
                            ->label('IP Adresi'),
                        Infolists\Components\TextEntry::make('user_agent')
                            ->label('Tarayıcı Bilgisi')
                            ->columnSpanFull()
                            ->wrap(),
                    ]),
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
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
