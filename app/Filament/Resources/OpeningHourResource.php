<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpeningHourResource\Pages;
use App\Models\OpeningHour;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class OpeningHourResource extends Resource
{
    protected static ?string $model = OpeningHour::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'Operasional Praktek';

    protected static ?string $navigationLabel = 'Jam Praktek';

    protected static ?string $modelLabel = 'Jam Praktek';

    protected static ?string $pluralModelLabel = 'Jam Praktek';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Jadwal Harian')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('day_of_week')
                            ->label('Hari (Urutan ISO)')
                            ->options([
                                1 => '1 - Senin',
                                2 => '2 - Selasa',
                                3 => '3 - Rabu',
                                4 => '4 - Kamis',
                                5 => '5 - Jumat',
                                6 => '6 - Sabtu',
                                7 => '7 - Minggu',
                            ])
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('day_name')
                            ->label('Nama Hari')
                            ->required()
                            ->maxLength(20),
                        TimePicker::make('open_time')
                            ->label('Jam Buka')
                            ->seconds(false),
                        TimePicker::make('close_time')
                            ->label('Jam Tutup')
                            ->seconds(false),
                        Toggle::make('is_closed')
                            ->label('Praktek Tutup pada Hari Ini')
                            ->default(false)
                            ->columnSpanFull(),
                        TextInput::make('notes')
                            ->label('Catatan Jadwal (Opsional)')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('day_of_week')
            ->columns([
                TextColumn::make('day_of_week')
                    ->label('No.')
                    ->sortable(),
                TextColumn::make('day_name')
                    ->label('Hari')
                    ->weight('semibold'),
                TextColumn::make('open_time')
                    ->label('Buka')
                    ->time('H:i')
                    ->placeholder('-'),
                TextColumn::make('close_time')
                    ->label('Tutup')
                    ->time('H:i')
                    ->placeholder('-'),
                IconColumn::make('is_closed')
                    ->label('Libur')
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('success'),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(40)
                    ->placeholder('-'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOpeningHours::route('/'),
            'create' => Pages\CreateOpeningHour::route('/create'),
            'edit' => Pages\EditOpeningHour::route('/{record}/edit'),
        ];
    }
}
