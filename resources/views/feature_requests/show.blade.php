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
      <div class="mb-3">
        <small class="text-muted d-block mb-1">Dampak Perubahan</small>
        @if($requestItem->impact)
          <div class="border rounded-3 p-3 bg-light" style="white-space: pre-wrap;">{{ $requestItem->impact }}</div>
        @else
          <div class="border rounded-3 p-3 bg-light text-muted" style="white-space: pre-wrap;">Tidak ada dampak perubahan yang dicatat.</div>
        @endif
      </div>

      {{-- Attachment --}}
      <div class="mb-0">
        <small class="text-muted d-block mb-1">Lampiran</small>
        @if($requestItem->attachment_filename)
          <a href="{{ route('feature-requests.attachment', $requestItem) }}" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-paperclip me-1"></i>Buka Lampiran
          </a>
        @else
          <span class="text-muted">Tidak ada lampiran</span>
        @endif
      </div>
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
    <div class="card-header bg-light border-0 rounded-top-4 py-3 d-flex justify-content-between align-items-center">
      <h5 class="mb-0 fw-bold" style="color: #e65100;">
        <i class="bi bi-check-circle me-2"></i>Data Penyelesaian
      </h5>
      <button type="button" class="btn btn-sm btn-outline-primary" onclick="CompleteModal.openEdit({{ $requestItem->id }})">
        <i class="bi bi-pencil me-1"></i>Edit
      </button>
    </div>
    <div class="card-body px-4 py-4">
      {{-- Row: Tanggal Selesai --}}
      <div class="row mb-3">
        <div class="col-md-6">
          <small class="text-muted d-block">Tanggal Selesai</small>
          <strong>{{ $requestItem->completed_at ? $requestItem->completed_at->format('d M Y H:i') : '-' }}</strong>
        </div>
      </div>

      {{-- Komponen yang Diubah --}}
      <div class="mb-3">
        <small class="text-muted d-block mb-2">Komponen yang Diubah</small>
        @if($requestItem->technicalComponents && $requestItem->technicalComponents->count() > 0)
          <div class="d-flex flex-wrap gap-2">
            @foreach($requestItem->technicalComponents as $component)
              <span class="badge bg-primary rounded-pill">{{ $component->name }}</span>
            @endforeach
          </div>
        @else
          <span class="text-muted fst-italic">Tidak ada komponen yang dicatat.</span>
        @endif
      </div>

      {{-- Aplikasi Terdampak --}}
      <div class="mb-3">
        <small class="text-muted d-block mb-2">Aplikasi Terdampak</small>
        @if($requestItem->affectedApplications && $requestItem->affectedApplications->count() > 0)
          <div class="d-flex flex-wrap gap-2">
            @foreach($requestItem->affectedApplications as $app)
              <span class="badge bg-info text-dark rounded-pill">{{ $app->name }}</span>
            @endforeach
          </div>
        @else
          <span class="text-muted fst-italic">Tidak ada aplikasi terdampak.</span>
        @endif
      </div>

      {{-- Long text: Lesson Learned --}}
      <div class="mb-0">
        <small class="text-muted d-block mb-1">Lesson Learned</small>
        <div class="border rounded-3 p-3 bg-light" style="white-space: pre-wrap;">{{ $requestItem->lesson_learned ?? '-' }}</div>
      </div>
    </div>
  </div>
  @endif

  {{-- ======================================== --}}
  {{-- Edit Completed Modal (shared implementation) --}}
  {{-- ======================================== --}}
  <style>
    #completeModal .modal-dialog { max-width: 900px; width: 100%; }
    #completeModal .twin-column-panel { display: flex; flex-direction: column; }
    #completeModal .twin-column-panel .tc-panel-header { padding: 12px 16px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center; background: #f8f9fa; }
    #completeModal .twin-column-panel .tc-panel-header h6 { margin: 0; font-weight: 600; }
    #completeModal .twin-column-panel .tc-panel-body { display: flex; flex-wrap: wrap; height: 330px; min-height: 0; }
    #completeModal .twin-column-panel .tc-col-left,
    #completeModal .twin-column-panel .tc-col-right { flex: 1 1 50%; min-width: 280px; height: 100%; display: flex; flex-direction: column; overflow: hidden; }
    #completeModal .twin-column-panel .tc-col-header { padding: 8px 12px; font-weight: 600; border-bottom: 1px solid #e9ecef; flex-shrink: 0; font-size: 0.92rem; }
    #completeModal .twin-column-panel .tc-col-body { flex: 1; min-height: 0; overflow: hidden; padding: 8px; display: flex; flex-direction: column; }
    #completeModal .twin-column-panel .tc-scroll-area { flex: 1; min-height: 0; overflow-y: auto; }
    #completeModal .twin-column-panel .tc-col-footer { padding: 8px 12px; border-top: 1px solid #e9ecef; flex-shrink: 0; }
    #completeModal .twin-column-panel .tc-col-footer .form-control-sm { height: 28px; font-size: 0.82rem; padding: 2px 6px; }
    #completeModal .component-scroll-box,
    #completeModal .app-scroll-box {
      background: #fff;
    }
    #completeModal .component-scroll-box .form-check,
    #completeModal .app-scroll-box .form-check {
      margin-bottom: 4px;
    }
    #completeModal .add-component-section {
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px solid #dee2e6;
    }
    #completeModal .add-component-feedback { }
    @media (max-width: 767.98px) {
      #completeModal .modal-dialog { max-width: calc(100vw - 1rem); margin: 0.5rem auto; }
    }
  </style>
  <div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="completeForm" method="POST" action="">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="completeModalLabel">Edit Data Penyelesaian</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="twin-column-panel mb-3">
              <div class="tc-panel-header">
                <h6>Pilih Komponen & Aplikasi Terdampak</h6>
                <div id="appCountBadge" class="small text-muted"></div>
              </div>
              <div class="tc-panel-body">
                <div class="tc-col-left">
                  <div class="tc-col-header d-flex justify-content-between align-items-center">
                    <span>Komponen <span class="text-danger">*</span></span>
                  </div>
                  <div class="tc-col-body">
                    <div class="tc-scroll-area component-scroll-box" id="componentsScrollBox">
                      <div class="text-muted small text-center py-2">Memuat komponen...</div>
                    </div>
                  </div>
                  <div class="tc-col-footer">
                    <div class="add-component-section" style="margin-top:0;padding-top:0;border-top:none;">
                      <small class="fw-semibold text-muted">+ Tambah Komponen Baru</small>
                      <div class="input-group input-group-sm mt-1">
                        <input type="text" class="form-control form-control-sm" id="newComponentInput" placeholder="Nama komponen baru...">
                        <button class="btn btn-outline-primary btn-sm" type="button" id="addComponentBtn">Tambah Komponen</button>
                      </div>
                      <div id="addComponentFeedback" class="add-component-feedback small mt-1" style="display: none;"></div>
                    </div>
                  </div>
                </div>
                <div class="tc-col-right">
                  <div class="tc-col-header d-flex justify-content-between align-items-center">
                    <span>Aplikasi Terdampak</span>
                  </div>
                  <div class="tc-col-body">
                    <div id="noApplicationsMsg" class="text-muted small mb-2" style="display: none;">Tidak ada aplikasi yang tersedia.</div>
                    <div id="appSearchContainer">
                      <input type="text" class="form-control form-control-sm mb-2" id="appFilterInput"
                             placeholder="Cari aplikasi..." autocomplete="off">
                      <div class="tc-scroll-area app-scroll-box" id="appsScrollBox">
                        <div class="text-muted small text-center py-2">Memuat aplikasi...</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-0">
              <label for="complete-lesson" class="form-label fw-semibold mb-1">Lesson Learned <span class="text-danger">*</span></label>
              <textarea class="form-control" id="complete-lesson" name="lesson_learned" rows="3" placeholder="Tuliskan pembelajaran, kendala, atau rekomendasi setelah perubahan selesai." required></textarea>
            </div>
            <div class="component-error text-danger small mt-1" id="componentError" style="display: none;">Pilih minimal satu komponen.</div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success px-4" id="confirmCompleteBtn">Selesai</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/complete-modal.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      CompleteModal.init();
    });
  </script>
@endsection