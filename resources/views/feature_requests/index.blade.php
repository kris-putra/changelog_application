@extends('layouts.app')

@section('content')
  <div class="d-flex justify-content-between mb-3">
    <h1>Daftar Permintaan</h1>
    <a href="{{ route('feature-requests.create') }}" class="btn btn-primary">Ajukan Permintaan</a>
  </div>

  @foreach($requests as $r)
    <div class="card mb-2">
      <div class="card-body">
        <h5><a href="{{ route('feature-requests.show', $r) }}">{{ $r->title }}</a></h5>
        <p class="mb-1">{{ 
            Illuminate\Support\Str::limit($r->description, 150) 
        }}</p>
        <small class="text-muted">Status: {{ $r->status }} • Prioritas: {{ $r->priority }}</small>
      </div>
    </div>
  @endforeach

  {{ $requests->links() }}
@endsection
