<?php

namespace App\Filament\Resources\DetailHeads;

use App\Filament\Resources\DetailHeads\Pages\CreateDetailHead;
use App\Filament\Resources\DetailHeads\Pages\EditDetailHead;
use App\Filament\Resources\DetailHeads\Pages\ListDetailHeads;
use App\Filament\Resources\DetailHeads\Pages\ViewDetailHead;
use App\Filament\Resources\DetailHeads\Schemas\DetailHeadForm;
use App\Filament\Resources\DetailHeads\Schemas\DetailHeadInfolist;
use App\Filament\Resources\DetailHeads\Tables\DetailHeadsTable;
use App\Models\DetailHead;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailHeadResource extends Resource
{
    protected static ?string $model = DetailHead::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'description';

    public static function form(Schema $schema): Schema
    {
        return DetailHeadForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DetailHeadInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DetailHeadsTable::configure($table);
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
            'index' => ListDetailHeads::route('/'),
            'create' => CreateDetailHead::route('/create'),
            'view' => ViewDetailHead::route('/{record}'),
            'edit' => EditDetailHead::route('/{record}/edit'),
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
