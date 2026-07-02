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

        return redirect()->route('applications.create')->with('success', 'Aplikasi berhasil ditambahkan.');
    }
}
