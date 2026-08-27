<?php

namespace App\Filament\Resources\FinancialYears;

use App\Filament\Resources\FinancialYears\Pages\ManageFinancialYears;
use App\Models\FinancialYear;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class FinancialYearResource extends Resource
{
    protected static ?string $model = FinancialYear::class;

    protected static string|UnitEnum|null $navigationGroup = 'Masters';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Calendar;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->disabled(fn (?FinancialYear $record): bool => $record?->is_current ?? false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label(__('Start Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label(__('End Date'))
                    ->date()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean()
                    ->sortable(),
                IconColumn::make('is_current')
                    ->label(__('Current'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(__('Deleted At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('is_active')
                    ->label('status')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFinancialYears::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
