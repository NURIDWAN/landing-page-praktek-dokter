<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('practice.practice_name', 'Praktek Dokter Siti Mudiroh');
        $this->migrator->add('practice.tagline', 'Pelayanan Dokter Umum Keluarga yang Ramah, Jelas, dan Terjangkau di Binong, Curug');
        $this->migrator->add('practice.hero_badge', 'Dokter Umum Keluarga · Binong, Curug');
        $this->migrator->add('practice.hero_title', 'Konsultasi Dokter Umum yang Tenang & Terpercaya untuk Keluarga di Binong');
        $this->migrator->add('practice.hero_description', 'Praktek Dokter Siti Mudiroh melayani pemeriksaan kesehatan anak dan dewasa, konsultasi Keluarga Berencana (KB), serta edukasi kesehatan harian dengan jadwal sore yang mudah dijangkau warga Binong, Curug, dan sekitar Aryana Karawaci.');
        $this->migrator->add('practice.hero_primary_cta', 'Petunjuk arah');
        $this->migrator->add('practice.hero_secondary_cta', 'Hubungi praktek');
        $this->migrator->add('practice.hero_image', null);
        $this->migrator->add('practice.logo', null);

        $this->migrator->add('practice.doctor_name', 'dr. Siti Mudiroh');
        $this->migrator->add('practice.doctor_role', 'Dokter Umum');
        $this->migrator->add('practice.doctor_bio', 'Melayani konsultasi dan pemeriksaan kesehatan umum bagi anak-anak, dewasa, serta keluarga di wilayah Binong, Kecamatan Curug, Kabupaten Tangerang. Mengutamakan komunikasi yang jelas, pemeriksaan teliti, dan edukasi pencegahan penyakit bagi pasien dan orang tua.');
        $this->migrator->add('practice.doctor_notes', 'Pasien dapat datang langsung pada jam praktek sore hari. Mohon membawa catatan obat yang sedang dikonsumsi atau buku KIA/imunisasi apabila membawa anak untuk pemeriksaan.');
        $this->migrator->add('practice.doctor_photo', null);
        $this->migrator->add('practice.sip_number', null);

        $this->migrator->add('practice.phone', '0853-1022-6518');
        $this->migrator->add('practice.whatsapp', '6285310226518');
        $this->migrator->add('practice.whatsapp_default_message', 'Halo Praktek Dokter Siti Mudiroh, saya ingin bertanya mengenai informasi jadwal praktek hari ini.');
        $this->migrator->add('practice.email', 'kontak@praktekdoktersitimudiroh.id');
        $this->migrator->add('practice.address', 'Binong, Kec. Curug, Kabupaten Tangerang, Banten 15810');
        $this->migrator->add('practice.service_area_title', 'Melayani Warga Binong, Curug & Sekitar Aryana Karawaci');
        $this->migrator->add('practice.service_area_text', 'Lokasi praktek strategis dan mudah diakses oleh pasien dari Kelurahan Binong, Kecamatan Curug, kawasan perumahan sekitar Aryana Karawaci, Sukabakti, Kadu, hingga wilayah Tangerang sekitarnya yang membutuhkan layanan dokter umum pada sore hari.');
        $this->migrator->add('practice.maps_url', 'https://www.google.com/maps/search/?api=1&query=Praktek+Dokter+Siti+Mudiroh+Binong+Curug+Tangerang');
        $this->migrator->add('practice.maps_embed_url', 'https://www.google.com/maps?q=Binong,+Curug,+Tangerang,+Banten&output=embed');
        $this->migrator->add('practice.latitude', '-6.235500');
        $this->migrator->add('practice.longitude', '106.585500');
        $this->migrator->add('practice.google_rating_text', null);

        $this->migrator->add('practice.medical_disclaimer', 'Informasi kesehatan pada situs ini bersifat edukatif dan bukan pengganti pemeriksaan fisik, diagnosis, atau terapi langsung dari dokter. Untuk kondisi gawat darurat (sesak napas berat, nyeri dada, kejang, perdarahan hebat, atau penurunan kesadaran), segera menuju IGD rumah sakit terdekat atau hubungi 119.');
        $this->migrator->add('practice.default_meta_title', 'Praktek Dokter Siti Mudiroh — Dokter Umum Binong, Curug, Tangerang');
        $this->migrator->add('practice.default_meta_description', 'Praktek dokter umum dr. Siti Mudiroh di Binong, Curug, Kabupaten Tangerang (dekat Aryana Karawaci). Melayani pemeriksaan anak, dewasa, dan konsultasi KB pukul 16.00–20.00 WIB.');
        $this->migrator->add('practice.instagram_url', null);
        $this->migrator->add('practice.facebook_url', null);
    }
};
