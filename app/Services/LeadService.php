<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Client;
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
        $oldStatus = $lead->status;
        $lead->update($data);
        $lead->lead_score = $lead->calculateLeadScore();
        $lead->save();

        Activity::log('updated', 'leads', "Lead diupdate: {$lead->name}", $lead);

        // Auto convert to client if closed_won
        if ($oldStatus !== 'closed_won' && $lead->status === 'closed_won') {
            $this->convertToClient($lead);
        }

        return $lead;
    }

    public function convertToClient(Lead $lead): Client
    {
        return DB::transaction(function () use ($lead) {
            $client = Client::create([
                'name' => $lead->name,
                'phone' => $lead->phone,
                'email' => $lead->email ?? '',
                'business_name' => $lead->name,
                'industry' => 'Belum ditentukan',
                'client_status' => 'active',
                'lead_id' => $lead->id,
            ]);

            Activity::log('converted', 'leads', "Lead {$lead->name} → Client", $client);

            return $client;
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
