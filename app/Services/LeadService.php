<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Client;
use App\Models\Project;
use App\Models\Subscription;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class LeadService
{
    public function createLead(array $data): Lead
    {
        $lead = Lead::create($data);
        $lead->lead_score = $lead->calculateLeadScore();
        $lead->save();

        Activity::log('created', 'leads', "Lead baru: {$lead->name}", $lead);

        return $lead;
    }

    public function updateLead(Lead $lead, array $data): Lead
    {
        $lead->update($data);
        $lead->lead_score = $lead->calculateLeadScore();
        $lead->save();

        Activity::log('updated', 'leads', "Lead diupdate: {$lead->name}", $lead);

        return $lead;
    }

    public function convertLead(Lead $lead, array $options): array
    {
        return DB::transaction(function () use ($lead, $options) {
            $result = ['client' => null, 'project' => null, 'subscription' => null];

            // 1. Create Client (always)
            $client = Client::create([
                'name' => $options['client_name'] ?? $lead->name,
                'phone' => $options['client_phone'] ?? $lead->phone,
                'email' => $options['client_email'] ?? $lead->email ?? '',
                'business_name' => $options['business_name'] ?? $lead->name,
                'industry' => $options['industry'] ?? 'Belum ditentukan',
                'instagram' => $options['instagram'] ?? null,
                'website' => $options['website'] ?? null,
                'client_status' => 'active',
                'lead_id' => $lead->id,
            ]);
            $result['client'] = $client;

            Activity::log('converted', 'leads', "Lead {$lead->name} → Client", $client);

            // 2. Create Project (optional)
            if (!empty($options['create_project'])) {
                $project = Project::create([
                    'client_id' => $client->id,
                    'service_id' => $lead->service_id,
                    'title' => $options['project_title'] ?? "Project - {$lead->name}",
                    'price' => $lead->estimated_value ?? $lead->service->base_price ?? 0,
                    'start_date' => $options['project_start_date'] ?? now()->format('Y-m-d'),
                    'deadline' => $options['project_deadline'] ?? now()->addDays(30)->format('Y-m-d'),
                    'status' => 'brief',
                    'progress_percentage' => 0,
                    'description' => $options['project_description'] ?? "Project dari lead: {$lead->name}",
                ]);
                $result['project'] = $project;

                Activity::log('created', 'projects', "Project baru dari konversi lead: {$project->title}", $project);
            }

            // 3. Create Subscription (optional)
            if (!empty($options['create_subscription'])) {
                $subscription = Subscription::create([
                    'client_id' => $client->id,
                    'service_id' => $lead->service_id,
                    'start_date' => $options['subscription_start_date'] ?? now()->format('Y-m-d'),
                    'end_date' => $options['subscription_end_date'] ?? now()->addYear()->format('Y-m-d'),
                    'billing_cycle' => $options['billing_cycle'] ?? 'monthly',
                    'price' => $lead->estimated_value ?? $lead->service->base_price ?? 0,
                    'status' => 'active',
                ]);
                $result['subscription'] = $subscription;

                Activity::log('created', 'subscriptions', "Subscription baru dari konversi lead: {$client->name}", $subscription);
            }

            // Update lead status to won if not already
            if ($lead->status !== 'closed_won') {
                $lead->update(['status' => 'closed_won']);
                $lead->lead_score = $lead->calculateLeadScore();
                $lead->save();
            }

            return $result;
        });
    }

    public function updateFollowUp(Lead $lead, string $notes = null): Lead
    {
        $lead->update([
            'last_follow_up' => now(),
            'notes' => $notes ?? $lead->notes,
        ]);
        $lead->lead_score = $lead->calculateLeadScore();
        $lead->save();

        Activity::log('follow_up', 'leads', "Follow up: {$lead->name}", $lead);

        return $lead;
    }

    public function getPipelineStats(): array
    {
        $statuses = ['new', 'contacted', 'proposal_sent', 'negotiation', 'closed_won', 'closed_lost'];
        $stats = [];

        foreach ($statuses as $status) {
            $stats[$status] = Lead::where('status', $status)->count();
        }

        return $stats;
    }
}
