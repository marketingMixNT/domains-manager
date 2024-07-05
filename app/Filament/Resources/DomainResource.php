<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Host;
use Filament\Tables;
use App\Models\Domain;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DomainResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DomainResource\RelationManagers;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // section
                Section::make('Nazwa i url')
                    ->columns(2)
                    ->schema([
                        TextInput::make('client')
                            ->label('Nazwa klienta')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255),
                        TextInput::make('site_url')
                            ->label('Adres strony')
                            ->minLength(3)
                            ->maxLength(255)
                            ->url()
                            ->helperText('Adres url musi zawierać http lub https'),
                    ]),
                // section
                Section::make('Usługa i dostawca')
                    ->columns(2)
                    ->schema([
                        Select::make('service')
                            ->label('Usługa')
                            ->required()
                            ->options([
                                'null' => 'brak',
                                'domain' => 'Domena',
                                'server' => 'Serwer',
                                'complex' => 'Domena + Serwer',
                            ]),
                        Select::make('host_id')
                            ->relationship('host', 'id')
                            ->preload()
                            ->options(Host::all()->pluck('name', 'id'))
                            ->createOptionForm(Host::getForm())
                            ->editOptionForm(Host::getForm()),
                    ]),
                // section
                Section::make('Zakres czasowy')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Data rozpoczęcia')
                            ->live()
                            ->default(now())
                            ->afterStateUpdated(function ($state, callable $set) {
                                $newDate = Carbon::parse($state)->addYear()->format('Y-m-d');
                                $set('end_date', $newDate);
                            }),
                        DatePicker::make('end_date')
                            ->label('Data zakończenia')

                    ]),
                // section
                Section::make('Faktura')
                    ->columns(2)
                    ->schema([
                        Toggle::make('invoice')
                            ->label('Faktura')
                            ->live()
                            ->columnSpanFull(),
                        DatePicker::make('invoice_date')
                            ->label('Data wystawienia')
                            ->visible(fn (Get $get) => $get('invoice') === true)
                            ->default(now())
                            ->columns(1),
                    ]),

                TextInput::make('google_drive')
                    ->url()
                    ->helperText('Adres url musi zawierać http lub https')
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client')
                    ->label('Klient')
                    ->description(function (Domain $record) {
                        return $record->site_url;
                    })
                    ->searchable(),

                TextColumn::make('service')
                    ->label('Usługa')
                    ->sortable(),
                TextColumn::make('host.name')
                    ->label('Dostawca')
                    ->sortable(),
                IconColumn::make('invoice')
                    ->label('Faktura')
                    ->boolean(),
                TextColumn::make('start_date')
                    ->label('Data rozpoczęcia')
                    ->dateTime()
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y');
                    })
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Data zakończenia')
                    ->dateTime()
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('d-m-Y');
                    })
                    ->color(function ($state) {
                        $endDate = Carbon::parse($state);
                        $currentDate = Carbon::now();
                        $oneMonthBeforeEndDate = $endDate->copy()->subMonth();
                        $threeMonthsBeforeEndDate = $endDate->copy()->subMonths(3);

                        if ($currentDate->greaterThanOrEqualTo($oneMonthBeforeEndDate)) {
                            return 'danger';
                        } elseif ($currentDate->greaterThanOrEqualTo($threeMonthsBeforeEndDate)) {
                            return 'warning';
                        } else {
                            return 'success';
                        }
                    })
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return ('Domeny');
    }
    public static function getPluralLabel(): string
    {
        return ('Domeny');
    }

    public static function getLabel(): string
    {
        return ('Domena');
    }
}
