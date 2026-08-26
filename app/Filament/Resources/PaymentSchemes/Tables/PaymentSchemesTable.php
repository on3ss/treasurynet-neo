<?php

namespace App\Filament\Resources\PaymentSchemes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PaymentSchemesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('demand_number')
                    ->searchable(),
                TextColumn::make('major_head')
                    ->searchable(),
                TextColumn::make('submajor_head')
                    ->searchable(),
                TextColumn::make('minor_head')
                    ->searchable(),
                TextColumn::make('subminor_head')
                    ->searchable(),
                TextColumn::make('sub_head')
                    ->searchable(),
                TextColumn::make('charged_or_voted')
                    ->searchable(),
                TextColumn::make('flag')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('sector_code')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
