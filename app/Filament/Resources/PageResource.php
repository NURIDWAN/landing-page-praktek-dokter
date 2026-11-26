<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Konten Publik';

    protected static ?string $navigationLabel = 'Halaman Statis';

    protected static ?string $modelLabel = 'Halaman';

    protected static ?string $pluralModelLabel = 'Halaman Statis';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Halaman')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Halaman')
                            ->required()
                            ->maxLength(150)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->prefix(url('/halaman').'/')
                            ->helperText('Dapat diakses melalui /halaman/{slug} maupun langsung /{slug}.')
                            ->required()
                            ->maxLength(160)
                            ->dehydrateStateUsing(fn (?string $state): string => Str::slug((string) $state))
                            ->unique(ignoreRecord: true),
                        RichEditor::make('body')
                            ->label('Isi Halaman')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('pages/content')
                            ->fileAttachmentsVisibility('public')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('meta_title')
                            ->label('Meta Title SEO')
                            ->maxLength(180),
                        TextInput::make('meta_description')
                            ->label('Meta Description SEO')
                            ->maxLength(320),
                        Toggle::make('is_published')
                            ->label('Terbitkan Halaman')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->weight('semibold'),
                TextColumn::make('slug')
                    ->label('URL Halaman')
                    ->formatStateUsing(fn (string $state): string => '/halaman/'.$state)
                    ->copyable()
                    ->copyableState(fn (Page $record): string => route('pages.show', $record->slug)),
                IconColumn::make('is_published')
                    ->label('Terbit')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since(),
            ])
            ->recordActions([
                Action::make('view_page')
                    ->label('Lihat')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (Page $record): string => route('pages.show', $record->slug), shouldOpenInNewTab: true)
                    ->visible(fn (Page $record): bool => (bool) $record->is_published),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
