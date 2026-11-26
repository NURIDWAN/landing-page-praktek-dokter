<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|UnitEnum|null $navigationGroup = 'Komunikasi Pasien';

    protected static ?string $navigationLabel = 'Pesan Masuk';

    protected static ?string $modelLabel = 'Pesan Masuk';

    protected static ?string $pluralModelLabel = 'Pesan Masuk Pasien';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = ContactMessage::unread()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Pesan Pengunjung')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')->label('Nama'),
                        TextEntry::make('phone')->label('Nomor HP / WhatsApp'),
                        TextEntry::make('created_at')->label('Dikirim Pada')->dateTime('d M Y H:i'),
                        TextEntry::make('privacy_agreed_at')->label('Persetujuan Privasi')->dateTime('d M Y H:i'),
                        TextEntry::make('read_at')->label('Dibaca Pada')->dateTime('d M Y H:i')->placeholder('Belum dibaca'),
                        TextEntry::make('handled_at')->label('Ditindaklanjuti Pada')->dateTime('d M Y H:i')->placeholder('Belum ditindaklanjuti'),
                        TextEntry::make('message')->label('Isi Pertanyaan')->columnSpanFull(),
                        TextEntry::make('admin_notes')->label('Catatan Staf')->placeholder('-')->columnSpanFull(),
                    ]),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Catatan Tindak Lanjut')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')->label('Nama')->disabled(),
                        TextInput::make('phone')->label('Nomor HP')->disabled(),
                        Textarea::make('message')->label('Pesan')->disabled()->rows(3),
                        Textarea::make('admin_notes')->label('Catatan Staf')->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->weight('semibold'),
                TextColumn::make('phone')
                    ->label('No. HP')
                    ->searchable(),
                TextColumn::make('message')
                    ->label('Pertanyaan')
                    ->limit(50),
                IconColumn::make('read_at')
                    ->label('Dibaca')
                    ->boolean()
                    ->getStateUsing(fn (ContactMessage $record): bool => $record->read_at !== null),
                IconColumn::make('handled_at')
                    ->label('Selesai')
                    ->boolean()
                    ->getStateUsing(fn (ContactMessage $record): bool => $record->handled_at !== null),
                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('read_at')
                    ->label('Status Baca')
                    ->nullable(),
                TernaryFilter::make('handled_at')
                    ->label('Status Tindak Lanjut')
                    ->nullable(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->mutateRecordDataUsing(function (array $data, ContactMessage $record): array {
                        if ($record->read_at === null) {
                            $record->update(['read_at' => now()]);
                        }

                        return $data;
                    }),
                Action::make('mark_read')
                    ->label('Tandai Dibaca')
                    ->icon(Heroicon::OutlinedEnvelopeOpen)
                    ->visible(fn (ContactMessage $record): bool => $record->read_at === null)
                    ->action(fn (ContactMessage $record) => $record->update(['read_at' => now()])),
                Action::make('mark_handled')
                    ->label('Tandai Selesai')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->visible(fn (ContactMessage $record): bool => $record->handled_at === null)
                    ->action(fn (ContactMessage $record) => $record->update([
                        'read_at' => $record->read_at ?? now(),
                        'handled_at' => now(),
                        'handled_by' => Auth::id(),
                    ])),
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
            'index' => Pages\ListContactMessages::route('/'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
}
