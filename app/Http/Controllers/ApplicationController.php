<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;

class ApplicationController extends Controller
{
    public function create()
    {
        return view('applications.create');
    }

    public function store(StoreApplicationRequest $request)
    {
        Application::create($request->validated());

        return redirect()->route('applications.create')->with('toast', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => 'Aplikasi berhasil ditambahkan.',
        ]);
    }

    public function edit(Application $application)
    {
        return view('applications.edit', compact('application'));
    }

    public function update(StoreApplicationRequest $request, Application $application)
    {
        $application->update($request->validated());

        return redirect()->route('dashboard')->with('toast', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => 'Aplikasi berhasil diperbarui.',
        ]);
    }

    public function destroy(Application $application)
    {
        $application->delete();

        return redirect()->route('dashboard')->with('toast', [
            'type'    => 'success',
            'title'   => 'Success',
            'message' => 'Aplikasi berhasil dihapus.',
        ]);
    }
}

