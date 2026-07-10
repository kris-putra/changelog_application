@extends('layouts.app')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3 mb-1">Daftar Permintaan Perubahan</h1>
      <p class="text-muted mb-0">Catatan internal untuk permintaan fitur, perubahan, dan perbaikan aplikasi.</p>
    </div>
    <a href="{{ route('feature-requests.create') }}" class="btn btn-primary">Ajukan Permintaan</a>
  </div>

  @forelse($requests as $r)
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start gap-3">
          <div>
            <h5 class="mb-2"><a href="{{ route('feature-requests.show', $r) }}" class="text-decoration-none">{{ $r->title }}</a></h5>
            <p class="mb-2 text-muted">{{ Illuminate\Support\Str::limit($r->description, 160) }}</p>
            <small class="text-muted">Aplikasi: {{ $r->application?->name ?? '-' }} • Tipe: {{ strtoupper($r->type) }} • Status: {{ $r->status }} • Prioritas: {{ $r->priority }}</small>
          </div>
          <span class="badge bg-light text-dark">{{ $r->request_number }}</span>
        </div>
      </div>
    </div>
  @empty
    <div class="alert alert-info">Belum ada permintaan yang tercatat.</div>
  @endforelse

  <div class="mt-4">
    {{ $requests->links() }}
  </div>
@endsection
