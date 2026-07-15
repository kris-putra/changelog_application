<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\FeatureRequest;
use App\Models\TechnicalComponent;
use App\Http\Requests\StoreFeatureRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $attachment = $data['attachment'] ?? null;
        unset($data['attachment']);

        $data['requested_by'] = auth()->id() ?? 1;
        $fr = FeatureRequest::create($data);

        if ($attachment) {
            $extension = $attachment->getClientOriginalExtension();
            $filename = $fr->request_number . '.' . $extension;
            $attachment->storeAs('attachments', $filename, 'local');
            $fr->update(['attachment_filename' => $filename]);
        }

        return redirect()->route('feature-requests.create')->with('toast', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => 'Feature Request berhasil disimpan.',
            'extra'   => 'Request Number: ' . $fr->request_number,
        ]);
    }

    public function show(FeatureRequest $featureRequest)
    {
        $featureRequest->load(['application', 'technicalComponents', 'affectedApplications']);

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
        $attachment = $data['attachment'] ?? null;
        unset($data['attachment']);

        $priorityChanged = $data['priority'] !== $featureRequest->priority;
        $typeChanged = $data['type'] !== $featureRequest->type;

        $featureRequest->update($data);

        if ($priorityChanged || $typeChanged) {
            $featureRequest->regenerateRequestNumber();
        }

        if ($attachment) {
            // Delete old file if exists
            if ($featureRequest->attachment_filename) {
                Storage::disk('local')->delete('attachments/' . $featureRequest->attachment_filename);
            }

            $extension = $attachment->getClientOriginalExtension();
            $filename = $featureRequest->request_number . '.' . $extension;
            $attachment->storeAs('attachments', $filename, 'local');
            $featureRequest->update(['attachment_filename' => $filename]);
        }

        return redirect()->route('feature-requests.show', $featureRequest)->with('toast', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => 'Feature Request berhasil diperbarui.',
        ]);
    }

    public function destroy(FeatureRequest $featureRequest)
    {
        // Cascade delete pivot relationships
        $featureRequest->technicalComponents()->detach();
        $featureRequest->affectedApplications()->detach();
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

    public function saveExecution(Request $request, FeatureRequest $featureRequest)
    {
        $validated = $request->validate([
            'pic' => 'required|string|max:255',
            'rollback_plan' => 'required|string',
            'estimated_finish_at' => 'required|date',
        ]);

        $validated['started_at'] = now();
        $validated['status'] = 'In Progress';

        $featureRequest->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pelaksanaan berhasil disimpan. Status diubah ke In Progress.',
            ]);
        }

        return redirect()->route('dashboard')->with('toast', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => 'Data pelaksanaan berhasil disimpan. Status diubah ke In Progress.',
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

    public function openAttachment(FeatureRequest $featureRequest)
    {
        if (!$featureRequest->attachment_filename) {
            abort(404);
        }

        $path = 'attachments/' . $featureRequest->attachment_filename;

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->response($path);
    }

    public function searchApplications(Request $request)
    {
        $query = $request->input('q', '');

        // Empty query = return all apps (used for existence check)
        if (strlen($query) === 0) {
            return response()->json(Application::orderBy('name')->limit(100)->get(['id', 'name']));
        }

        // Require at least 2 chars for search
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = Application::where('name', 'like', '%' . $query . '%')
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name']);

        return response()->json($results);
    }

    public function complete(Request $request, FeatureRequest $featureRequest)
    {
        $validated = $request->validate([
            'lesson_learned' => 'required|string',
            'technical_component_ids' => 'required|array|min:1',
            'technical_component_ids.*' => 'exists:technical_components,id',
            'affected_application_ids' => 'required|array|min:1',
            'affected_application_ids.*' => 'exists:applications,id',
        ]);

        $validated['completed_at'] = now();
        $validated['status'] = 'Completed';

        $featureRequest->update([
            'status' => $validated['status'],
            'completed_at' => $validated['completed_at'],
            'lesson_learned' => $validated['lesson_learned'],
        ]);

        // Sync technical components (many-to-many)
        $featureRequest->technicalComponents()->sync($validated['technical_component_ids']);

        // Sync affected applications (many-to-many)
        $featureRequest->affectedApplications()->sync($validated['affected_application_ids']);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Perubahan berhasil diselesaikan.',
            ]);
        }

        return redirect()->route('dashboard')->with('toast', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => 'Perubahan berhasil diselesaikan.',
        ]);
    }

    public function getTechnicalComponents()
    {
        $components = TechnicalComponent::orderBy('display_order')->get(['id', 'name']);
        return response()->json($components);
    }

    public function getCompletedData(FeatureRequest $featureRequest)
    {
        $featureRequest->load(['technicalComponents', 'affectedApplications']);

        return response()->json([
            'lesson_learned' => $featureRequest->lesson_learned,
            'technical_component_ids' => $featureRequest->technicalComponents->pluck('id'),
            'affected_application_ids' => $featureRequest->affectedApplications->pluck('id'),
            'affected_application_names' => $featureRequest->affectedApplications->pluck('name', 'id'),
        ]);
    }

    public function updateCompleted(Request $request, FeatureRequest $featureRequest)
    {
        $validated = $request->validate([
            'lesson_learned' => 'required|string',
            'technical_component_ids' => 'required|array|min:1',
            'technical_component_ids.*' => 'exists:technical_components,id',
            'affected_application_ids' => 'required|array|min:1',
            'affected_application_ids.*' => 'exists:applications,id',
        ]);

        $featureRequest->update([
            'lesson_learned' => $validated['lesson_learned'],
        ]);

        // Sync technical components - sync() handles updates without duplicates
        $featureRequest->technicalComponents()->sync($validated['technical_component_ids']);

        // Sync affected applications - sync() handles updates without duplicates
        $featureRequest->affectedApplications()->sync($validated['affected_application_ids']);

        return response()->json([
            'success' => true,
            'message' => 'Data penyelesaian berhasil diperbarui!',
        ]);
    }
}