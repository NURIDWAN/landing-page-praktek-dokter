# PRD — Website Praktek Dokter Siti Mudiroh

**Stack:** Laravel 11 + Filament 3 (Admin Panel) + MySQL
**Versi:** 1.0 (Draft) · **Tanggal:** 25 November 2026
**Sumber awal:** landing page statis `landing_page_praktek_dokter_siti_mudiroh.html`

---

## 1. Ringkasan

Praktek Dokter Siti Mudiroh (Binong, Curug, Kabupaten Tangerang) saat ini hanya memiliki landing page HTML statis. Seluruh konten (jam praktek, kontak, layanan, artikel) tertanam di kode sehingga setiap perubahan membutuhkan edit file manual.

Produk ini mengubahnya menjadi website dinamis berbasis **Laravel** dengan **Filament** sebagai panel admin, sehingga dokter/staf dapat mengelola informasi praktek, artikel edukasi, dan pesan pasien tanpa menyentuh kode, sambil mempertahankan tampilan dan SEO lokal yang sudah ada.

## 2. Tujuan & Metrik Keberhasilan

| Tujuan | Metrik | Target (3 bulan setelah rilis) |
|---|---|---|
| Pasien mudah menemukan & menghubungi praktek | Klik tombol telepon/WhatsApp/Maps | ≥ 150 klik/bulan |
| Peringkat pencarian lokal | Posisi untuk "praktek dokter Binong Tangerang" | Top 5 hasil lokal |
| Konten dikelola mandiri oleh staf | Perubahan konten tanpa developer | 100% |
| Performa | Skor Lighthouse mobile | ≥ 90 (Performance, SEO, Accessibility) |
| Edukasi keluarga | Artikel dipublikasikan | ≥ 2 artikel/bulan |

**Non-goal (fase 1):** rekam medis elektronik, pembayaran online, telemedicine/video call, integrasi BPJS/SATUSEHAT.

## 3. Pengguna

1. **Calon pasien / orang tua** — mencari praktek dokter terdekat dari ponsel; butuh alamat, jam, telepon, dan layanan dengan cepat.
2. **Pembaca artikel** — mencari informasi kesehatan anak, dewasa, KB, kesehatan perempuan.
3. **Admin / staf praktek** — memperbarui jam, pengumuman, artikel, dan membalas pesan.
4. **Dokter (super admin)** — mengawasi seluruh konten dan pengguna admin.

## 4. Ruang Lingkup Fitur

### 4.1 Website Publik

| # | Halaman / Fitur | Deskripsi | Prioritas |
|---|---|---|---|
| P1 | **Beranda** | Hero (judul, deskripsi, CTA "Petunjuk arah" & "Hubungi praktek"), foto praktek, strip kepercayaan (lokasi, kontak), ringkasan layanan, 3 artikel terbaru | Must |
| P2 | **Layanan** | Daftar layanan dinamis (Pemeriksaan anak, dewasa, Konsultasi KB, dll.) dengan ikon & deskripsi | Must |
| P3 | **Tentang Praktek** | Profil dokter, catatan untuk pasien, disclaimer medis | Must |
| P4 | **Lokasi & Jam Praktek** | Alamat, telepon, jam per hari, Google Maps embed, tombol petunjuk arah, banner info libur/jadwal khusus | Must |
| P5 | **Edukasi (Blog)** | Daftar artikel + filter kategori + pencarian; halaman detail artikel dengan sumber/referensi | Must |
| P6 | **Formulir Kontak / Pertanyaan** | Nama, no. HP, pesan; validasi, anti-spam (honeypot + throttling/Turnstile) | Should |
| P7 | **Tombol WhatsApp melayang** | Tautan `wa.me` dengan pesan awal yang bisa diatur | Should |
| P8 | **Area Layanan (SEO lokal)** | Blok teks area: Binong, Curug, sekitar Aryana Karawaci; dikelola dari admin | Must |
| P9 | **Halaman statis** | Kebijakan Privasi, Syarat & Ketentuan (diedit via admin) | Should |
| P10 | **Pendaftaran antrean/janji temu (opsional)** | Pilih tanggal & jam, status dikonfirmasi staf via admin. Hanya jika dokter menginginkannya | Could |
| P11 | **Pengumuman** | Banner di atas situs (mis. "Libur tanggal 1 Desember") dengan jadwal tampil | Should |

### 4.2 Panel Admin (Filament)

Panel di `/admin`, login email+password, dilindungi role/permission.

| # | Resource / Halaman | Fungsi |
|---|---|---|
| A1 | **Dashboard** | Widget: pesan baru belum dibaca, artikel draft, statistik kunjungan ringkas, janji temu hari ini (jika P10 aktif) |
| A2 | **Pengaturan Situs** (halaman Settings) | Nama praktek, tagline, alamat, telepon, WhatsApp, email, koordinat & URL Maps, foto hero, logo, meta SEO default, tautan media sosial, tahun footer otomatis |
| A3 | **Jam Praktek** (Resource/Repeater) | Jam buka per hari, status libur, jadwal khusus/libur tanggal tertentu |
| A4 | **Layanan** (Resource) | Judul, slug, deskripsi, ikon, urutan, aktif/nonaktif |
| A5 | **Artikel** (Resource) | Judul, slug, kategori, ringkasan, isi (rich editor), gambar sampul, sumber/referensi, status (draft/terbit/terjadwal), tanggal terbit, meta SEO |
| A6 | **Kategori Artikel** | Kesehatan anak, dewasa, KB, kesehatan perempuan, dst. |
| A7 | **Pesan Masuk** | Daftar pesan dari formulir; tandai dibaca/ditindaklanjuti; ekspor CSV; hapus sesuai retensi |
| A8 | **Janji Temu** (opsional) | Kalender/daftar, ubah status: menunggu → dikonfirmasi → selesai/batal |
| A9 | **Halaman Statis** | CRUD halaman (kebijakan privasi, dll.) |
| A10 | **Pengumuman** | Teks, tautan, rentang tanggal tampil |
| A11 | **Media** | Pustaka gambar (Spatie Media Library) dengan kompresi otomatis |
| A12 | **Pengguna & Peran** | Super Admin (dokter), Admin (staf), Editor (artikel saja) via Filament Shield / Spatie Permission |
| A13 | **Log Aktivitas** | Siapa mengubah apa (Spatie Activitylog) |

## 5. Alur Pengguna Utama

1. **Pasien baru:** Google → Beranda/Lokasi → lihat jam → klik **Telepon / WhatsApp / Petunjuk arah**.
2. **Pembaca artikel:** Google → detail artikel → CTA "Konsultasikan dengan dokter" → Kontak.
3. **Staf memperbarui jadwal:** login admin → Jam Praktek → ubah → simpan → situs langsung terbarui (cache dibersihkan otomatis).
4. **Staf menerbitkan artikel:** Artikel → Buat → isi & unggah sampul → pilih "Terjadwal" → terbit otomatis (scheduler).

## 6. Model Data (Ringkas)

- `users` (+ roles/permissions)
- `settings` (key–value atau satu baris; dikelola via Filament Settings page)
- `opening_hours` (day_of_week, open_time, close_time, is_closed)
- `special_closures` (date, reason)
- `services` (title, slug, description, icon, sort_order, is_active)
- `article_categories` (name, slug)
- `articles` (title, slug, category_id, excerpt, body, cover_image, source_name, source_url, status, published_at, meta_title, meta_description, author_id)
- `pages` (title, slug, body, is_published)
- `announcements` (message, link, starts_at, ends_at, is_active)
- `contact_messages` (name, phone, message, ip_hash, read_at, handled_at)
- `appointments` (opsional: patient_name, phone, patient_type [anak/dewasa/KB], preferred_date, preferred_time, notes, status)
- `activity_log`, `media` (paket pihak ketiga)

## 7. Arsitektur & Teknologi

| Lapisan | Pilihan |
|---|---|
| Framework | Laravel 11, PHP 8.2+ |
| Admin | Filament 3 (+ Filament Shield untuk role), Spatie Settings plugin |
| Front-end publik | Blade + Tailwind CSS (port tema hijau `#176b55` dari landing page), Alpine.js untuk interaksi ringan; tanpa SPA agar SEO optimal |
| Database | MySQL 8 / MariaDB |
| Media | Spatie Media Library + konversi WebP |
| SEO | `artesaos/seotools` atau manual; sitemap (`spatie/laravel-sitemap`), JSON-LD |
| Cache/Queue | Redis/file cache; queue database untuk email notifikasi |
| Email | SMTP (notifikasi pesan baru ke staf) |
| Hosting | VPS/shared hosting PHP 8.2 + HTTPS (Let's Encrypt); deploy via Git + `composer install --no-dev`, `php artisan optimize` |
| Backup | Backup harian DB + storage (spatie/laravel-backup) |

## 8. Persyaratan SEO (Prioritas Tinggi)

Bagian ini penting karena landing page awal menargetkan pencarian lokal.

- Judul & meta description unik per halaman (default dari Pengaturan Situs, bisa di-override).
- **Structured data JSON-LD:** `Physician` / `MedicalClinic` + `LocalBusiness` (nama, alamat, telepon, geo, `openingHoursSpecification`); `Article` untuk artikel; `BreadcrumbList`.
- Kata kunci lokal alami di konten: *praktek dokter Binong Tangerang, dokter umum Binong Curug, praktek dokter sekitar Aryana Karawaci*. Hindari keyword stuffing.
- URL ramah (`/artikel/{slug}`), canonical, `sitemap.xml`, `robots.txt`, Open Graph/Twitter card.
- Gambar WebP dengan `alt`, lazy loading, dimensi eksplisit.
- Daftarkan situs di Google Search Console & sinkronkan data dengan Google Business Profile (NAP: nama, alamat, telepon konsisten).

## 9. Persyaratan Non-Fungsional

- **Performa:** LCP < 2,5 dtk di jaringan 4G; total halaman < 1 MB; Lighthouse ≥ 90.
- **Responsif:** mobile-first (mayoritas pengunjung dari ponsel).
- **Aksesibilitas:** WCAG 2.1 AA (kontras, label form, navigasi keyboard, `prefers-reduced-motion` seperti di desain awal).
- **Keamanan:** HTTPS wajib, CSRF, validasi input, rate limiting formulir & login, hash password, 2FA admin (opsional via Filament), pembaruan dependensi berkala.
- **Ketersediaan:** uptime ≥ 99%.
- **Pemeliharaan:** kode mengikuti PSR-12, test otomatis untuk fitur kritis (Pest/PHPUnit), CI sederhana.

## 10. Kepatuhan, Privasi & Etika Medis

- **UU Pelindungan Data Pribadi (UU PDP):** formulir hanya mengumpulkan data minimum; sertakan persetujuan eksplisit & tautan Kebijakan Privasi; tetapkan masa retensi pesan (mis. 12 bulan) dan fitur hapus; jangan meminta keluhan medis rinci lewat formulir publik.
- **Disclaimer medis** tampil di artikel dan halaman Tentang (sudah ada pada desain awal): informasi bukan pengganti konsultasi/diagnosis; arahkan ke IGD untuk kondisi darurat.
- **Konten & promosi:** hindari klaim penyembuhan/superlatif dan testimoni pasien berkonotasi iklan, sesuai kode etik kedokteran dan ketentuan promosi layanan kesehatan. Konfirmasi ke dokter sebelum menampilkan nomor izin praktek (SIP/STR) bila ingin dicantumkan.
- **Rating Google:** landing page awal menulis "5,0 · 3 ulasan" secara statis. Jangan di-hardcode; tampilkan hanya bila terverifikasi dan dapat diperbarui dari admin, atau tautkan langsung ke Google Maps.
- **Artikel edukasi:** wajib mencantumkan sumber rujukan (mis. Kemenkes, BKKBN) dan tanggal pembaruan. Artikel sebaiknya ditinjau dokter sebelum terbit (workflow status: Draft → Ditinjau → Terbit).

## 11. Pemetaan dari Landing Page Saat Ini

| Elemen di HTML statis | Menjadi |
|---|---|
| Hero, teks, CTA | Konten beranda dari Pengaturan Situs |
| 3 kartu layanan | Resource **Layanan** |
| 4 kartu artikel (tautan eksternal) | Resource **Artikel** (konten native di situs + sumber rujukan) |
| Alamat, telepon `0853-1022-6518`, jam 16.00–20.00 | **Pengaturan Situs** + **Jam Praktek** |
| Iframe Google Maps | Komponen peta dengan URL dari pengaturan |
| Blok "Area sekitar" | Konten SEO lokal yang dapat diedit |
| Efek gerak (pointer glow, reveal, tilt) | Dipertahankan ringan via CSS/Alpine; nonaktif pada perangkat sentuh dan `prefers-reduced-motion` |
| Tahun footer | Otomatis dari `now()` |
| Duplikasi catatan jam praktek (muncul 2×) | Dirapikan menjadi satu sumber data |

## 12. Rencana Rilis (Estimasi)

| Fase | Isi | Durasi |
|---|---|---|
| **0. Persiapan** | Konfirmasi konten, foto, domain, hosting, keputusan fitur janji temu | 3 hari |
| **1. Fondasi** | Setup Laravel, Filament, auth & role, skema DB, Settings | 4 hari |
| **2. Admin MVP** | Resource Layanan, Jam Praktek, Artikel, Kategori, Pesan, Pengumuman, Halaman | 5 hari |
| **3. Front-end publik** | Port desain ke Blade/Tailwind, semua halaman P1–P9, P11 | 6 hari |
| **4. SEO & performa** | JSON-LD, sitemap, optimasi gambar, cache, Lighthouse | 3 hari |
| **5. QA & UAT** | Uji perangkat, keamanan dasar, UAT bersama staf/dokter, migrasi konten | 4 hari |
| **6. Rilis & pasca-rilis** | Deploy, SSL, Search Console, backup, pelatihan admin singkat | 2 hari |
| **Opsional** | Janji temu online (P10, A8) | +5 hari |

Total MVP ≈ 4 minggu kerja satu developer.

## 13. Kriteria Penerimaan (MVP)

1. Staf dapat mengubah alamat, telepon, dan jam praktek dari admin dan perubahan tampil di situs tanpa deploy.
2. Staf dapat membuat, menjadwalkan, dan menerbitkan artikel lengkap dengan gambar sampul dan sumber rujukan.
3. Formulir kontak menyimpan pesan, mengirim notifikasi email ke staf, dan menolak spam sederhana.
4. Semua halaman publik lolos Lighthouse ≥ 90 (mobile) untuk Performance, SEO, Accessibility.
5. JSON-LD valid pada Rich Results Test; sitemap dapat diakses dan terkirim ke Search Console.
6. Role Editor tidak dapat mengakses Pengaturan Situs maupun Pengguna.
7. Disclaimer medis tampil di halaman Tentang dan semua artikel; persetujuan privasi wajib di formulir.
8. Situs berfungsi baik di Chrome/Safari/Firefox terbaru dan layar 360 px ke atas.

## 14. Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
|---|---|---|
| Konten artikel tidak diperbarui | Situs terlihat mati, SEO stagnan | Jadwal editorial bulanan; artikel terjadwal otomatis |
| Klaim medis/promosi melanggar etika | Reputasi/regulasi | Alur tinjauan dokter, panduan konten |
| Kebocoran data pesan pasien | Pelanggaran UU PDP | Data minimum, retensi, akses berbasis role, HTTPS |
| Ranking turun setelah migrasi URL | Kehilangan trafik | Pertahankan URL utama / redirect 301, pantau Search Console |
| Admin tunggal lupa kredensial | Tidak dapat mengelola | Reset password via email, minimal 2 akun admin |
| Hosting murah lambat | Skor performa buruk | Cache, optimasi gambar, pilih PHP 8.2+ dengan OPcache |

## 15. Pertanyaan Terbuka

1. Apakah fitur **janji temu / antrean online** diperlukan di fase 1?
2. Apakah **WhatsApp** praktek sama dengan nomor telepon `0853-1022-6518`?
3. Apakah jam praktek 16.00–20.00 berlaku **setiap hari**, atau ada hari libur tetap?
4. Apakah dokter bersedia mencantumkan **foto, riwayat pendidikan, dan nomor SIP/STR**?
5. Sudah ada **domain** dan hosting? Apakah situs lama (HTML statis) di-redirect?
6. Siapa yang menulis/meninjau artikel edukasi?
7. Tampilkan **rating Google**? Jika ya, manual atau tautan saja?
8. Apakah perlu **bilingual** (Indonesia/Inggris)? Asumsi saat ini: hanya Indonesia.
