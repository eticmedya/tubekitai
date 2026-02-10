<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreditPackageResource\Pages;
use App\Models\CreditPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CreditPackageResource extends Resource
{
    protected static ?string $model = CreditPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Package Details')
                    ->schema([
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(500),
                        Forms\Components\TextInput::make('credits')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->suffix('kuruş')
                            ->helperText('Price in kuruş (1/100 TL)'),
                        Forms\Components\Select::make('currency')
                            ->options([
                                'TRY' => 'Turkish Lira (₺)',
                                'USD' => 'US Dollar ($)',
                                'EUR' => 'Euro (€)',
                            ])
                            ->default('TRY'),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_popular')
                            ->label('Mark as Popular'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Features')
                    ->schema([
                        Forms\Components\Repeater::make('features')
                            ->schema([
                                Forms\Components\TextInput::make('feature')
                                    ->required(),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('credits')
                    ->numeric(decimalPlaces: 1)
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->formatStateUsing(fn ($state) => number_format($state / 100, 2) . ' ₺')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_popular')
                    ->boolean()
                    ->label('Popular'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCreditPackages::route('/'),
            'create' => Pages\CreateCreditPackage::route('/create'),
            'edit' => Pages\EditCreditPackage::route('/{record}/edit'),
        ];
    }
}
