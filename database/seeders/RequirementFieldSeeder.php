<?php

namespace Database\Seeders;

use App\Models\RequirementField;
use Illuminate\Database\Seeder;

class RequirementFieldSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            // ── Website ─────────────────────────────
            ['service_category' => 'website', 'field_label' => 'Jenis Website', 'field_name' => 'jenis_website', 'field_type' => 'select', 'field_options' => ['Company Profile', 'Landing Page', 'E-Commerce', 'Web Application', 'Portal/Sistem', 'Blog/Media'], 'is_required' => true, 'sort_order' => 1],
            ['service_category' => 'website', 'field_label' => 'Jumlah Halaman', 'field_name' => 'jumlah_halaman', 'field_type' => 'select', 'field_options' => ['1-5 halaman', '6-10 halaman', '11-20 halaman', '20+ halaman'], 'is_required' => true, 'sort_order' => 2],
            ['service_category' => 'website', 'field_label' => 'Fitur Yang Dibutuhkan', 'field_name' => 'fitur_website', 'field_type' => 'checkbox', 'field_options' => ['Form Kontak', 'Blog/Artikel', 'Payment Gateway', 'Login System', 'Dashboard Admin', 'Multi Bahasa', 'Live Chat', 'SEO Optimization', 'Integrasi WhatsApp', 'Google Analytics'], 'is_required' => false, 'sort_order' => 3],
            ['service_category' => 'website', 'field_label' => 'Referensi Website', 'field_name' => 'referensi_website', 'field_type' => 'textarea', 'placeholder' => 'Masukkan URL website yang Anda suka sebagai referensi...', 'is_required' => false, 'sort_order' => 4],
            ['service_category' => 'website', 'field_label' => 'Sudah Punya Domain?', 'field_name' => 'punya_domain', 'field_type' => 'radio', 'field_options' => ['Ya, sudah punya', 'Belum, butuh bantuan'], 'is_required' => true, 'sort_order' => 5],
            ['service_category' => 'website', 'field_label' => 'Sudah Punya Hosting?', 'field_name' => 'punya_hosting', 'field_type' => 'radio', 'field_options' => ['Ya, sudah punya', 'Belum, butuh bantuan'], 'is_required' => true, 'sort_order' => 6],
            ['service_category' => 'website', 'field_label' => 'Target Deadline', 'field_name' => 'target_deadline', 'field_type' => 'date', 'is_required' => false, 'sort_order' => 7],
            ['service_category' => 'website', 'field_label' => 'Deskripsi Singkat Project', 'field_name' => 'deskripsi_project', 'field_type' => 'textarea', 'placeholder' => 'Ceritakan secara singkat tentang project website Anda...', 'is_required' => false, 'sort_order' => 8],

            // ── Branding ────────────────────────────
            ['service_category' => 'branding', 'field_label' => 'Jenis Branding', 'field_name' => 'jenis_branding', 'field_type' => 'checkbox', 'field_options' => ['Logo', 'Business Card', 'Letterhead', 'Envelope', 'Company Profile', 'Packaging Design', 'Brand Guidelines', 'Stationery Set', 'Merchandise Design'], 'is_required' => true, 'sort_order' => 1],
            ['service_category' => 'branding', 'field_label' => 'Industri Bisnis', 'field_name' => 'industri_bisnis', 'field_type' => 'select', 'field_options' => ['F&B / Kuliner', 'Fashion & Beauty', 'Teknologi', 'Properti', 'Jasa Profesional', 'Kesehatan', 'Pendidikan', 'Retail', 'Manufaktur', 'Lainnya'], 'is_required' => true, 'sort_order' => 2],
            ['service_category' => 'branding', 'field_label' => 'Warna Favorit', 'field_name' => 'warna_favorit', 'field_type' => 'text', 'placeholder' => 'Contoh: Biru navy, emas, putih', 'is_required' => false, 'sort_order' => 3],
            ['service_category' => 'branding', 'field_label' => 'Sudah Punya Logo?', 'field_name' => 'punya_logo', 'field_type' => 'radio', 'field_options' => ['Ya, ingin redesign', 'Ya, ingin tetap pakai', 'Belum punya'], 'is_required' => true, 'sort_order' => 4],
            ['service_category' => 'branding', 'field_label' => 'Gaya Desain Preferensi', 'field_name' => 'gaya_desain_branding', 'field_type' => 'select', 'field_options' => ['Minimalis', 'Modern', 'Elegan/Luxury', 'Playful/Fun', 'Bold/Tegas', 'Klasik/Vintage', 'Corporate/Formal'], 'is_required' => false, 'sort_order' => 5],
            ['service_category' => 'branding', 'field_label' => 'Referensi Desain', 'field_name' => 'referensi_desain', 'field_type' => 'textarea', 'placeholder' => 'Ceritakan brand atau desain yang Anda suka sebagai referensi...', 'is_required' => false, 'sort_order' => 6],
            ['service_category' => 'branding', 'field_label' => 'Target Audience', 'field_name' => 'target_audience_branding', 'field_type' => 'text', 'placeholder' => 'Contoh: Wanita usia 25-35, profesional muda', 'is_required' => false, 'sort_order' => 7],

            // ── Social Media ────────────────────────
            ['service_category' => 'social_media', 'field_label' => 'Platform', 'field_name' => 'platform_sosmed', 'field_type' => 'checkbox', 'field_options' => ['Instagram', 'TikTok', 'Facebook', 'LinkedIn', 'Twitter/X', 'YouTube'], 'is_required' => true, 'sort_order' => 1],
            ['service_category' => 'social_media', 'field_label' => 'Jenis Konten', 'field_name' => 'jenis_konten', 'field_type' => 'checkbox', 'field_options' => ['Feed Post/Carousel', 'Instagram Story', 'Reels/Short Video', 'Copywriting/Caption', 'Content Calendar', 'Ads/Iklan'], 'is_required' => true, 'sort_order' => 2],
            ['service_category' => 'social_media', 'field_label' => 'Jumlah Konten per Bulan', 'field_name' => 'jumlah_konten', 'field_type' => 'select', 'field_options' => ['12 konten/bulan', '20 konten/bulan', '30 konten/bulan', 'Custom (sebutkan di catatan)'], 'is_required' => true, 'sort_order' => 3],
            ['service_category' => 'social_media', 'field_label' => 'Durasi Kontrak', 'field_name' => 'durasi_kontrak', 'field_type' => 'select', 'field_options' => ['1 Bulan (Trial)', '3 Bulan', '6 Bulan', '12 Bulan'], 'is_required' => true, 'sort_order' => 4],
            ['service_category' => 'social_media', 'field_label' => 'Industri Bisnis', 'field_name' => 'industri_sosmed', 'field_type' => 'select', 'field_options' => ['F&B / Kuliner', 'Fashion & Beauty', 'Teknologi', 'Properti', 'Jasa Profesional', 'Kesehatan', 'Pendidikan', 'Retail', 'Lainnya'], 'is_required' => false, 'sort_order' => 5],
            ['service_category' => 'social_media', 'field_label' => 'Sudah Punya Akun?', 'field_name' => 'punya_akun_sosmed', 'field_type' => 'radio', 'field_options' => ['Ya, sudah aktif', 'Ya, tapi belum aktif', 'Belum punya'], 'is_required' => true, 'sort_order' => 6],
            ['service_category' => 'social_media', 'field_label' => 'Target Audience', 'field_name' => 'target_audience_sosmed', 'field_type' => 'text', 'placeholder' => 'Contoh: Pria & wanita usia 18-30, area Jabodetabek', 'is_required' => false, 'sort_order' => 7],
            ['service_category' => 'social_media', 'field_label' => 'Apakah Butuh Ads/Iklan?', 'field_name' => 'butuh_ads', 'field_type' => 'radio', 'field_options' => ['Ya, sekalian manage ads', 'Tidak, organik saja', 'Belum tahu'], 'is_required' => false, 'sort_order' => 8],

            // ── Invitation ──────────────────────────
            ['service_category' => 'invitation', 'field_label' => 'Jenis Acara', 'field_name' => 'jenis_acara', 'field_type' => 'select', 'field_options' => ['Pernikahan', 'Khitanan', 'Ulang Tahun', 'Gathering/Reuni', 'Seminar/Webinar', 'Grand Opening', 'Lainnya'], 'is_required' => true, 'sort_order' => 1],
            ['service_category' => 'invitation', 'field_label' => 'Tanggal Acara', 'field_name' => 'tanggal_acara', 'field_type' => 'date', 'is_required' => true, 'sort_order' => 2],
            ['service_category' => 'invitation', 'field_label' => 'Fitur Undangan', 'field_name' => 'fitur_undangan', 'field_type' => 'checkbox', 'field_options' => ['Galeri Foto', 'Countdown Timer', 'RSVP/Konfirmasi Kehadiran', 'Google Maps', 'Background Music', 'Love Story Timeline', 'Gift/Amplop Digital', 'Video Prewedding', 'QR Code Check-in'], 'is_required' => false, 'sort_order' => 3],
            ['service_category' => 'invitation', 'field_label' => 'Gaya Desain', 'field_name' => 'gaya_desain_undangan', 'field_type' => 'select', 'field_options' => ['Elegan/Mewah', 'Minimalis', 'Tradisional/Adat', 'Floral/Bunga', 'Modern/Clean', 'Rustic', 'Islamic/Islami'], 'is_required' => false, 'sort_order' => 4],
            ['service_category' => 'invitation', 'field_label' => 'Estimasi Jumlah Tamu', 'field_name' => 'jumlah_tamu', 'field_type' => 'select', 'field_options' => ['< 100 tamu', '100-300 tamu', '300-500 tamu', '500+ tamu'], 'is_required' => false, 'sort_order' => 5],
            ['service_category' => 'invitation', 'field_label' => 'Nama Pasangan/Penyelenggara', 'field_name' => 'nama_penyelenggara', 'field_type' => 'text', 'placeholder' => 'Contoh: Budi & Ani', 'is_required' => false, 'sort_order' => 6],
        ];

        foreach ($fields as $field) {
            RequirementField::create($field);
        }
    }
}
