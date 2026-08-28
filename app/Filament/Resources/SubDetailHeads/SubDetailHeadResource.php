<?php

namespace App\Filament\Resources\SubDetailHeads;

use App\Filament\Resources\SubDetailHeads\Pages\CreateSubDetailHead;
use App\Filament\Resources\SubDetailHeads\Pages\EditSubDetailHead;
use App\Filament\Resources\SubDetailHeads\Pages\ListSubDetailHeads;
use App\Filament\Resources\SubDetailHeads\Pages\ViewSubDetailHead;
use App\Filament\Resources\SubDetailHeads\Schemas\SubDetailHeadForm;
use App\Filament\Resources\SubDetailHeads\Schemas\SubDetailHeadInfolist;
use App\Filament\Resources\SubDetailHeads\Tables\SubDetailHeadsTable;
use App\Models\SubDetailHead;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class SubDetailHeadResource extends Resource
{
    protected static ?string $model = SubDetailHead::class;

    protected static string|UnitEnum|null $navigationGroup = 'Masters';

    protected static ?int $navigationSort = 6;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'description';

    public static function form(Schema $schema): Schema
    {
        return SubDetailHeadForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SubDetailHeadInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubDetailHeadsTable::configure($table);
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
            'index' => ListSubDetailHeads::route('/'),
            'create' => CreateSubDetailHead::route('/create'),
            'view' => ViewSubDetailHead::route('/{record}'),
            'edit' => EditSubDetailHead::route('/{record}/edit'),
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
