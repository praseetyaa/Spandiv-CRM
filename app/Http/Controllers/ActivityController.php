<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('user');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        $activities = $query->latest()->paginate(30);
        $modules = Activity::select('module')->distinct()->pluck('module');

        return view('activities.index', compact('activities', 'modules'));
    }
}
