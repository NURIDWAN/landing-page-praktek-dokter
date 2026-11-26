<?php

namespace App\Filament\Pages;

use App\Settings\PracticeSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class ManagePracticeSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan & Akses';

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    protected static ?string $title = 'Pengaturan Situs & Praktek';

    protected static ?int $navigationSort = 1;

    protected static string $settings = PracticeSettings::class;

    public static function canAccess(): bool
    {
        return Auth::user()?->can('View:ManagePracticeSettings') ?? false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Pengaturan Praktek')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Identitas & Hero')
                            ->schema([
                                Section::make('Identitas Utama')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('practice_name')
                                            ->label('Nama Praktek')
                                            ->required()
                                            ->maxLength(150),
                                        TextInput::make('tagline')
                                            ->label('Tagline')
                                            ->required()
                                            ->maxLength(255),
                                        FileUpload::make('logo')
                                            ->label('Logo Praktek (Opsional)')
                                            ->disk('public')
                                            ->directory('settings')
                                            ->image()
                                            ->helperText('Jika kosong, logo disembunyikan dan hanya nama praktek yang tampil.'),
                                        FileUpload::make('favicon')
                                            ->label('Favicon Website (Opsional)')
                                            ->disk('public')
                                            ->directory('settings')
                                            ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon', 'image/png', 'image/svg+xml', 'image/webp', 'image/jpeg'])
                                            ->maxSize(1024)
                                            ->helperText('Ikon tab browser (32x32 / 64x64 px, format ICO, PNG, SVG, atau WEBP).'),
                                        FileUpload::make('hero_image')
                                            ->label('Foto Hero Praktek (Opsional)')
                                            ->disk('public')
                                            ->directory('settings')
                                            ->image()
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('Konten Hero Beranda')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('hero_badge')
                                            ->label('Label Badge Hero')
                                            ->required()
                                            ->maxLength(120),
                                        TextInput::make('hero_title')
                                            ->label('Judul Utama Hero')
                                            ->required()
                                            ->maxLength(200)
                                            ->columnSpanFull(),
                                        Textarea::make('hero_description')
                                            ->label('Deskripsi Hero')
                                            ->required()
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TextInput::make('hero_primary_cta')
                                            ->label('Teks CTA Primer')
                                            ->required()
                                            ->maxLength(60),
                                        TextInput::make('hero_secondary_cta')
                                            ->label('Teks CTA Sekunder')
                                            ->required()
                                            ->maxLength(60),
                                    ]),
                            ]),

                        Tab::make('Kontak, Alamat & Peta')
                            ->schema([
                                Section::make('Kontak Resmi')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('phone')
                                            ->label('Nomor Telepon (Tampil)')
                                            ->required()
                                            ->maxLength(30),
                                        TextInput::make('whatsapp')
                                            ->label('Nomor WhatsApp (Format 628xxx)')
                                            ->required()
                                            ->maxLength(30),
                                        TextInput::make('email')
                                            ->label('Email Notifikasi Staf')
                                            ->email()
                                            ->required()
                                            ->maxLength(150),
                                        TextInput::make('google_rating_text')
                                            ->label('Teks Rating Google Terverifikasi (Opsional)')
                                            ->helperText('Kosongkan jika hanya ingin menautkan langsung ke profil Google Maps.')
                                            ->maxLength(80),
                                        Textarea::make('whatsapp_default_message')
                                            ->label('Pesan Awal WhatsApp Default')
                                            ->required()
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('Lokasi & Google Maps')
                                    ->columns(2)
                                    ->schema([
                                        Textarea::make('address')
                                            ->label('Alamat Lengkap Praktek')
                                            ->required()
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        TextInput::make('latitude')
                                            ->label('Latitude')
                                            ->required()
                                            ->maxLength(30),
                                        TextInput::make('longitude')
                                            ->label('Longitude')
                                            ->required()
                                            ->maxLength(30),
                                        TextInput::make('maps_url')
                                            ->label('URL Petunjuk Arah Google Maps')
                                            ->url()
                                            ->required()
                                            ->columnSpanFull(),
                                        TextInput::make('maps_embed_url')
                                            ->label('URL Embed Google Maps (iframe src)')
                                            ->url()
                                            ->required()
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Profil Dokter & Area Layanan')
                            ->schema([
                                Section::make('Profil Dokter & Catatan Pasien')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('doctor_name')
                                            ->label('Nama Dokter')
                                            ->required()
                                            ->maxLength(120),
                                        TextInput::make('doctor_role')
                                            ->label('Peran / Profesi')
                                            ->required()
                                            ->maxLength(80),
                                        TextInput::make('sip_number')
                                            ->label('Nomor SIP/STR (Opsional, sesuai persetujuan dokter)')
                                            ->maxLength(120),
                                        FileUpload::make('doctor_photo')
                                            ->label('Foto Dokter (Opsional)')
                                            ->disk('public')
                                            ->directory('settings')
                                            ->image(),
                                        Textarea::make('doctor_bio')
                                            ->label('Profil Singkat Dokter')
                                            ->required()
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Textarea::make('doctor_notes')
                                            ->label('Catatan untuk Pasien')
                                            ->required()
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                                Section::make('Area Layanan (SEO Lokal) & Etika Medis')
                                    ->schema([
                                        TextInput::make('service_area_title')
                                            ->label('Judul Blok Area Layanan')
                                            ->required()
                                            ->maxLength(150),
                                        Textarea::make('service_area_text')
                                            ->label('Deskripsi Area Layanan (Binong, Curug, sekitar Aryana Karawaci)')
                                            ->required()
                                            ->rows(3),
                                        Textarea::make('medical_disclaimer')
                                            ->label('Disclaimer Medis')
                                            ->required()
                                            ->rows(3),
                                    ]),
                            ]),

                        Tab::make('SEO Default & Sosial Media')
                            ->schema([
                                Section::make('Meta SEO Default')
                                    ->schema([
                                        TextInput::make('default_meta_title')
                                            ->label('Default Meta Title')
                                            ->required()
                                            ->maxLength(160),
                                        Textarea::make('default_meta_description')
                                            ->label('Default Meta Description')
                                            ->required()
                                            ->rows(3)
                                            ->maxLength(320),
                                    ]),
                                Section::make('Tautan Media Sosial (Opsional)')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('instagram_url')
                                            ->label('URL Instagram')
                                            ->url()
                                            ->maxLength(255),
                                        TextInput::make('facebook_url')
                                            ->label('URL Facebook')
                                            ->url()
                                            ->maxLength(255),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
