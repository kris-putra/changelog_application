<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\FeatureRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalRequests = FeatureRequest::count();
        $openCount = FeatureRequest::where('status', 'Open')->count();
        $inProgressCount = FeatureRequest::where('status', 'In Progress')->count();
        $completedCount = FeatureRequest::where('status', 'Completed')->count();
        $totalApplications = Application::count();
        $applications = Application::latest()->get();

        $query = FeatureRequest::with('application');


        $sort = $request->query('sort');
        $order = $request->query('order', 'asc');
        $cycle = (int) $request->query('cycle', 0);

        if ($sort && in_array($order, ['asc', 'desc'])) {
            switch ($sort) {
                case 'request_number':
                    $query->orderByRaw('RIGHT(request_number, 4) ' . $order);
                    break;

                case 'application':
                    $query->leftJoin('applications', 'feature_requests.application_id', '=', 'applications.id')
                          ->orderBy('applications.name', $order)
                          ->select('feature_requests.*');
                    break;

                case 'title':
                    $query->orderBy('title', $order);
                    break;

                case 'priority':
                    $cycles = [
                        ['low', 'medium', 'urgent'],
                        ['medium', 'urgent', 'low'],
                        ['urgent', 'low', 'medium'],
                    ];
                    $cycleKey = $cycle % 3;
                    $priorities = $cycles[$cycleKey];
                    $query->orderByRaw(DB::raw("FIELD(priority, '" . implode("','", $priorities) . "')"));
                    break;

                case 'type':
                    $cycles = [
                        ['feature', 'change', 'bug', 'incident'],
                        ['change', 'bug', 'incident', 'feature'],
                        ['bug', 'incident', 'feature', 'change'],
                        ['incident', 'feature', 'change', 'bug'],
                    ];
                    $cycleKey = $cycle % 4;
                    $types = $cycles[$cycleKey];
                    $query->orderByRaw(DB::raw("FIELD(type, '" . implode("','", $types) . "')"));
                    break;

                case 'status':
                    $cycles = [
                        ['Open', 'In Progress', 'Completed'],
                        ['In Progress', 'Completed', 'Open'],
                        ['Completed', 'Open', 'In Progress'],
                    ];
                    $cycleKey = $cycle % 3;
                    $statuses = $cycles[$cycleKey];
                    $query->orderByRaw(DB::raw("FIELD(status, '" . implode("','", $statuses) . "')"));
                    break;

                case 'created_at':
                    $query->orderBy('created_at', $order);
                    break;
            }
        } else {
            $query->latest();
        }

        // Cycle labels for display in blade
        $cycleLabels = [];
        $priorityCycles = [
            ['Low', 'Medium', 'Urgent'],
            ['Medium', 'Urgent', 'Low'],
            ['Urgent', 'Low', 'Medium'],
        ];
        $typeCycles = [
            ['Feature', 'Change', 'Bug', 'Incident'],
            ['Change', 'Bug', 'Incident', 'Feature'],
            ['Bug', 'Incident', 'Feature', 'Change'],
            ['Incident', 'Feature', 'Change', 'Bug'],
        ];
        $statusCycles = [
            ['Open', 'In Progress', 'Completed'],
            ['In Progress', 'Completed', 'Open'],
            ['Completed', 'Open', 'In Progress'],
        ];
        if ($sort === 'priority') {
            $cycleLabels['priority'] = $priorityCycles[$cycle % 3];
        } elseif ($sort === 'type') {
            $cycleLabels['type'] = $typeCycles[$cycle % 4];
        } elseif ($sort === 'status') {
            $cycleLabels['status'] = $statusCycles[$cycle % 3];
        }

        $requests = $query->get();

        return view('dashboard', compact(
            'totalRequests', 'openCount', 'inProgressCount', 'completedCount', 'requests',
            'sort', 'order', 'cycle', 'cycleLabels', 'totalApplications', 'applications'
        ));

    }
}
