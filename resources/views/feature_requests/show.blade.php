@extends('layouts.app')
@section('content')
  <a href="{{ route('feature-requests.index') }}" class="btn btn-outline-secondary btn-sm mb-3">&larr; Kembali ke daftar</a>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <h1 class="h3 mb-1">{{ $requestItem->title }}</h1>
          <p class="text-muted mb-0">Aplikasi: {{ $requestItem->application?->name ?? '-' }} • Tipe: {{ $requestItem->type }} • Status: {{ $requestItem->status }} • Prioritas: {{ $requestItem->priority }}</p>
        </div>
        <span class="badge bg-primary">#{{ $requestItem->id }}</span>
      </div>
      <div class="border rounded p-3 bg-light">{{ $requestItem->description }}</div>

      @if($requestItem->detail_perubahan)
      <div class="mt-3">
        <h6>Detail Perubahan</h6>
        <div class="border rounded p-3 bg-light">{{ $requestItem->detail_perubahan }}</div>
      </div>
      @endif

      @if($requestItem->pemohon_perubahan)
      <div class="mt-3">
        <h6>Pemohon Perubahan</h6>
        <div class="border rounded p-3 bg-light">{{ $requestItem->pemohon_perubahan }}</div>
      </div>
      @endif

      @if($requestItem->as_is)
      <div class="mt-3">
        <h6>As-Is</h6>
        <div class="border rounded p-3 bg-light">{{ $requestItem->as_is }}</div>
      </div>
      @endif

      @if($requestItem->to_be)
      <div class="mt-3">
        <h6>To-Be</h6>
        <div class="border rounded p-3 bg-light">{{ $requestItem->to_be }}</div>
      </div>
      @endif

      <div class="mt-3">
        <h6>Klasifikasi Perubahan</h6>
        <span class="badge {{ $requestItem->klasifikasi_perubahan === 'Emergency' ? 'bg-danger' : 'bg-success' }}">{{ $requestItem->klasifikasi_perubahan ?? 'Normal' }}</span>
      </div>

      <div class="mt-4">
        <a href="{{ route('feature-requests.edit', $requestItem) }}" class="btn btn-secondary">Edit</a>
      </div>
    </div>
  </div>
@endsection
