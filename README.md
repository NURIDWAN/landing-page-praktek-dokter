# Website Praktek Dokter Siti Mudiroh

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-4.x-F59E0B?style=flat&logo=filament&logoColor=white)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-06B6D4?style=flat&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.4%20%7C%208.5-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![DDEV](https://img.shields.io/badge/DDEV-v1.25+-007ACC?style=flat&logo=docker&logoColor=white)](https://ddev.com)
[![Tests](https://img.shields.io/badge/Tests-Passing%20(18%20tests)-10B981?style=flat&logo=githubactions&logoColor=white)](#pengujian)

Website profil dan layanan kesehatan resmi **Praktek Dokter Siti Mudiroh** yang berlokasi di Binong, Curug, Kabupaten Tangerang (dekat Aryana Karawaci). 

Platform ini dirancang khusus untuk mempermudah pasien menemukan informasi jadwal praktek dokter umum, konsultasi Keluarga Berencana (KB), edukasi kesehatan keluarga, serta memberikan kemudahan bagi dokter dan staf dalam mengelola konten situs melalui panel admin **Filament**.

---

## Daftar Isi
- [Fitur Utama](#fitur-utama)
- [Tech Stack](#tech-stack)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Panduan Instalasi & Menjalankan Lokal](#panduan-instalasi--menjalankan-lokal)
- [Akun Pengguna Panel Admin (Default)](#akun-pengguna-panel-admin-default)
- [Perintah Penting & Pengujian](#perintah-penting--pengujian)
- [Struktur Direktori Utama](#struktur-direktori-utama)
- [Lisensi](#lisensi)

---

## Fitur Utama

### 1. Website Publik (Pasien & Pengunjung)
* **Beranda Informatif & Bersih**: Hero section dengan navigasi terstruktur, status buka/tutup dinamis hari ini, serta akses cepat ke WhatsApp praktek.
* **Jadwal & Peta Terpadu (*Unified Container*)**: Jam operasional mingguan berdampingan langsung dengan peta interaktif Google Maps dan rute navigasi.
* **Daftar Layanan Medis**: Menampilkan ragam layanan (pemeriksaan anak, umum dewasa, konsultasi KB/perempuan, surat rujukan & imunisasi) dengan ikon dinamis.
* **Edukasi Medis & Artikel Berjadwal**: Artikel kesehatan terkurasi dengan referensi resmi (Kemenkes, IDAI, BKKBN), gambar sampul otomatis teroptimasi WebP, dan disclaimer etika medis.
* **Formulir Kontak Cepat (UU PDP Compliant)**: Pertanyaan singkat pasien dengan proteksi anti-spam (*honeypot & rate-limiting*) tanpa menyimpan rekam medis sensitif.
* **CTA WhatsApp Resmi**: Tombol chat mengambang (*floating WhatsApp*) dan tombol kontak di setiap halaman dengan tautan langsung ke WhatsApp praktek.
* **Optimasi SEO Lokal & Aksesibilitas**: Skema terstruktur `schema.org/MedicalClinic` (JSON-LD), sitemap XML otomatis, meta tag OpenGraph, serta ramah perangkat seluler (*mobile-first*).

### 2. Panel Admin (Filament v4)
* **Kelola Pengaturan Praktek (*Single Source of Truth*)**: Nama, alamat, nomor WhatsApp, pesan default chat, URL Maps, dan meta SEO.
* **Manajemen Jam Praktek & Libur Khusus**: Pengaturan jam operasional mingguan serta penandaan libur nasional/cuti praktek secara fleksibel.
* **Manajemen Artikel & Kategori**: Penyusunan konten artikel dengan RichEditor (dukungan attachment gambar inline) dan cover upload otomatis terkompresi.
* **Manajemen Pesan Masuk**: Kotak masuk pertanyaan formulir pasien dengan tanda status dibaca dan tindak lanjut.
* **Pustaka Media & Log Aktivitas**: Manajemen berkas dengan Spatie Media Library dan pencatatan audit log perubahan data (Spatie Activitylog).
* **Role-Based Access Control (RBAC)**: Pengelolaan hak akses granular dengan Filament Shield (`super_admin`, `admin`, `editor`).

---

## Tech Stack

* **Framework**: [Laravel 12](https://laravel.com)
* **Admin Panel**: [Filament v4](https://filamentphp.com) + [Filament Shield](https://github.com/bezhanSalleh/filament-shield)
* **Styling**: [Tailwind CSS v4](https://tailwindcss.com) + `@tailwindcss/vite`
* **Interaktivitas Frontend**: [Alpine.js](https://alpinejs.dev)
* **Media & Aktivitas**: [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary) + [Spatie Activitylog](https://spatie.be/docs/laravel-activitylog)
* **Database & Engine**: MariaDB 11.8 / MySQL 8.0+
* **Environment Lokal**: [DDEV](https://ddev.com) (Nginx, PHP 8.4/8.5, MariaDB, Node.js 24, Mailpit)

---

## Persyaratan Sistem

Untuk menjalankan proyek ini di komputer lokal:
* **Docker & DDEV** (sangat direkomendasikan):
  * [Docker Desktop](https://www.docker.com/) atau Docker Engine
  * [DDEV](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/) v1.23+
* *Atau secara Native:*
  * PHP >= 8.2 (ekstensi: `pdo_mysql`, `gd`, `imagick`, `fileinfo`, `mbstring`, `openssl`)
  * Composer 2.x
  * Node.js >= 20.x & npm

---

## Panduan Instalasi & Menjalankan Lokal

### Menggunakan DDEV (Rekomendasi)

1. **Clone Repositori**:
   ```bash
   git clone git@github.com:NURIDWAN/landing-page-praktek-dokter.git
   cd landing-page-praktek-dokter
   ```

2. **Jalankan Container DDEV**:
   ```bash
   ddev start
   ```

3. **Install Dependensi & Konfigurasi Lingkungan**:
   ```bash
   ddev composer install
   ddev exec npm install
   ```

4. **Siapkan File `.env`**:
   Salin `.env.example` ke `.env` dan generate application key:
   ```bash
   cp .env.example .env
   ddev artisan key:generate
   ```
   *Pastikan konfigurasi database di `.env` sudah sesuai dengan DDEV:*
   ```env
   DB_CONNECTION=mariadb
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=db
   DB_USERNAME=db
   DB_PASSWORD=db
   ```

5. **Jalankan Migrasi & Data Seeder Awal**:
   ```bash
   ddev artisan migrate --seed
   ```

6. **Hubungkan Storage & Build Frontend Assets**:
   ```bash
   ddev artisan storage:link
   ddev exec npm run build
   ```

7. **Akses Website**:
   * Website Publik: [https://landing-page-praktek-dokter.ddev.site](https://landing-page-praktek-dokter.ddev.site)
   * Panel Admin Filament: [https://landing-page-praktek-dokter.ddev.site/admin](https://landing-page-praktek-dokter.ddev.site/admin)
   * Mailpit (Inbox email lokal): `ddev mailpit`

---

## Akun Pengguna Panel Admin (Default)

Data pengguna awal telah dibuat melalui seeder `RolePermissionSeeder`:

| Role | Email Login | Password Default | Hak Akses Utama |
|---|---|---|---|
| **Super Admin (Dokter)** | `dokter@praktekdoktersitimudiroh.id` | `PraktekDokter2026!` | Hak akses penuh: Pengaturan, Pengguna, Peran, Jam Praktek, Layanan, Kontak, Artikel |
| **Admin (Staf Praktek)** | `staf@praktekdoktersitimudiroh.id` | `StafPraktek2026!` | Operasional harian: Jam Praktek, Pengumuman, Layanan, Artikel, Pesan Masuk, Pengaturan Konten |
| **Editor (Penulis)** | `editor@praktekdoktersitimudiroh.id` | `EditorEdukasi2026!` | Menulis & mengelola Artikel Edukasi, Kategori Artikel, dan Pustaka Media |

> [!WARNING]
> Pastikan untuk segera mengganti kata sandi default di atas ketika mendeploy sistem ke lingkungan server *production*!

---

## Perintah Penting & Pengujian

### Menjalankan Automated Test
Proyek dilengkapi dengan pengujian fitur (Feature Tests) mencakup form kontak, hak akses Filament, publikasi artikel terjadwal, dan halaman publik:
```bash
# Menjalankan seluruh test suite (18 tests, 114 assertions)
ddev exec php artisan test

# Menjalankan pengujian artikel saja
ddev exec php artisan test --filter Article
```

### Build & Pengembangan Frontend
```bash
# Mode pengembangan (HMR)
ddev exec npm run dev

# Kompilasi aset produksi (Vite + Tailwind CSS v4)
npm run build
```

### Scheduler & Pembersihan Otomatis
```bash
# Mempublikasikan artikel yang sudah masuk jadwal terbit
ddev artisan articles:publish-scheduled

# Membersihkan pesan kontak lama sesuai retensi UU PDP (default: > 365 hari)
ddev artisan contact:prune
```

---

## Struktur Direktori Utama

```text
├── app/
│   ├── Filament/                # Panel Admin (Resources, Pages, Widgets)
│   ├── Http/Controllers/        # Controller Website Publik
│   ├── Models/                  # Eloquent Models (Article, Service, OpeningHour, dll.)
│   ├── Services/                # Service Layer (PracticeData, Schedule, StructuredData)
│   └── Settings/                # Spatie Laravel Settings (PracticeSettings)
├── database/
│   ├── migrations/              # Skema Database
│   ├── seeders/                 # Data Awal Praktek & Role/Permission
│   └── settings/                # Migrasi Pengaturan Dinamis
├── resources/
│   ├── css/app.css              # Konfigurasi Tailwind CSS v4
│   ├── views/
│   │   ├── components/          # Komponen Blade (Icons, Form, Hours Card)
│   │   ├── layouts/             # Master Layout (Header, Footer, Floating CTA)
│   │   └── pages/               # Tampilan Halaman Publik (Home, Services, Articles)
├── routes/
│   ├── web.php                  # Rute Publik & SEO Sitemap
│   └── console.php              # Scheduled Tasks
└── tests/                       # Automated Feature & Unit Tests
```

---

## Lisensi

Aplikasi ini dikembangkan untuk kebutuhan operasional **Praktek Dokter Siti Mudiroh**. Seluruh hak cipta dilindungi. Source code didasari oleh framework [Laravel](https://laravel.com) di bawah lisensi [MIT License](https://opensource.org/licenses/MIT).
