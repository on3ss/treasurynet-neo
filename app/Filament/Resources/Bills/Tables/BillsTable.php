<?php

namespace App\Filament\Resources\Bills\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Primary visible columns
                TextColumn::make('bill_number')
                    ->label(__('Bill No.'))
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('bill_date')
                    ->label(__('Bill Date'))
                    ->dateTime('d-m-Y')
                    ->sortable(),
                TextColumn::make('ddo.code')
                    ->label(__('DDO'))
                    ->searchable(),
                TextColumn::make('billType.description')
                    ->label(__('Bill Type'))
                    ->searchable(),
                TextColumn::make('net_amount')
                    ->label(__('Net Amt'))
                    ->numeric()
                    ->sortable()
                    ->money('INR'),
                TextColumn::make('gross_amount')
                    ->label(__('Gross Amt'))
                    ->numeric()
                    ->sortable()
                    ->money('INR'),
                IconColumn::make('is_urgent')
                    ->label(__('Urgent'))
                    ->boolean(),

                // Secondary columns - hidden by default
                TextColumn::make('financialYear.name')
                    ->label(__('Financial Year'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('treasury.name')
                    ->label(__('Treasury'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('source_bill_id')
                    ->label(__('Source Bill ID'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('paymentScheme.code')
                    ->label(__('Scheme'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('detailHead.code')
                    ->label(__('Detail Head'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('form.name')
                    ->label(__('Form'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('source')
                    ->label(__('Source'))
                    ->formatStateUsing(fn(Model $model) => $model->source->label())
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category')
                    ->label(__('Category'))
                    ->formatStateUsing(fn(Model $model) => $model->category->label())
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_type')
                    ->label(__('Payment Type'))
                    ->formatStateUsing(fn(Model $model) => $model->payment_type->label())
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Timestamps - hidden by default
                TextColumn::make('created_at')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime('d-m-Y H:i')
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