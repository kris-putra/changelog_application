@extends('layouts.app')
@section('content')
  <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm mb-3">&larr; Kembali ke Dashboard</a>

  {{-- Title Bar --}}
  <div class="d-flex justify-content-between align-items-start mb-4">
    <div>
      <h1 class="h3 mb-1">{{ $requestItem->title }}</h1>
    </div>
    <span class="badge bg-primary fs-6">{{ $requestItem->request_number }}</span>
  </div>

  {{-- ======================================== --}}
  {{-- Section 1: Data Permintaan              --}}
  {{-- ======================================== --}}
  <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-left: 4px solid #0d6efd !important;">
    <div class="card-header bg-light border-0 rounded-top-4 py-3">
      <h5 class="mb-0 fw-bold text-primary">
        <i class="bi bi-file-text me-2"></i>Data Permintaan
      </h5>
    </div>
    <div class="card-body px-4 py-4">
      {{-- Row 1: Request Number | Application | Request Date --}}
      <div class="row mb-3">
        <div class="col-md-4">
          <small class="text-muted d-block">Request Number</small>
          <strong><code>{{ $requestItem->request_number }}</code></strong>
        </div>
        <div class="col-md-4">
          <small class="text-muted d-block">Aplikasi</small>
          <strong>{{ $requestItem->application?->name ?? '-' }}</strong>
        </div>
        <div class="col-md-4">
          <small class="text-muted d-block">Tanggal Permintaan</small>
          <strong>{{ $requestItem->created_at->format('d M Y H:i') }}</strong>
        </div>
      </div>

      {{-- Row 2: Requester | Priority | Type | Status --}}
      <div class="row mb-3">
        <div class="col-md-3">
          <small class="text-muted d-block">Pemohon Perubahan</small>
          <strong>{{ $requestItem->pemohon_perubahan ?? '-' }}</strong>
        </div>
        <div class="col-md-3">
          <small class="text-muted d-block">Prioritas</small>
          <span class="badge bg-secondary">{{ ucfirst($requestItem->priority) }}</span>
        </div>
        <div class="col-md-3">
          <small class="text-muted d-block">Tipe</small>
          <span class="badge bg-info text-dark">{{ ucfirst($requestItem->type) }}</span>
        </div>
        <div class="col-md-3">
          <small class="text-muted d-block">Status</small>
          @if($requestItem->status === 'Open')
            <span class="badge bg-primary">Open</span>
          @elseif($requestItem->status === 'In Progress')
            <span class="badge bg-warning text-dark">In Progress</span>
          @elseif($requestItem->status === 'Completed')
            <span class="badge bg-success">Completed</span>
          @else
            <span class="badge bg-secondary">{{ $requestItem->status }}</span>
          @endif
        </div>
      </div>

      {{-- Long text: Description --}}
      <div class="mb-3">
        <small class="text-muted d-block mb-1">Deskripsi</small>
        <div class="border rounded-3 p-3 bg-light" style="white-space: pre-wrap;">{{ $requestItem->description }}</div>
      </div>

      {{-- Long text: As-Is --}}
      @if($requestItem->as_is)
      <div class="mb-3">
        <small class="text-muted d-block mb-1">As-Is</small>
        <div class="border rounded-3 p-3 bg-light" style="white-space: pre-wrap;">{{ $requestItem->as_is }}</div>
      </div>
      @endif

      {{-- Long text: To-Be --}}
      @if($requestItem->to_be)
      <div class="mb-3">
        <small class="text-muted d-block mb-1">To-Be</small>
        <div class="border rounded-3 p-3 bg-light" style="white-space: pre-wrap;">{{ $requestItem->to_be }}</div>
      </div>
      @endif

      {{-- Long text: Dampak Perubahan --}}
      @if($requestItem->impact)
      <div class="mb-0">
        <small class="text-muted d-block mb-1">Dampak Perubahan</small>
        <div class="border rounded-3 p-3 bg-light" style="white-space: pre-wrap;">{{ $requestItem->impact }}</div>
      </div>
      @endif
    </div>
  </div>

  {{-- ======================================== --}}
  {{-- Section 2: Data Pelaksanaan             --}}
  {{-- ======================================== --}}
  @if(in_array($requestItem->status, ['In Progress', 'Completed']))
  <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-left: 4px solid #198754 !important;">
    <div class="card-header bg-light border-0 rounded-top-4 py-3">
      <h5 class="mb-0 fw-bold text-success">
        <i class="bi bi-person-workspace me-2"></i>Data Pelaksanaan
      </h5>
    </div>
    <div class="card-body px-4 py-4">
      {{-- Row: PIC | Tanggal Mulai | Estimasi Selesai --}}
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

      {{-- Long text: Rollback Plan --}}
      <div class="mb-0">
        <small class="text-muted d-block mb-1">Rollback Plan</small>
        <div class="border rounded-3 p-3 bg-light" style="white-space: pre-wrap;">{{ $requestItem->rollback_plan ?? '-' }}</div>
      </div>
    </div>
  </div>
  @endif

  {{-- ======================================== --}}
  {{-- Section 3: Data Penyelesaian            --}}
  {{-- ======================================== --}}
  @if($requestItem->status === 'Completed')
  <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-left: 4px solid #fd7e14 !important;">
    <div class="card-header bg-light border-0 rounded-top-4 py-3">
      <h5 class="mb-0 fw-bold" style="color: #e65100;">
        <i class="bi bi-check-circle me-2"></i>Data Penyelesaian
      </h5>
    </div>
    <div class="card-body px-4 py-4">
      {{-- Row: Tanggal Selesai --}}
      <div class="row mb-3">
        <div class="col-md-6">
          <small class="text-muted d-block">Tanggal Selesai</small>
          <strong>{{ $requestItem->completed_at ? $requestItem->completed_at->format('d M Y H:i') : '-' }}</strong>
        </div>
      </div>

      {{-- Long text: Lesson Learned --}}
      <div class="mb-0">
        <small class="text-muted d-block mb-1">Lesson Learned</small>
        <div class="border rounded-3 p-3 bg-light" style="white-space: pre-wrap;">{{ $requestItem->lesson_learned ?? '-' }}</div>
      </div>
    </div>
  </div>
  @endif
@endsection