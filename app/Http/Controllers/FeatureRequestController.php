<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\FeatureRequest;
use App\Http\Requests\StoreFeatureRequest;

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
        return redirect()->route('feature-requests.show', $fr)->with('success', 'Permintaan disimpan');
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
        $featureRequest->update($request->validated());
        return redirect()->route('feature-requests.show', $featureRequest)->with('success','Diperbarui');
    }

    public function destroy(FeatureRequest $featureRequest)
    {
        $featureRequest->delete();
        return redirect()->route('feature-requests.index')->with('success','Dihapus');
    }
}
