<?php

namespace App\Filament\Resources\PaymentSchemes;

use App\Filament\Resources\PaymentSchemes\Pages\CreatePaymentScheme;
use App\Filament\Resources\PaymentSchemes\Pages\EditPaymentScheme;
use App\Filament\Resources\PaymentSchemes\Pages\ListPaymentSchemes;
use App\Filament\Resources\PaymentSchemes\Pages\ViewPaymentScheme;
use App\Filament\Resources\PaymentSchemes\Schemas\PaymentSchemeForm;
use App\Filament\Resources\PaymentSchemes\Schemas\PaymentSchemeInfolist;
use App\Filament\Resources\PaymentSchemes\Tables\PaymentSchemesTable;
use App\Models\PaymentScheme;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PaymentSchemeResource extends Resource
{
    protected static ?string $model = PaymentScheme::class;

    protected static string|UnitEnum|null $navigationGroup = 'Masters';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'description';

    public static function form(Schema $schema): Schema
    {
        return PaymentSchemeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentSchemeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentSchemesTable::configure($table);
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
            'index' => ListPaymentSchemes::route('/'),
            'create' => CreatePaymentScheme::route('/create'),
            'view' => ViewPaymentScheme::route('/{record}'),
            'edit' => EditPaymentScheme::route('/{record}/edit'),
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
