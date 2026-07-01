@extends('layouts.app')
@section('content')
  <a href="{{ route('feature-requests.index') }}">&larr; Kembali</a>
  <h1 class="mt-2">{{ $requestItem->title }}</h1>
  <p class="text-muted">Status: {{ $requestItem->status }} • Prioritas: {{ $requestItem->priority }}</p>
  <div class="mt-3">{{ $requestItem->description }}</div>
  <div class="mt-3">
    <a href="{{ route('feature-requests.edit', $requestItem) }}" class="btn btn-secondary">Edit</a>
  </div>
@endsection
