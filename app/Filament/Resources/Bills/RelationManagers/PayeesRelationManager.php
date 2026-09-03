<?php

namespace App\Filament\Resources\Bills\RelationManagers;

use App\Filament\Columns\MoneyColumn;
use App\Filament\Summarizers\MoneySum;
use App\Models\Payee;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayeesRelationManager extends RelationManager
{
    protected static string $relationship = 'payees';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make([
                'default' => 1,
                'md' => 2,
            ])
                ->columnSpanFull()
                ->schema([
                    self::payeeInformation(),
                    self::bankInformation(),
                    self::rbiInformation(),
                ]),
        ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make([
                'default' => 1,
                'lg' => 2,
            ])
                ->columnSpanFull()
                ->schema([
                    self::payeeInfolistSection(),
                    self::bankInfolistSection(),

                    self::rbiInfolistSection()
                        ->columnSpanFull(),

                    self::auditInfolistSection()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('reg_code')
                    ->label('Registration code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                MoneyColumn::make('amount')
                    ->sortable()
                    ->summarize(MoneySum::make()),

                TextColumn::make('ifsc')
                    ->label('IFSC')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('account_no')
                    ->label('Account number')
                    ->formatStateUsing(
                        fn (?string $state): string => self::maskAccountNumber($state)
                    ),

                TextColumn::make('rbi_endtoend_id')
                    ->label('RBI End-to-end ID')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('rbi_utr_id')
                    ->label('RBI UTR')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('rbi_utr_date')
                    ->label('UTR date')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalWidth('5xl'),
                EditAction::make()
                    ->modalWidth('5xl'),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(
                fn (Builder $query) => $query->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ])
            );
    }

    private static function payeeInformation(): Section
    {
        return Section::make('Payee Information')
            ->icon('heroicon-o-user')
            ->columns(2)
            ->schema([
                TextInput::make('reg_code')
                    ->label('Registration code')
                    ->required(),

                TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('amount')
                    ->numeric()
                    ->required(),
            ]);
    }

    private static function bankInformation(): Section
    {
        return Section::make('Bank Information')
            ->icon('heroicon-o-building-library')
            ->columns(2)
            ->schema([
                TextInput::make('ifsc')
                    ->label('IFSC')
                    ->required()
                    ->maxLength(11),

                TextInput::make('account_no')
                    ->label('Account number')
                    ->required()
                    ->password()
                    ->revealable(),
            ]);
    }

    private static function rbiInformation(): Section
    {
        return Section::make('RBI Information')
            ->icon('heroicon-o-arrow-path')
            ->columns(3)
            ->schema([
                TextInput::make('rbi_endtoend_id')
                    ->label('RBI End-to-end ID'),

                TextInput::make('rbi_utr_id')
                    ->label('RBI UTR ID'),

                DatePicker::make('rbi_utr_date')
                    ->label('RBI UTR date'),
            ]);
    }

    private static function payeeInfolistSection(): Section
    {
        return Section::make('Payee Information')
            ->icon('heroicon-o-user')
            ->columns(2)
            ->schema([
                TextEntry::make('reg_code')
                    ->label('Registration code'),

                TextEntry::make('name')
                    ->label('Name')
                    ->columnSpanFull(),

                TextEntry::make('amount')
                    ->label('Amount')
                    ->money('INR'),
            ]);
    }

    private static function bankInfolistSection(): Section
    {
        return Section::make('Bank Information')
            ->icon('heroicon-o-building-library')
            ->columns(2)
            ->schema([
                TextEntry::make('ifsc')
                    ->label('IFSC'),

                TextEntry::make('account_no')
                    ->label('Account number')
                    ->formatStateUsing(
                        fn (?string $state): string => self::maskAccountNumber($state)
                    ),
            ]);
    }

    private static function rbiInfolistSection(): Section
    {
        return Section::make('RBI Information')
            ->icon('heroicon-o-arrow-path')
            ->columns(3)
            ->schema([
                TextEntry::make('rbi_endtoend_id')
                    ->label('RBI End-to-end ID')
                    ->placeholder('-'),

                TextEntry::make('rbi_utr_id')
                    ->label('RBI UTR ID')
                    ->placeholder('-'),

                TextEntry::make('rbi_utr_date')
                    ->label('RBI UTR date')
                    ->date()
                    ->placeholder('-'),
            ]);
    }

    private static function auditInfolistSection(): Section
    {
        return Section::make('Audit Information')
            ->icon('heroicon-o-clock')
            ->columns(3)
            ->schema([
                TextEntry::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label('Last updated')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('deleted_at')
                    ->label('Deleted')
                    ->dateTime()
                    ->placeholder('-')
                    ->visible(
                        fn (Payee $record): bool => $record->trashed()
                    ),
            ]);
    }

    private static function maskAccountNumber(?string $accountNumber): string
    {
        if (blank($accountNumber)) {
            return '-';
        }

        $length = strlen($accountNumber);

        if ($length <= 4) {
            return str_repeat('•', $length);
        }

        return str_repeat('•', $length - 4)
            .substr($accountNumber, -4);
    }
}
