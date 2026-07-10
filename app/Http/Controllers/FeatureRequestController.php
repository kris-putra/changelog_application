<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\FeatureRequest;
use App\Http\Requests\StoreFeatureRequest;
use Illuminate\Http\Request;

class FeatureRequestController extends Controller
{
    public function index()
    {
        $requests = FeatureRequest::with('application')->latest()->paginate(15);
        return view('feature_requests.index', compact('requests'));
    }

    public function create()
    {
        $applications = Application::orderBy('name')->get();

        return view('feature_requests.create', compact('applications'));
    }

    public function store(StoreFeatureRequest $request)
    {
        $data = $request->validated();
        $data['requested_by'] = auth()->id() ?? 1;
        $fr = FeatureRequest::create($data);

        return redirect()->route('feature-requests.create')->with('toast', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => 'Feature Request berhasil disimpan.',
            'extra'   => 'Request Number: ' . $fr->request_number,
        ]);
    }

    public function show(FeatureRequest $featureRequest)
    {
        $featureRequest->load('application');

        return view('feature_requests.show', ['requestItem' => $featureRequest]);
    }

    public function edit(FeatureRequest $featureRequest)
    {
        $applications = Application::orderBy('name')->get();

        return view('feature_requests.edit', compact('featureRequest', 'applications'));
    }

    public function update(StoreFeatureRequest $request, FeatureRequest $featureRequest)
    {
        $data = $request->validated();

        $priorityChanged = $data['priority'] !== $featureRequest->priority;
        $typeChanged = $data['type'] !== $featureRequest->type;

        $featureRequest->update($data);

        if ($priorityChanged || $typeChanged) {
            $featureRequest->regenerateRequestNumber();
        }

        return redirect()->route('feature-requests.show', $featureRequest)->with('toast', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => 'Feature Request berhasil diperbarui.',
        ]);
    }

    public function destroy(FeatureRequest $featureRequest)
    {
        $featureRequest->delete();
        return redirect()->route('dashboard')->with('toast', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => 'Permintaan dihapus.',
        ]);
    }

    public function start(FeatureRequest $featureRequest)
    {
        $featureRequest->update([
            'status' => 'In Progress',
            'started_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('toast', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => 'Status diubah ke In Progress.',
        ]);
    }

    public function cancel(FeatureRequest $featureRequest)
    {
        $featureRequest->update([
            'status' => 'Open',
            'started_at' => null,
        ]);

        return redirect()->route('dashboard')->with('toast', [
            'type'    => 'warning',
            'title'   => 'Warning',
            'message' => 'Status dikembalikan ke Open.',
        ]);
    }

    public function complete(FeatureRequest $featureRequest)
    {
        $featureRequest->update([
            'status' => 'Completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('toast', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => 'Permintaan diselesaikan.',
        ]);
    }
}