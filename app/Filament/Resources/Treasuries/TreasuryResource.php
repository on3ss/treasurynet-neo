<?php

namespace App\Filament\Resources\Treasuries;

use App\Filament\Resources\Treasuries\Pages\CreateTreasury;
use App\Filament\Resources\Treasuries\Pages\EditTreasury;
use App\Filament\Resources\Treasuries\Pages\ListTreasuries;
use App\Filament\Resources\Treasuries\Pages\ViewTreasury;
use App\Filament\Resources\Treasuries\Schemas\TreasuryForm;
use App\Filament\Resources\Treasuries\Schemas\TreasuryInfolist;
use App\Filament\Resources\Treasuries\Tables\TreasuriesTable;
use App\Models\Treasury;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TreasuryResource extends Resource
{
    protected static ?string $model = Treasury::class;

    protected static string|UnitEnum|null $navigationGroup = 'Masters';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingLibrary;

    public static function form(Schema $schema): Schema
    {
        return TreasuryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TreasuryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TreasuriesTable::configure($table);
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
            'index' => ListTreasuries::route('/'),
            'create' => CreateTreasury::route('/create'),
            'view' => ViewTreasury::route('/{record}'),
            'edit' => EditTreasury::route('/{record}/edit'),
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
