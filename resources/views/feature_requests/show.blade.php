@extends('layouts.app')
@section('content')
  <a href="{{ route('feature-requests.index') }}" class="btn btn-outline-secondary btn-sm mb-3">&larr; Kembali ke daftar</a>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <h1 class="h3 mb-1">{{ $requestItem->title }}</h1>
          <p class="text-muted mb-0">Tipe: {{ $requestItem->type }} • Status: {{ $requestItem->status }} • Prioritas: {{ $requestItem->priority }}</p>
        </div>
        <span class="badge bg-primary">#{{ $requestItem->id }}</span>
      </div>
      <div class="border rounded p-3 bg-light">{{ $requestItem->description }}</div>
      <div class="mt-4">
        <a href="{{ route('feature-requests.edit', $requestItem) }}" class="btn btn-secondary">Edit</a>
      </div>
    </div>
  </div>
@endsection
