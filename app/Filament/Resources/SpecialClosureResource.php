<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialClosureResource\Pages;
use App\Models\SpecialClosure;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
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

class SpecialClosureResource extends Resource
{
    protected static ?string $model = SpecialClosure::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Operasional Praktek';

    protected static ?string $navigationLabel = 'Libur & Jadwal Khusus';

    protected static ?string $modelLabel = 'Jadwal Khusus';

    protected static ?string $pluralModelLabel = 'Libur & Jadwal Khusus';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Libur atau Jadwal Khusus')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('title')
                            ->label('Judul (mis. Libur Idul Fitri / Jadwal Sore Maju)')
                            ->required()
                            ->maxLength(150),
                        Toggle::make('is_closed')
                            ->label('Praktek Tutup Seharian')
                            ->default(true)
                            ->columnSpanFull(),
                        TimePicker::make('custom_open_time')
                            ->label('Jam Buka Khusus (jika tidak tutup)')
                            ->seconds(false),
                        TimePicker::make('custom_close_time')
                            ->label('Jam Tutup Khusus (jika tidak tutup)')
                            ->seconds(false),
                        Textarea::make('reason')
                            ->label('Keterangan untuk Pasien')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Keterangan')
                    ->searchable(),
                IconColumn::make('is_closed')
                    ->label('Tutup Total')
                    ->boolean(),
                TextColumn::make('custom_open_time')
                    ->label('Buka Khusus')
                    ->time('H:i')
                    ->placeholder('-'),
                TextColumn::make('custom_close_time')
                    ->label('Tutup Khusus')
                    ->time('H:i')
                    ->placeholder('-'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpecialClosures::route('/'),
            'create' => Pages\CreateSpecialClosure::route('/create'),
            'edit' => Pages\EditSpecialClosure::route('/{record}/edit'),
        ];
    }
}
