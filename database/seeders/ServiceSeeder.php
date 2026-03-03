<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $companyId = \App\Models\Company::first()?->id;

        $services = [
            [
                'name' => 'Website Company Profile',
                'category' => 'website',
                'base_price' => 5000000,
                'billing_type' => 'one_time',
                'description' => 'Website company profile profesional dengan desain modern dan responsif',
            ],
            [
                'name' => 'Website E-Commerce',
                'category' => 'website',
                'base_price' => 15000000,
                'billing_type' => 'one_time',
                'description' => 'Website toko online lengkap dengan payment gateway',
            ],
            [
                'name' => 'Website Landing Page',
                'category' => 'website',
                'base_price' => 3000000,
                'billing_type' => 'one_time',
                'description' => 'Landing page untuk campaign atau produk tertentu',
            ],
            [
                'name' => 'Logo Design',
                'category' => 'branding',
                'base_price' => 2500000,
                'billing_type' => 'one_time',
                'description' => 'Desain logo profesional termasuk brand guideline',
            ],
            [
                'name' => 'Brand Identity Package',
                'category' => 'branding',
                'base_price' => 7500000,
                'billing_type' => 'one_time',
                'description' => 'Paket lengkap: logo, kartu nama, kop surat, amplop, brand guideline',
            ],
            [
                'name' => 'Social Media Management - Basic',
                'category' => 'social_media',
                'base_price' => 2500000,
                'billing_type' => 'recurring',
                'description' => 'Kelola 1 platform, 12 posting/bulan, desain feed & story',
            ],
            [
                'name' => 'Social Media Management - Pro',
                'category' => 'social_media',
                'base_price' => 5000000,
                'billing_type' => 'recurring',
                'description' => 'Kelola 2 platform, 24 posting/bulan, content plan, reporting bulanan',
            ],
            [
                'name' => 'Social Media Management - Enterprise',
                'category' => 'social_media',
                'base_price' => 10000000,
                'billing_type' => 'recurring',
                'description' => 'Kelola semua platform, unlimited posting, ads management, monthly report',
            ],
            [
                'name' => 'Digital Invitation - Standard',
                'category' => 'invitation',
                'base_price' => 350000,
                'billing_type' => 'one_time',
                'description' => 'Undangan digital standar dengan galeri foto dan RSVP',
            ],
            [
                'name' => 'Digital Invitation - Premium',
                'category' => 'invitation',
                'base_price' => 750000,
                'billing_type' => 'one_time',
                'description' => 'Undangan digital premium dengan animasi, musik, dan gift registry',
            ],
        ];

        foreach ($services as $service) {
            Service::create(array_merge($service, ['company_id' => $companyId]));
        }
    }
}
