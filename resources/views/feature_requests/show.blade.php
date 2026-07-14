@extends('layouts.app')
@section('content')
  <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm mb-3">&larr; Kembali ke Dashboard</a>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <h1 class="h3 mb-1">{{ $requestItem->title }}</h1>
          <p class="text-muted mb-0">Aplikasi: {{ $requestItem->application?->name ?? '-' }} • Tipe: {{ $requestItem->type }} • Status: {{ $requestItem->status }} • Prioritas: {{ $requestItem->priority }}</p>
        </div>
          <span class="badge bg-primary">{{ $requestItem->request_number }}</span>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <small class="text-muted d-block">Request Number</small>
          <strong><code>{{ $requestItem->request_number }}</code></strong>
        </div>
        <div class="col-md-6">
          <small class="text-muted d-block">Aplikasi</small>
          <strong>{{ $requestItem->application?->name ?? '-' }}</strong>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-6">
          <small class="text-muted d-block">Pemohon Perubahan</small>
          <strong>{{ $requestItem->pemohon_perubahan ?? '-' }}</strong>
        </div>
        <div class="col-md-6">
          <small class="text-muted d-block">Tanggal Permintaan</small>
          <strong>{{ $requestItem->created_at->format('d M Y H:i') }}</strong>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-4">
          <small class="text-muted d-block">Prioritas</small>
          <span class="badge bg-secondary">{{ ucfirst($requestItem->priority) }}</span>
        </div>
        <div class="col-md-4">
          <small class="text-muted d-block">Tipe</small>
          <span class="badge bg-info text-dark">{{ ucfirst($requestItem->type) }}</span>
        </div>
        <div class="col-md-4">
          <small class="text-muted d-block">Status</small>
          @if($requestItem->status === 'Open')
            <span class="badge bg-primary">Open</span>
          @elseif($requestItem->status === 'In Progress')
            <span class="badge bg-warning text-dark">In Progress</span>
          @elseif($requestItem->status === 'Completed')
            <span class="badge bg-success">Completed</span>
          @endif
        </div>
      </div>

      <div class="mb-3">
        <small class="text-muted d-block">Deskripsi</small>
        <div class="border rounded p-3 bg-light">{{ $requestItem->description }}</div>
      </div>

      @if($requestItem->as_is)
      <div class="mb-3">
        <small class="text-muted d-block">As-Is</small>
        <div class="border rounded p-3 bg-light">{{ $requestItem->as_is }}</div>
      </div>
      @endif

      @if($requestItem->to_be)
      <div class="mb-3">
        <small class="text-muted d-block">To-Be</small>
        <div class="border rounded p-3 bg-light">{{ $requestItem->to_be }}</div>
      </div>
      @endif

      @if($requestItem->impact)
      <div class="mb-3">
        <small class="text-muted d-block">Dampak Perubahan</small>
        <div class="border rounded p-3 bg-light">{{ $requestItem->impact }}</div>
      </div>
      @endif

      @if(in_array($requestItem->status, ['In Progress', 'Completed']))
      <hr class="my-4">
      <h5 class="mb-3">Data Pelaksanaan</h5>

      <div class="row mb-3">
        <div class="col-md-4">
          <small class="text-muted d-block">PIC</small>
          <strong>{{ $requestItem->pic ?? '-' }}</strong>
        </div>
        <div class="col-md-4">
          <small class="text-muted d-block">Tanggal Mulai</small>
          <strong>{{ $requestItem->started_at ? $requestItem->started_at->format('d M Y H:i') : '-' }}</strong>
        </div>
        <div class="col-md-4">
          <small class="text-muted d-block">Estimasi Selesai</small>
          <strong>{{ $requestItem->estimated_finish_at ? $requestItem->estimated_finish_at->format('d M Y H:i') : '-' }}</strong>
        </div>
      </div>

      <div class="mb-3">
        <small class="text-muted d-block">Rollback Plan</small>
        <div class="border rounded p-3 bg-light" style="white-space: pre-wrap;">{{ $requestItem->rollback_plan ?? '-' }}</div>
      </div>
      @endif

      @if($requestItem->status === 'Completed')
      <hr class="my-4">
      <h5 class="mb-3">Data Penyelesaian</h5>

      <div class="row mb-3">
        <div class="col-md-6">
          <small class="text-muted d-block">Tanggal Selesai</small>
          <strong>{{ $requestItem->completed_at ? $requestItem->completed_at->format('d M Y H:i') : '-' }}</strong>
        </div>
      </div>

      <div class="mb-3">
        <small class="text-muted d-block">Lesson Learned</small>
        <div class="border rounded p-3 bg-light" style="white-space: pre-wrap;">{{ $requestItem->lesson_learned ?? '-' }}</div>
      </div>
      @endif
    </div>

  </div>
@endsection
