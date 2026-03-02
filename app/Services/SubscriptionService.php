<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Activity;

class SubscriptionService
{
    public function create(array $data): Subscription
    {
        $subscription = Subscription::create($data);

        Activity::log('created', 'subscriptions', "Langganan baru untuk client #{$subscription->client_id}: {$subscription->service->name}", $subscription);

        return $subscription;
    }

    public function update(Subscription $subscription, array $data): Subscription
    {
        $subscription->update($data);

        Activity::log('updated', 'subscriptions', "Langganan #{$subscription->id} diupdate", $subscription);

        return $subscription;
    }

    public function togglePause(Subscription $subscription): Subscription
    {
        if ($subscription->status === 'active') {
            $subscription->update(['status' => 'paused']);
            Activity::log('paused', 'subscriptions', "Langganan #{$subscription->id} dijeda", $subscription);
        } elseif ($subscription->status === 'paused') {
            $subscription->update(['status' => 'active']);
            Activity::log('resumed', 'subscriptions', "Langganan #{$subscription->id} dilanjutkan", $subscription);
        }

        return $subscription;
    }

    public function cancel(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'cancelled',
            'end_date' => now()->toDateString(),
        ]);

        Activity::log('cancelled', 'subscriptions', "Langganan #{$subscription->id} dibatalkan", $subscription);

        return $subscription;
    }
}
