<?php

namespace App\Filament\Resources\Ddos;

use App\Filament\Resources\Ddos\Pages\CreateDdo;
use App\Filament\Resources\Ddos\Pages\EditDdo;
use App\Filament\Resources\Ddos\Pages\ListDdos;
use App\Filament\Resources\Ddos\Pages\ViewDdo;
use App\Filament\Resources\Ddos\Schemas\DdoForm;
use App\Filament\Resources\Ddos\Schemas\DdoInfolist;
use App\Filament\Resources\Ddos\Tables\DdosTable;
use App\Models\Ddo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DdoResource extends Resource
{
    protected static ?string $model = Ddo::class;

    public static function getModelLabel(): string
    {
        return __('DDO');
    }

    public static function getPluralModelLabel(): string
    {
        return __('DDOs');
    }

    protected static string|UnitEnum|null $navigationGroup = 'Masters';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::HomeModern;

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'designation';

    public static function form(Schema $schema): Schema
    {
        return DdoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DdoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DdosTable::configure($table);
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
            'index' => ListDdos::route('/'),
            'create' => CreateDdo::route('/create'),
            'view' => ViewDdo::route('/{record}'),
            'edit' => EditDdo::route('/{record}/edit'),
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
