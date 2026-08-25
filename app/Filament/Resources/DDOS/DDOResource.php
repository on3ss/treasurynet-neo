<?php

namespace App\Filament\Resources\DDOS;

use App\Filament\Resources\DDOS\Pages\CreateDDO;
use App\Filament\Resources\DDOS\Pages\EditDDO;
use App\Filament\Resources\DDOS\Pages\ListDDOS;
use App\Filament\Resources\DDOS\Pages\ViewDDO;
use App\Filament\Resources\DDOS\Schemas\DDOForm;
use App\Filament\Resources\DDOS\Schemas\DDOInfolist;
use App\Filament\Resources\DDOS\Tables\DDOSTable;
use App\Models\DDO;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DDOResource extends Resource
{
    protected static ?string $model = DDO::class;

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
        return DDOForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DDOInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DDOSTable::configure($table);
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
            'index' => ListDDOS::route('/'),
            'create' => CreateDDO::route('/create'),
            'view' => ViewDDO::route('/{record}'),
            'edit' => EditDDO::route('/{record}/edit'),
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
