<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use UnitEnum;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Konten Publik';

    protected static ?string $navigationLabel = 'Artikel Edukasi';

    protected static ?string $modelLabel = 'Artikel';

    protected static ?string $pluralModelLabel = 'Artikel Edukasi';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Artikel')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Artikel')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? '')))
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->maxLength(220)
                            ->unique(ignoreRecord: true),
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Textarea::make('excerpt')
                            ->label('Ringkasan Artikel')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label('Isi Artikel Edukasi')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('articles/content')
                            ->fileAttachmentsVisibility('public')
                            ->required()
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->label('Gambar Sampul')
                            ->collection('cover')
                            ->disk('public')
                            ->image()
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->helperText('Format: JPG, PNG, atau WEBP. Maksimal 5MB. Otomatis dikonversi ke WebP untuk performa optimal.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Sumber Rujukan & Etika Medis (Wajib)')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('source_name')
                            ->label('Nama Sumber / Referensi Medis (mis. Kemenkes RI, IDAI, BKKBN)')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('source_url')
                            ->label('URL Sumber Rujukan (Opsional)')
                            ->url()
                            ->maxLength(500),
                    ]),

                Section::make('Status Penerbitan & SEO')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                Article::STATUS_DRAFT => 'Draft',
                                Article::STATUS_REVIEW => 'Menunggu Tinjauan Dokter',
                                Article::STATUS_SCHEDULED => 'Terjadwal',
                                Article::STATUS_PUBLISHED => 'Terbit',
                            ])
                            ->default(Article::STATUS_DRAFT)
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Terbit / Jadwal Terbit')
                            ->seconds(false)
                            ->timezone('Asia/Jakarta'),
                        TextInput::make('meta_title')
                            ->label('Meta Title SEO (Opsional)')
                            ->maxLength(180),
                        TextInput::make('meta_description')
                            ->label('Meta Description SEO (Opsional)')
                            ->maxLength(320),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')
                    ->label('Sampul')
                    ->collection('cover')
                    ->square(),
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50)
                    ->weight('semibold'),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Article::STATUS_DRAFT => 'Draft',
                        Article::STATUS_REVIEW => 'Ditinjau',
                        Article::STATUS_SCHEDULED => 'Terjadwal',
                        Article::STATUS_PUBLISHED => 'Terbit',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        Article::STATUS_PUBLISHED => 'success',
                        Article::STATUS_SCHEDULED => 'info',
                        Article::STATUS_REVIEW => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('published_at')
                    ->label('Tanggal Terbit')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('source_name')
                    ->label('Sumber')
                    ->limit(25),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        Article::STATUS_DRAFT => 'Draft',
                        Article::STATUS_REVIEW => 'Ditinjau',
                        Article::STATUS_SCHEDULED => 'Terjadwal',
                        Article::STATUS_PUBLISHED => 'Terbit',
                    ]),
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('approve_publish')
                    ->label('Terbitkan')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->visible(fn (Article $record): bool => $record->status !== Article::STATUS_PUBLISHED && (Auth::user()?->hasAnyRole(['super_admin', 'admin']) ?? false))
                    ->requiresConfirmation()
                    ->action(function (Article $record): void {
                        $record->update([
                            'status' => Article::STATUS_PUBLISHED,
                            'reviewed_by' => Auth::id(),
                            'reviewed_at' => now(),
                            'published_at' => $record->published_at ?? now(),
                        ]);
                    }),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
