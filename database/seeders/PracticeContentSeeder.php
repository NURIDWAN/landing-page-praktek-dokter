<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\OpeningHour;
use App\Models\Page;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class PracticeContentSeeder extends Seeder
{
    public function run(): void
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        foreach ($days as $iso => $name) {
            OpeningHour::updateOrCreate(
                ['day_of_week' => $iso],
                [
                    'day_name' => $name,
                    'open_time' => '16:00:00',
                    'close_time' => '20:00:00',
                    'is_closed' => false,
                    'notes' => $iso === 7 ? 'Jadwal dapat menyesuaikan pengumuman libur' : null,
                ]
            );
        }

        $services = [
            [
                'title' => 'Pemeriksaan Kesehatan Anak',
                'slug' => 'pemeriksaan-kesehatan-anak',
                'summary' => 'Evaluasi demam, batuk pilek, infeksi ringan, gangguan pencernaan, serta pemantauan tumbuh kembang anak.',
                'description' => 'Pemeriksaan menyeluruh untuk bayi, balita, dan anak usia sekolah dengan pendekatan yang sabar dan ramah anak. Dokter juga memberikan penjelasan mengenai tanda bahaya yang perlu dipantau orang tua di rumah serta panduan perawatan harian.',
                'icon' => 'child',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Pemeriksaan Kesehatan Dewasa & Lansia',
                'slug' => 'pemeriksaan-kesehatan-dewasa',
                'summary' => 'Konsultasi keluhan umum harian, cek tekanan darah, kontrol gula darah, dan pemantauan keluhan kronis ringan.',
                'description' => 'Layanan pemeriksaan dokter umum untuk keluhan infeksi saluran napas, gangguan lambung, alergi, nyeri otot/sendi, serta pemantauan hipertensi dan diabetes tipe 2 secara berkala dengan edukasi gaya hidup yang realistis.',
                'icon' => 'stethoscope',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Konsultasi & Layanan KB',
                'slug' => 'konsultasi-layanan-kb',
                'summary' => 'Konseling pemilihan metode kontrasepsi yang sesuai kondisi ibu serta layanan suntik dan kontrol KB.',
                'description' => 'Pendampingan perencanaan keluarga secara privat dan informatif agar pasangan memahami pilihan kontrasepsi, jadwal kontrol ulang, serta penyesuaian bila muncul efek samping ringan.',
                'icon' => 'heart-handshake',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Surat Keterangan Sehat & Rujukan',
                'slug' => 'surat-keterangan-sehat-rujukan',
                'summary' => 'Pemeriksaan fisik untuk kebutuhan administrasi sekolah/kerja serta surat rujukan medis bila diperlukan.',
                'description' => 'Pemeriksaan tanda vital dan kondisi fisik dasar sesuai standar medis untuk keperluan sekolah, melamar pekerjaan, atau rujukan lanjutan ke fasilitas kesehatan tingkat lanjut apabila terindikasi secara klinis.',
                'icon' => 'clipboard-pulse',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['slug' => $service['slug']], $service);
        }

        $categories = [
            ['name' => 'Kesehatan Anak', 'slug' => 'kesehatan-anak', 'description' => 'Panduan perawatan anak, demam, dan tumbuh kembang.', 'sort_order' => 1],
            ['name' => 'Kesehatan Dewasa', 'slug' => 'kesehatan-dewasa', 'description' => 'Pencegahan dan pemantauan penyakit harian dewasa.', 'sort_order' => 2],
            ['name' => 'Keluarga Berencana (KB)', 'slug' => 'keluarga-berencana', 'description' => 'Edukasi perencanaan keluarga dan metode kontrasepsi.', 'sort_order' => 3],
            ['name' => 'Kesehatan Perempuan', 'slug' => 'kesehatan-perempuan', 'description' => 'Kesehatan reproduksi dan kebugaran perempuan.', 'sort_order' => 4],
        ];

        $categoryMap = [];
        foreach ($categories as $category) {
            $categoryMap[$category['slug']] = ArticleCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $doctor = User::where('email', 'dokter@praktekdoktersitimudiroh.id')->first();

        $articles = [
            [
                'category_id' => $categoryMap['kesehatan-anak']->id,
                'author_id' => $doctor?->id,
                'reviewed_by' => $doctor?->id,
                'title' => 'Kapan Demam pada Anak Perlu Segera Diperiksakan ke Dokter?',
                'slug' => 'kapan-demam-anak-perlu-diperiksakan-ke-dokter',
                'excerpt' => 'Panduan praktis bagi orang tua untuk mengenali demam yang masih bisa dipantau di rumah dan tanda bahaya yang membutuhkan pemeriksaan dokter segera.',
                'body' => '<p>Demam sebenarnya merupakan respons alami tubuh anak ketika melawan infeksi virus maupun bakteri. Meski demikian, rasa khawatir orang tua sangat wajar muncul, terutama bila suhu naik pada malam hari.</p><h2>Langkah Pertama di Rumah</h2><ul><li>Pastikan anak cukup minum air putih, ASI, atau cairan rehidrasi agar terhindar dari dehidrasi.</li><li>Gunakan pakaian yang tipis dan nyaman, serta jaga sirkulasi udara kamar tetap sejuk.</li><li>Kompres hangat pada lipatan ketiak atau leher dapat membantu anak merasa lebih nyaman.</li></ul><h2>Tanda Bahaya yang Perlu Diwaspadai</h2><p>Segera bawa anak ke dokter atau IGD terdekat apabila demam disertai napas cepat atau sesak, anak tampak sangat lemas sulit dibangunkan, kejang, ruam merah yang tidak memudar saat ditekan, atau bayi berusia di bawah 3 bulan mengalami demam ≥ 38°C.</p>',
                'source_name' => 'Ikatan Dokter Anak Indonesia (IDAI) & Kemenkes RI',
                'source_url' => 'https://www.idai.or.id/artikel/klinik/keluhan-anak/penanganan-demam-pada-anak',
                'status' => Article::STATUS_PUBLISHED,
                'published_at' => now('Asia/Jakarta')->subDays(4),
                'reviewed_at' => now('Asia/Jakarta')->subDays(4),
                'meta_title' => 'Kapan Demam Anak Perlu Diperiksakan? Panduan Orang Tua',
                'meta_description' => 'Kenali tanda bahaya demam pada anak dan langkah penanganan awal yang aman di rumah menurut panduan medis keluarga.',
            ],
            [
                'category_id' => $categoryMap['kesehatan-dewasa']->id,
                'author_id' => $doctor?->id,
                'reviewed_by' => $doctor?->id,
                'title' => 'Mengenal Tekanan Darah Tinggi: Gejala Tersembunyi & Cara Mengontrolnya',
                'slug' => 'mengenal-tekanan-darah-tinggi-dan-cara-mengontrolnya',
                'excerpt' => 'Hipertensi sering berlangsung tanpa keluhan jelas. Ketahui angka tekanan darah normal dan langkah sederhana menjaga kestabilannya.',
                'body' => '<p>Banyak pasien dewasa baru mengetahui tekanan darahnya tinggi saat menjalani pemeriksaan rutin. Kondisi ini dikenal sebagai <em>silent condition</em> karena kerap tidak memunculkan nyeri kepala atau keluhan spesifik di tahap awal.</p><h2>Batas Tekanan Darah yang Perlu Dipantau</h2><p>Secara umum, tekanan darah normal dewasa berada di bawah 120/80 mmHg. Bila hasil pengukuran berulang mencapai 140/90 mmHg atau lebih, dokter akan mengevaluasi faktor risiko serta rencana pemantauan berkala.</p><h2>Kebiasaan Harian Pendukung</h2><ul><li>Kurangi konsumsi garam berlebih, makanan olahan, dan gorengan.</li><li>Aktif bergerak minimal 30 menit sehari sebanyak 5 hari per minggu.</li><li>Konsumsi obat penurun tekanan darah sesuai anjuran dokter tanpa menghentikannya secara mendadak.</li></ul>',
                'source_name' => 'Kementerian Kesehatan RI — Direktorat P2PTM',
                'source_url' => 'https://p2ptm.kemkes.go.id/',
                'status' => Article::STATUS_PUBLISHED,
                'published_at' => now('Asia/Jakarta')->subDays(11),
                'reviewed_at' => now('Asia/Jakarta')->subDays(11),
                'meta_title' => 'Mengenal Tekanan Darah Tinggi & Cara Mengontrolnya',
                'meta_description' => 'Penjelasan praktis mengenai batas tekanan darah normal, pencegahan hipertensi, dan pentingnya kontrol rutin ke dokter.',
            ],
            [
                'category_id' => $categoryMap['keluarga-berencana']->id,
                'author_id' => $doctor?->id,
                'reviewed_by' => $doctor?->id,
                'title' => 'Memilih Metode KB yang Tepat: Hal Penting untuk Dikonsultasikan',
                'slug' => 'memilih-metode-kb-yang-tepat-bersama-dokter',
                'excerpt' => 'Setiap metode kontrasepsi memiliki karakteristik berbeda. Pertimbangkan riwayat kesehatan, rencana kehamilan, dan kenyamanan ibu sebelum memilih.',
                'body' => '<p>Perencanaan kehamilan membantu menjaga kesehatan ibu dan tumbuh kembang anak secara optimal. Tidak ada satu metode KB yang otomatis paling cocok untuk semua orang karena kondisi tubuh dan kebutuhan setiap keluarga berbeda.</p><h2>Faktor yang Dinilai Saat Konsultasi</h2><ul><li>Apakah ibu sedang menyusui aktif atau memiliki riwayat tekanan darah tinggi.</li><li>Rencana jarak kehamilan berikutnya (jangka pendek atau jangka panjang).</li><li>Kenyamanan terhadap jadwal kontrol (pil harian, suntik 1 atau 3 bulan, atau metode jangka panjang).</li></ul><p>Melalui konseling langsung bersama dokter, pasangan dapat membandingkan manfaat serta potensi efek samping ringan seperti perubahan pola haid sehingga tidak merasa cemas di kemudian hari.</p>',
                'source_name' => 'BKKBN & Pedoman Pelayanan Kontrasepsi Kemenkes RI',
                'source_url' => 'https://www.bkkbn.go.id/',
                'status' => Article::STATUS_PUBLISHED,
                'published_at' => now('Asia/Jakarta')->subDays(18),
                'reviewed_at' => now('Asia/Jakarta')->subDays(18),
                'meta_title' => 'Memilih Metode KB yang Tepat — Konsultasi Keluarga',
                'meta_description' => 'Tips berkonsultasi memilih metode KB yang aman dan nyaman sesuai kondisi kesehatan ibu dan rencana keluarga.',
            ],
            [
                'category_id' => $categoryMap['kesehatan-perempuan']->id,
                'author_id' => $doctor?->id,
                'reviewed_by' => $doctor?->id,
                'title' => 'Menjaga Kesehatan Reproduksi & Pola Istirahat Ibu Bekerja maupun Ibu Rumah Tangga',
                'slug' => 'menjaga-kesehatan-perempuan-dan-pola-istirahat-harian',
                'excerpt' => 'Aktivitas harian yang padat sering membuat perempuan menunda pemeriksaan kesehatan. Kenali sinyal tubuh dan langkah pencegahan dasar.',
                'body' => '<p>Perempuan sering memprioritaskan kesehatan anak dan anggota keluarga lainnya sambil mengabaikan keluhan kelelahan berkepanjangan, anemia ringan, atau gangguan siklus menstruasi yang dialami sendiri.</p><h2>Kebiasaan Sehat yang Dapat Dimulai Minggu Ini</h2><ul><li>Penuhi asupan zat besi, protein, dan cairan cukup sepanjang hari.</li><li>Catat tanggal siklus haid setiap bulan agar perubahan pola lebih mudah dievaluasi saat berkonsultasi.</li><li>Segera periksakan bila mengalami keputihan berbau menyengat, nyeri panggul menetap, atau perdarahan di luar jadwal haid.</li></ul>',
                'source_name' => 'Kementerian Kesehatan RI — Ayo Sehat',
                'source_url' => 'https://ayosehat.kemkes.go.id/',
                'status' => Article::STATUS_PUBLISHED,
                'published_at' => now('Asia/Jakarta')->subDays(25),
                'reviewed_at' => now('Asia/Jakarta')->subDays(25),
                'meta_title' => 'Menjaga Kesehatan Perempuan di Tengah Aktivitas Harian',
                'meta_description' => 'Langkah sederhana menjaga daya tahan tubuh, siklus haid, dan kapan perempuan perlu berkonsultasi ke dokter.',
            ],
        ];

        foreach ($articles as $article) {
            Article::updateOrCreate(['slug' => $article['slug']], $article);
        }

        $pages = [
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'meta_title' => 'Kebijakan Privasi — Praktek Dokter Siti Mudiroh',
                'meta_description' => 'Kebijakan privasi dan pelindungan data pribadi pengunjung pada situs Praktek Dokter Siti Mudiroh sesuai UU PDP.',
                'is_published' => true,
                'body' => '<h2>1. Komitmen Pelindungan Data Pribadi</h2><p>Praktek Dokter Siti Mudiroh menghargai privasi setiap pengunjung dan pasien. Pengelolaan informasi pada situs ini mengacu pada prinsip kehati-hatian dan kepatuhan terhadap Undang-Undang Pelindungan Data Pribadi (UU PDP).</p><h2>2. Data yang Dikumpulkan melalui Formulir Kontak</h2><p>Formulir kontak publik hanya mengumpulkan data minimum yang diperlukan untuk menjawab pertanyaan umum Anda, yaitu: <strong>Nama Lengkap</strong>, <strong>Nomor HP/WhatsApp</strong>, dan <strong>Pesan Singkat</strong>. Kami mengimbau pengunjung untuk tidak menuliskan rincian keluhan medis sensitif atau hasil pemeriksaan laboratorium di formulir situs publik.</p><h2>3. Tujuan Penggunaan & Retensi Data</h2><p>Data pesan digunakan semata-mata oleh dokter atau staf praktek untuk merespons pertanyaan jadwal, lokasi, atau informasi layanan. Data pesan disimpan secara terbatas dan dihapus secara berkala maksimal setelah 12 bulan sejak diterima.</p><h2>4. Hak Subjek Data</h2><p>Anda berhak meminta pembaruan atau penghapusan pesan yang pernah dikirimkan melalui formulir ini dengan menghubungi kontak resmi praktek di 0853-1022-6518.</p>',
            ],
            [
                'title' => 'Syarat & Ketentuan',
                'slug' => 'syarat-ketentuan',
                'meta_title' => 'Syarat & Ketentuan — Praktek Dokter Siti Mudiroh',
                'meta_description' => 'Ketentuan penggunaan informasi kesehatan dan layanan komunikasi daring pada situs Praktek Dokter Siti Mudiroh.',
                'is_published' => true,
                'body' => '<h2>1. Tujuan Informasi Situs</h2><p>Seluruh artikel edukasi kesehatan, informasi layanan, dan jadwal pada situs Praktek Dokter Siti Mudiroh disediakan untuk membantu masyarakat mendapatkan akses informasi kesehatan dasar yang tepercaya.</p><h2>2. Bukan Pengganti Konsultasi Medis Langsung</h2><p>Konten artikel tidak dimaksudkan untuk menggantikan pemeriksaan fisik, penegakan diagnosis, maupun pemberian resep oleh dokter secara langsung. Dalam kondisi gawat darurat medis, harap segera menuju Instalasi Gawat Darurat (IGD) rumah sakit terdekat.</p><h2>3. Perubahan Jadwal Praktek</h2><p>Jam praktek dapat berubah sewaktu-waktu apabila terdapat hari libur nasional atau keperluan mendesak dokter. Informasi perubahan jadwal akan diumumkan melalui banner pengumuman dan halaman Lokasi & Jadwal.</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }

        Announcement::updateOrCreate(
            ['title' => 'Jadwal Praktek Sore Reguler'],
            [
                'message' => 'Praktek Dokter Siti Mudiroh buka setiap hari pukul 16.00 – 20.00 WIB. Konfirmasi jadwal khusus melalui WhatsApp 0853-1022-6518.',
                'link_label' => 'Lihat Lokasi & Jam',
                'link_url' => '/lokasi-jadwal',
                'is_active' => false,
            ]
        );
    }
}
