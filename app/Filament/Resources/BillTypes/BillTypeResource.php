<?php

namespace App\Filament\Resources\BillTypes;

use App\Filament\Resources\BillTypes\Pages\CreateBillType;
use App\Filament\Resources\BillTypes\Pages\EditBillType;
use App\Filament\Resources\BillTypes\Pages\ListBillTypes;
use App\Filament\Resources\BillTypes\Pages\ViewBillType;
use App\Filament\Resources\BillTypes\Schemas\BillTypeForm;
use App\Filament\Resources\BillTypes\Schemas\BillTypeInfolist;
use App\Filament\Resources\BillTypes\Tables\BillTypesTable;
use App\Models\BillType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BillTypeResource extends Resource
{
    protected static ?string $model = BillType::class;

    protected static string|UnitEnum|null $navigationGroup = 'Masters';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Square3Stack3d;

    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute = 'description';

    public static function form(Schema $schema): Schema
    {
        return BillTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BillTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BillTypesTable::configure($table);
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
            'index' => ListBillTypes::route('/'),
            'create' => CreateBillType::route('/create'),
            'view' => ViewBillType::route('/{record}'),
            'edit' => EditBillType::route('/{record}/edit'),
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
