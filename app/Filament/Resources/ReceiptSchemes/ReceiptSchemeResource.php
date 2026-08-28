<?php

namespace App\Filament\Resources\ReceiptSchemes;

use App\Filament\Resources\ReceiptSchemes\Pages\CreateReceiptScheme;
use App\Filament\Resources\ReceiptSchemes\Pages\EditReceiptScheme;
use App\Filament\Resources\ReceiptSchemes\Pages\ListReceiptSchemes;
use App\Filament\Resources\ReceiptSchemes\Pages\ViewReceiptScheme;
use App\Filament\Resources\ReceiptSchemes\Schemas\ReceiptSchemeForm;
use App\Filament\Resources\ReceiptSchemes\Schemas\ReceiptSchemeInfolist;
use App\Filament\Resources\ReceiptSchemes\Tables\ReceiptSchemesTable;
use App\Models\ReceiptScheme;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ReceiptSchemeResource extends Resource
{
    protected static ?string $model = ReceiptScheme::class;

    protected static string|UnitEnum|null $navigationGroup = 'Masters';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'description';

    public static function form(Schema $schema): Schema
    {
        return ReceiptSchemeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReceiptSchemeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReceiptSchemesTable::configure($table);
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
            'index' => ListReceiptSchemes::route('/'),
            'create' => CreateReceiptScheme::route('/create'),
            'view' => ViewReceiptScheme::route('/{record}'),
            'edit' => EditReceiptScheme::route('/{record}/edit'),
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
