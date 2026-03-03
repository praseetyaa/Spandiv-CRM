<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\{Lead, Client, Service, Project, Subscription, Invoice, Payment, Activity};

class CompanyScope
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && !$user->isSuperAdmin() && $user->company_id) {
            $companyId = $user->company_id;

            // Add global scopes to all data models
            $models = [Lead::class, Client::class, Service::class, Project::class, Subscription::class, Invoice::class, Payment::class, Activity::class];

            foreach ($models as $model) {
                $model::addGlobalScope('company', function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                });
            }
        }

        return $next($request);
    }
}
