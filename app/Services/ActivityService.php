<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityService
{
    public function getAll(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Activity::with('user')->latest();

        if (!empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (!empty($filters['search'])) {
            $query->where('description', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate($perPage);
    }

    public function getModules(): array
    {
        return Activity::select('module')
            ->distinct()
            ->pluck('module')
            ->toArray();
    }

    public function getActions(): array
    {
        return Activity::select('action')
            ->distinct()
            ->pluck('action')
            ->toArray();
    }
}
