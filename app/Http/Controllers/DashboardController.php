<?php

namespace App\Http\Controllers;

use App\Models\FeatureRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRequests = FeatureRequest::count();
        $openCount = FeatureRequest::where('status', 'Open')->count();
        $inProgressCount = FeatureRequest::where('status', 'In Progress')->count();
        $completedCount = FeatureRequest::where('status', 'Completed')->count();

        $requests = FeatureRequest::with('application')->latest()->get();

        return view('dashboard', compact(
            'totalRequests', 'openCount', 'inProgressCount', 'completedCount', 'requests'
        ));
    }
}