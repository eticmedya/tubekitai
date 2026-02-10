<?php

namespace App\Filament\Resources;

use App\Enums\PaymentStatus;
use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('credit_package_id')
                            ->relationship('creditPackage', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('merchant_oid')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->suffix('kuruş'),
                        Forms\Components\TextInput::make('credits_purchased')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options(collect(PaymentStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->getLabel()]))
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creditPackage.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('merchant_oid')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn ($state) => number_format($state / 100, 2) . ' ₺')
                    ->sortable(),
                Tables\Columns\TextColumn::make('credits_purchased')
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (PaymentStatus $state): string => $state->getColor()),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(PaymentStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->getLabel()])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
