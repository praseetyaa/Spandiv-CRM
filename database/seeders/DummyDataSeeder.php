<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Leads
        $leads = [
            ['name' => 'Budi Santoso', 'phone' => '081234567890', 'email' => 'budi@example.com', 'source' => 'instagram', 'service_id' => 1, 'estimated_value' => 5000000, 'urgency_level' => 'medium', 'lead_score' => 45, 'status' => 'closed_won', 'notes' => 'Tertarik website company profile'],
            ['name' => 'Sari Dewi', 'phone' => '081234567891', 'email' => 'sari@example.com', 'source' => 'referral', 'service_id' => 6, 'estimated_value' => 2500000, 'urgency_level' => 'low', 'lead_score' => 35, 'status' => 'closed_won', 'notes' => 'Butuh kelola sosmed toko online'],
            ['name' => 'Ahmad Rizki', 'phone' => '081234567892', 'email' => 'ahmad@example.com', 'source' => 'website', 'service_id' => 9, 'estimated_value' => 350000, 'urgency_level' => 'high', 'lead_score' => 70, 'status' => 'closed_won', 'notes' => 'Nikah bulan depan, butuh undangan digital'],
            ['name' => 'Lisa Permata', 'phone' => '081234567893', 'email' => 'lisa@example.com', 'source' => 'instagram', 'service_id' => 4, 'estimated_value' => 2500000, 'urgency_level' => 'medium', 'lead_score' => 50, 'status' => 'proposal_sent', 'notes' => 'Butuh logo untuk brand fashion baru'],
            ['name' => 'Denny Prasetyo', 'phone' => '081234567894', 'email' => 'denny@example.com', 'source' => 'google', 'service_id' => 2, 'estimated_value' => 15000000, 'urgency_level' => 'medium', 'lead_score' => 55, 'status' => 'negotiation', 'notes' => 'E-commerce untuk toko elektronik'],
            ['name' => 'Maya Anggraini', 'phone' => '081234567895', 'email' => 'maya@example.com', 'source' => 'referral', 'service_id' => 7, 'estimated_value' => 5000000, 'urgency_level' => 'low', 'lead_score' => 30, 'status' => 'contacted', 'notes' => 'Pengen sosmed management pro'],
            ['name' => 'Rudi Hartono', 'phone' => '081234567896', 'email' => null, 'source' => 'whatsapp', 'service_id' => 10, 'estimated_value' => 750000, 'urgency_level' => 'high', 'lead_score' => 65, 'status' => 'new', 'notes' => 'Undangan premium, acara 2 minggu lagi'],
            ['name' => 'Rina Wulandari', 'phone' => '081234567897', 'email' => 'rina@example.com', 'source' => 'instagram', 'service_id' => 5, 'estimated_value' => 7500000, 'urgency_level' => 'medium', 'lead_score' => 40, 'status' => 'closed_lost', 'notes' => 'Brand identity tapi budget tidak cukup'],
        ];

        foreach ($leads as $leadData) {
            Lead::create($leadData);
        }

        // Create Clients (from won leads)
        $clients = [
            ['name' => 'Budi Santoso', 'phone' => '081234567890', 'email' => 'budi@example.com', 'business_name' => 'PT Maju Bersama', 'industry' => 'Manufaktur', 'instagram' => '@majubersama', 'website' => null, 'client_status' => 'active', 'lead_id' => 1],
            ['name' => 'Sari Dewi', 'phone' => '081234567891', 'email' => 'sari@example.com', 'business_name' => 'Sari Fashion Store', 'industry' => 'Fashion', 'instagram' => '@sarifashion', 'website' => 'www.sarifashion.com', 'client_status' => 'active', 'lead_id' => 2],
            ['name' => 'Ahmad Rizki', 'phone' => '081234567892', 'email' => 'ahmad@example.com', 'business_name' => '-', 'industry' => 'Personal', 'instagram' => '@ahmadrizki', 'website' => null, 'client_status' => 'active', 'lead_id' => 3],
            ['name' => 'Teguh Waluyo', 'phone' => '081234567898', 'email' => 'teguh@example.com', 'business_name' => 'CV Teknologi Utama', 'industry' => 'Teknologi', 'instagram' => '@tekutama', 'website' => 'www.tekutama.com', 'client_status' => 'active', 'total_lifetime_value' => 12500000],
            ['name' => 'Fitri Handayani', 'phone' => '081234567899', 'email' => 'fitri@example.com', 'business_name' => 'Fitri Catering', 'industry' => 'F&B', 'instagram' => '@fitricatering', 'website' => null, 'client_status' => 'active', 'total_lifetime_value' => 5000000],
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }

        // Create Projects
        $projects = [
            ['client_id' => 1, 'service_id' => 1, 'title' => 'Website Company Profile PT Maju Bersama', 'price' => 5000000, 'start_date' => now()->subDays(30), 'deadline' => now()->addDays(15), 'status' => 'on_progress', 'progress_percentage' => 60, 'description' => 'Pembuatan website company profile modern'],
            ['client_id' => 3, 'service_id' => 9, 'title' => 'Undangan Digital Pernikahan Ahmad & Rina', 'price' => 350000, 'start_date' => now()->subDays(5), 'deadline' => now()->addDays(10), 'status' => 'dp_paid', 'progress_percentage' => 20, 'description' => 'Undangan digital pernikahan standar'],
            ['client_id' => 4, 'service_id' => 2, 'title' => 'Website E-Commerce CV Teknologi Utama', 'price' => 15000000, 'start_date' => now()->subDays(60), 'deadline' => now()->subDays(5), 'status' => 'revision', 'progress_percentage' => 85, 'description' => 'E-commerce toko elektronik'],
            ['client_id' => 5, 'service_id' => 1, 'title' => 'Website Fitri Catering', 'price' => 5000000, 'start_date' => now()->subDays(90), 'deadline' => now()->subDays(45), 'status' => 'completed', 'progress_percentage' => 100, 'description' => 'Website katalog dan pemesanan catering'],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);

            // Add milestones for each project
            if ($project->id === 1) {
                ProjectMilestone::create(['project_id' => $project->id, 'title' => 'Wireframe & Desain', 'due_date' => now()->subDays(20), 'status' => 'done']);
                ProjectMilestone::create(['project_id' => $project->id, 'title' => 'Development Frontend', 'due_date' => now()->subDays(10), 'status' => 'done']);
                ProjectMilestone::create(['project_id' => $project->id, 'title' => 'Development Backend', 'due_date' => now()->addDays(5), 'status' => 'pending']);
                ProjectMilestone::create(['project_id' => $project->id, 'title' => 'Testing & Launch', 'due_date' => now()->addDays(15), 'status' => 'pending']);
            }
        }

        // Create Subscriptions
        $subscriptions = [
            ['client_id' => 2, 'service_id' => 6, 'start_date' => now()->subMonths(3), 'billing_cycle' => 'monthly', 'price' => 2500000, 'status' => 'active'],
            ['client_id' => 4, 'service_id' => 7, 'start_date' => now()->subMonths(6), 'billing_cycle' => 'monthly', 'price' => 5000000, 'status' => 'active'],
            ['client_id' => 5, 'service_id' => 6, 'start_date' => now()->subMonths(2), 'end_date' => now()->subDays(15), 'billing_cycle' => 'monthly', 'price' => 2500000, 'status' => 'paused'],
        ];

        foreach ($subscriptions as $subData) {
            Subscription::create($subData);
        }

        // Create Invoices
        $invoices = [
            ['client_id' => 1, 'project_id' => 1, 'invoice_number' => 'INV-' . now()->format('Ym') . '-0001', 'issue_date' => now()->subDays(30), 'due_date' => now()->subDays(16), 'total_amount' => 2500000, 'paid_amount' => 2500000, 'status' => 'paid', 'notes' => 'DP 50% Website'],
            ['client_id' => 1, 'project_id' => 1, 'invoice_number' => 'INV-' . now()->format('Ym') . '-0002', 'issue_date' => now(), 'due_date' => now()->addDays(14), 'total_amount' => 2500000, 'paid_amount' => 0, 'status' => 'sent', 'notes' => 'Pelunasan Website'],
            ['client_id' => 2, 'subscription_id' => 1, 'invoice_number' => 'INV-' . now()->format('Ym') . '-0003', 'issue_date' => now()->subDays(5), 'due_date' => now()->addDays(25), 'total_amount' => 2500000, 'paid_amount' => 2500000, 'status' => 'paid', 'notes' => 'Sosmed Management Bulan Ini'],
            ['client_id' => 3, 'project_id' => 2, 'invoice_number' => 'INV-' . now()->format('Ym') . '-0004', 'issue_date' => now()->subDays(5), 'due_date' => now()->addDays(5), 'total_amount' => 350000, 'paid_amount' => 175000, 'status' => 'partial', 'notes' => 'Undangan Digital'],
            ['client_id' => 4, 'project_id' => 3, 'invoice_number' => 'INV-' . now()->format('Ym') . '-0005', 'issue_date' => now()->subDays(60), 'due_date' => now()->subDays(30), 'total_amount' => 7500000, 'paid_amount' => 7500000, 'status' => 'paid', 'notes' => 'DP E-Commerce'],
            ['client_id' => 4, 'subscription_id' => 2, 'invoice_number' => 'INV-' . now()->format('Ym') . '-0006', 'issue_date' => now()->subDays(10), 'due_date' => now()->subDays(2), 'total_amount' => 5000000, 'paid_amount' => 0, 'status' => 'overdue', 'notes' => 'Sosmed Management Pro'],
        ];

        foreach ($invoices as $invData) {
            Invoice::create($invData);
        }

        // Create Payments
        $payments = [
            ['invoice_id' => 1, 'amount' => 2500000, 'method' => 'transfer', 'payment_date' => now()->subDays(28), 'notes' => 'DP via BCA'],
            ['invoice_id' => 3, 'amount' => 2500000, 'method' => 'transfer', 'payment_date' => now()->subDays(3), 'notes' => 'Transfer BCA'],
            ['invoice_id' => 4, 'amount' => 175000, 'method' => 'e-wallet', 'payment_date' => now()->subDays(4), 'notes' => 'DP via GoPay'],
            ['invoice_id' => 5, 'amount' => 7500000, 'method' => 'transfer', 'payment_date' => now()->subDays(55), 'notes' => 'Transfer DP 50%'],
        ];

        foreach ($payments as $payData) {
            Payment::create($payData);
        }
    }
}
