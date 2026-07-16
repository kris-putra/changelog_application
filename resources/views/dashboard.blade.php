@extends('layouts.app')

@section('content')
  <style>
    /* Summary Cards */
    .summary-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.08);
      transition: box-shadow 0.2s ease;
    }
    .summary-card:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .summary-card .card-body {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100px;
    }

    /* Table Container */
    .table-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.08);
      overflow: hidden;
      height: 560px;
      display: flex;
      flex-direction: column;
    }
    .table-card .card-body {
      padding: 0;
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 0;
    }
    .table-scroll {
      overflow-y: auto;
      flex: 1;
      min-height: 0;
    }
    .table-scroll thead th {
      position: sticky;
      top: 0;
      z-index: 1;
    }


    /* Table */
    .dashboard-table {
      margin-bottom: 0;
    }
    .dashboard-table thead th {
      background: #f8f6f1;
      border-bottom: 2px solid #e4dccb;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #555;
      padding: 12px 16px;
      white-space: nowrap;
      vertical-align: middle;
    }
    .dashboard-table tbody td {
      padding: 14px 16px;
      vertical-align: middle;
      font-size: 0.875rem;
      border-bottom: 1px solid #f0ece4;
    }
    .dashboard-table tbody tr:last-child td {
      border-bottom: none;
    }
    .dashboard-table tbody tr:hover {
      background: #faf8f4;
    }

    /* Column widths */
    .col-reqnum   { width: 18%; }
    .col-app      { width: 10%; }
    .col-title    { width: 30%; }
    .col-priority { width: 8%; text-align: center; }
    .col-type     { width: 8%; text-align: center; }
    .col-status   { width: 8%; text-align: center; }
    .col-date     { width: 10%; }
    .col-actions  { width: 8%; text-align: center; }
    .col-app-name { width: 35%; }
    .col-app-url  { width: 30%; }
    .col-app-date { width: 20%; }


    /* Action icons */
    .action-icons {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 16px;
      flex-wrap: nowrap;
    }
    .action-icons a,
    .action-icons button {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.15s ease, color 0.15s ease;
      cursor: pointer;
    }
    .action-icons a:hover,
    .action-icons button:hover {
      transform: scale(1.2);
    }
    .action-icons i {
      font-size: 1.15rem;
    }

    /* Badge centering */
    .badge-cell {
      display: flex;
      justify-content: center;
    }

    /* Sortable headers */
    .sortable {
      cursor: pointer;
      user-select: none;
      text-decoration: none;
      color: #555;
      transition: background 0.15s ease;
    }
    .sortable:hover {
      background: #eee8db;
    }
    .sort-header {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap;
      background: #dc3545;
      color: #212529;
      border-radius: 4px;
      padding: 4px 10px;
      transition: background 0.15s ease;
    }
    .sort-header:hover {
      background: #c82333;
    }
    .sort-reset {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: #6c757d;
      color: #fff;
      cursor: pointer;
      font-size: 0.6rem;
      text-decoration: none;
      transition: background 0.2s ease, transform 0.2s ease;
      line-height: 1;
      vertical-align: middle;
    }
    .sort-reset:hover {
      background: #5a6268;
      transform: scale(1.05);
    }
    .sort-arrow {
      font-size: 0.75rem;
    }
  </style>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3 mb-1">Dashboard</h1>
      <p class="text-muted mb-0">Ringkasan seluruh permintaan perubahan aplikasi.</p>
    </div>
  </div>

  {{-- Summary Cards --}}
  <div class="row g-3 mb-4">
    <div class="col-lg col-md-6 col-12">
      <div class="card summary-card text-center">
        <div class="card-body">
          <div>
            <h6 class="text-muted mb-1">Total Request</h6>
            <h2 class="mb-0 fw-bold">{{ $totalRequests }}</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg col-md-6 col-12">
      <div class="card summary-card text-center">
        <div class="card-body">
          <div>
            <h6 class="text-muted mb-1">Open</h6>
            <h2 class="mb-0 fw-bold text-primary">{{ $openCount }}</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg col-md-6 col-12">
      <div class="card summary-card text-center">
        <div class="card-body">
          <div>
            <h6 class="text-muted mb-1">In Progress</h6>
            <h2 class="mb-0 fw-bold text-warning">{{ $inProgressCount }}</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg col-md-6 col-12">
      <div class="card summary-card text-center">
        <div class="card-body">
          <div>
            <h6 class="text-muted mb-1">Completed</h6>
            <h2 class="mb-0 fw-bold text-success">{{ $completedCount }}</h2>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg col-md-6 col-12">
      <div class="card summary-card text-center">
        <div class="card-body">
          <div>
            <h6 class="text-muted mb-1">Applications</h6>
            <h2 class="mb-0 fw-bold text-dark">{{ $totalApplications }}</h2>
          </div>
        </div>
      </div>
    </div>
  </div>



  <div class="row g-3 mb-3">
  {{-- Feature Request Table --}}
  <div class="col-12">
  <div class="card table-card">

    <div class="card-body">
      @if($requests->isEmpty())
        <div class="alert alert-info mb-0 m-4">Belum ada Feature Request.</div>
      @else
        <div class="table-responsive table-scroll">
          <table class="table dashboard-table align-middle">


            <thead>
              @php
                $sortableCols = [
                  'request_number' => 'Request Number',
                  'application'    => 'Aplikasi',
                  'title'          => 'Nama Perubahan',
                  'priority'       => 'Prioritas',
                  'type'           => 'Tipe',
                  'status'         => 'Status',
                  'created_at'     => 'Tanggal',
                ];
                $colClasses = [
                  'request_number' => 'col-reqnum',
                  'application'    => 'col-app',
                  'title'          => 'col-title',
                  'priority'       => 'col-priority text-center',
                  'type'           => 'col-type text-center',
                  'status'         => 'col-status text-center',
                  'created_at'     => 'col-date',
                ];
                $cycleCols = ['priority', 'type', 'status'];
                $cycleCounts = ['priority' => 3, 'type' => 4, 'status' => 3];

                function sortUrl($col, $currentSort, $currentOrder, $currentCycle, $cycleCols, $cycleCounts) {
                  $params = ['sort' => $col];
                  if (in_array($col, $cycleCols)) {
                    // Cycle-based: if same column, increment cycle; else start at cycle 0
                    $nextCycle = ($col === $currentSort) ? ($currentCycle + 1) : 0;
                    $params['order'] = 'asc';
                    $params['cycle'] = $nextCycle;
                  } else {
                    // Standard: toggle asc/desc
                    if ($col === $currentSort) {
                      $params['order'] = $currentOrder === 'asc' ? 'desc' : 'asc';
                    } else {
                      $params['order'] = 'asc';
                    }
                  }
                  return route('dashboard', $params);
                }
              @endphp
              <tr>
                @foreach($sortableCols as $colKey => $colLabel)
                  @php
                    $isActive = (isset($sort) && $sort === $colKey);
                    $thClass = $colClasses[$colKey] ?? '';
                  @endphp
                  <th class="{{ $thClass }} {{ $isActive ? '' : 'sortable' }}">
                    @if($isActive)
                      <div class="sort-header">
                        <a href="{{ sortUrl($colKey, $sort ?? null, $order ?? 'asc', $cycle ?? 0, $cycleCols, $cycleCounts) }}"
                           class="text-dark text-decoration-none">
                          {{ $colLabel }}
                        </a>
                        <i class="bi {{ ($order ?? 'asc') === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }} sort-arrow"></i>
                        <a href="{{ route('dashboard') }}" class="sort-reset"
                           data-bs-toggle="tooltip" data-bs-placement="top" title="Clear Sort">
                          <i class="bi bi-x-lg"></i>
                        </a>
                      </div>
                    @else
                      <a href="{{ sortUrl($colKey, $sort ?? null, $order ?? 'asc', $cycle ?? 0, $cycleCols, $cycleCounts) }}"
                         class="text-muted text-decoration-none">
                        {{ $colLabel }}
                      </a>
                    @endif
                  </th>
                @endforeach
                <th class="col-actions text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($requests as $r)
                <tr>
                  <td><code class="text-dark">{{ $r->request_number }}</code></td>
                  <td>{{ $r->application?->name ?? '-' }}</td>
                  <td class="fw-semibold">{{ $r->title }}</td>
                  <td>
                    <div class="badge-cell">
                      <span class="badge bg-secondary">{{ ucfirst($r->priority) }}</span>
                    </div>
                  </td>
                  <td>
                    <div class="badge-cell">
                      <span class="badge bg-info text-dark">{{ ucfirst($r->type) }}</span>
                    </div>
                  </td>
                  <td>
                    <div class="badge-cell">
                      @if($r->status === 'Open')
                        <span class="badge bg-primary">Open</span>
                      @elseif($r->status === 'In Progress')
                        <span class="badge bg-warning text-dark">In Progress</span>
                      @elseif($r->status === 'Completed')
                        <span class="badge bg-success">Completed</span>
                      @endif
                    </div>
                  </td>
                  <td>{{ $r->created_at->format('d M Y') }}</td>
                  <td>
                    <div class="action-icons">
                      {{-- View (selalu ada) --}}
                      <a href="{{ route('feature-requests.show', $r) }}"
                         class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                        <i class="bi bi-eye"></i>
                      </a>

                      @if($r->status === 'Open')
                        {{-- Edit --}}
                        <a href="{{ route('feature-requests.edit', $r) }}"
                           class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                          <i class="bi bi-pencil-square"></i>
                        </a>
                        {{-- Start --}}
                        <button type="button" class="btn btn-link p-0 text-warning border-0"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Start"
                                onclick="openStartModal({{ $r->id }})">
                          <i class="bi bi-play-circle"></i>
                        </button>
                        {{-- Delete --}}
                        <form action="{{ route('feature-requests.destroy', $r) }}" method="post" class="d-inline"
                              id="delete-form-{{ $r->id }}">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="btn btn-link p-0 text-danger border-0"
                                  data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"
                                  onclick="confirmDelete({{ $r->id }})">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      @elseif($r->status === 'In Progress')
                        {{-- Cancel --}}
                        <form action="{{ route('feature-requests.cancel', $r) }}" method="post" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-link p-0 text-secondary border-0"
                                  data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise"></i>
                          </button>
                        </form>
                        {{-- Completed --}}
                        <button type="button" class="btn btn-link p-0 text-success border-0"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Completed"
                                onclick="openCompleteModal({{ $r->id }})">
                          <i class="bi bi-check-circle"></i>
                        </button>
                      @elseif($r->status === 'Completed')
                        {{-- Edit Completed Data --}}
                        <button type="button" class="btn btn-link p-0 text-primary border-0"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Data Penyelesaian"
                                onclick="openEditCompleteModal({{ $r->id }})">
                          <i class="bi bi-pencil-square"></i>
                        </button>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
  </div>

  </div>

  <div class="row g-3">
  {{-- Daftar Aplikasi --}}
  <div class="col-12">
  <div class="card table-card">

    <div class="card-body">
      @if($applications->isEmpty())
        <div class="alert alert-info mb-0 m-4">Belum ada Aplikasi.</div>
      @else
        <div class="table-responsive table-scroll">
          <table class="table dashboard-table align-middle">
            <thead>
              <tr>
                <th class="col-app-name">Nama Aplikasi</th>
                <th class="col-app-url">URL</th>
                <th class="col-app-date">Tanggal Dibuat</th>
                {{-- Future Ready: kolom "Website Status" (Online/Offline/Maintenance) dapat ditambahkan di sini --}}
                <th class="col-actions text-center">Aksi</th>
              </tr>
            </thead>

            <tbody>
              @foreach($applications as $app)
                <tr>
                  <td class="fw-semibold">{{ $app->name }}</td>
                  <td>{{ $app->url }}</td>
                  <td>{{ $app->created_at->format('d M Y') }}</td>
                  <td>
                    <div class="action-icons">
                      <a href="{{ route('applications.edit', $app) }}"
                         class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <form action="{{ route('applications.destroy', $app) }}" method="post" class="d-inline"
                            id="delete-app-form-{{ $app->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-link p-0 text-danger border-0"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"
                                onclick="confirmDeleteApp({{ $app->id }})">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
  </div>
  </div>

  {{-- Start Progress Modal --}}
  <style>
    /* Start Progress Modal – responsive width */
    #startProgressModal .modal-dialog {
      max-width: 760px;
      width: 100%;
    }
    @media (max-width: 991.98px) {
      #startProgressModal .modal-dialog {
        max-width: 90vw;
      }
    }
    @media (max-width: 575.98px) {
      #startProgressModal .modal-dialog {
        max-width: calc(100vw - 1rem);
        margin: 0.5rem auto;
      }
    }
    #startProgressModal .modal-header {
      padding: 24px 24px 16px 24px;
    }
    #startProgressModal .modal-body {
      padding: 0 24px 24px 24px;
    }
    #startProgressModal .modal-body .mb-3:last-child {
      margin-bottom: 0 !important;
    }
    #startProgressModal .modal-footer {
      padding: 16px 24px 20px 24px;
    }
  </style>
  <div class="modal fade" id="startProgressModal" tabindex="-1" aria-labelledby="startProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form id="startProgressForm" method="POST" action="">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="startProgressModalLabel">Data Pelaksanaan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="start-pic" class="form-label fw-semibold">PIC <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="start-pic" name="pic" placeholder="Nama PIC yang bertanggung jawab." required>
            </div>
            <div class="mb-3">
              <label for="start-rollback" class="form-label fw-semibold">Rollback Plan <span class="text-danger">*</span></label>
              <textarea class="form-control" id="start-rollback" name="rollback_plan" rows="6" placeholder="Langkah untuk mengembalikan sistem jika implementasi gagal." required></textarea>
            </div>
            <div class="mb-3">
              <label for="start-estimasi" class="form-label fw-semibold">Estimasi Selesai <span class="text-danger">*</span></label>
              <input type="datetime-local" class="form-control" id="start-estimasi" name="estimated_finish_at" required>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning px-4" id="confirmStartBtn">Mulai</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <style>
    #completeModal .modal-dialog { max-width: 940px; width: 100%; }
    #completeModal .modal-body { padding: 0 24px 24px 24px; }
    #completeModal .modal-header { padding: 24px 24px 16px 24px; }
    #completeModal .modal-footer { padding: 16px 24px 20px 24px; }

    /* Twin column panel */
    #completeModal .twin-column-panel {
      border: 1px solid #dee2e6;
      border-radius: 0.375rem;
      background: #fff;
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    #completeModal .twin-column-header {
      padding: 10px 12px 8px 12px;
      border-bottom: 1px solid #dee2e6;
      background: #f8f9fa;
      border-radius: 0.375rem 0.375rem 0 0;
    }
    #completeModal .twin-column-header .form-label {
      margin-bottom: 0;
      font-size: 0.875rem;
    }
    #completeModal .twin-column-body {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 0;
      padding: 0;
    }

    /* Scroll boxes – identical for both */
    #completeModal .component-scroll-box,
    #completeModal .app-scroll-box {
      height: 220px;
      overflow-y: auto;
      border: none;
      border-radius: 0;
      padding: 8px 12px;
      background: #fff;
      flex: 1;
      min-height: 0;
    }
    #completeModal .component-scroll-box .form-check,
    #completeModal .app-scroll-box .form-check {
      margin-bottom: 4px;
    }

    /* Bottom input area – identical for both */
    #completeModal .twin-column-footer {
      border-top: 1px solid #dee2e6;
      padding: 10px 12px;
      background: #f8f9fa;
      border-radius: 0 0 0.375rem 0.375rem;
    }
    #completeModal .twin-column-footer .form-label {
      font-size: 0.8rem;
      margin-bottom: 4px;
    }

    #completeModal .component-error { color: #dc3545; font-size: 0.8rem; display: none; margin-top: 4px; }
    #completeModal .add-component-feedback { display: none; margin-top: 4px; }

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
            <h5 class="modal-title" id="completeModalLabel">Selesaikan Perubahan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              {{-- Left column: Technical Components --}}
              <div class="col-md-6">
                <div class="twin-column-panel">
                  <div class="twin-column-header">
                    <label class="form-label fw-semibold">Komponen yang Diubah <span class="text-danger">*</span></label>
                  </div>
                  <div class="twin-column-body">
                    <div class="component-scroll-box" id="componentsScrollBox">
                      <div class="text-muted small text-center py-2">Memuat komponen...</div>
                    </div>
                  </div>
                  <div class="twin-column-footer">
                    <div class="component-error" id="componentError">Pilih minimal satu komponen.</div>
                    <label class="form-label fw-semibold">Tambah Komponen Baru</label>
                    <input type="text" class="form-control form-control-sm mb-2" id="newComponentInput" placeholder="Nama komponen baru...">
                    <button class="btn btn-sm btn-primary w-100" type="button" id="addComponentBtn">Tambah Komponen</button>
                    <div id="addComponentFeedback" class="add-component-feedback small"></div>
                  </div>
                </div>
              </div>

              {{-- Right column: Affected Applications --}}
              <div class="col-md-6">
                <div class="twin-column-panel">
                  <div class="twin-column-header">
                    <label class="form-label fw-semibold">Aplikasi Terdampak</label>
                  </div>
                  <div class="twin-column-body">
                    <div id="noApplicationsMsg" class="text-muted small text-center py-2" style="display: none;">Tidak ada aplikasi yang tersedia.</div>
                    <div id="appSearchContainer">
                      <div class="app-scroll-box" id="appsScrollBox">
                        <div class="text-muted small text-center py-2">Memuat aplikasi...</div>
                      </div>
                    </div>
                  </div>
                  <div class="twin-column-footer">
                    <label class="form-label fw-semibold">Cari Aplikasi</label>
                    <input type="text" class="form-control form-control-sm" id="appFilterInput"
                           placeholder="Ketik nama aplikasi..." autocomplete="off">
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-0">
              <label for="complete-lesson" class="form-label fw-semibold">Lesson Learned <span class="text-danger">*</span></label>
              <textarea class="form-control" id="complete-lesson" name="lesson_learned" rows="4" placeholder="Tuliskan pembelajaran, kendala, atau rekomendasi setelah perubahan selesai." required></textarea>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success px-4" id="confirmCompleteBtn">Selesai</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Delete Confirmation Modal --}}

  <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteConfirmModalLabel">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Yakin ingin menghapus permintaan ini?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Initialize Bootstrap Tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.forEach(function (el) {
        new bootstrap.Tooltip(el);
      });
    });

    // Start Progress Modal
    var startModal = null;
    var featureRequestId = null;

    function openStartModal(id) {
      if (id === null || id === undefined || !Number.isFinite(Number(id)) || Number(id) < 1 || !Number.isInteger(Number(id))) {
        alert('ID permintaan tidak valid. Silakan muat ulang halaman.');
        return;
      }
      featureRequestId = Number(id);
      var form = document.getElementById('startProgressForm');
      form.action = '/feature-requests/' + featureRequestId + '/save-execution';
      form.reset();
      if (!startModal) {
        startModal = new bootstrap.Modal(document.getElementById('startProgressModal'));
      }
      startModal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
      var startForm = document.getElementById('startProgressForm');
      var startBtn = document.getElementById('confirmStartBtn');
      var cancelBtn = startForm.querySelector('[data-bs-dismiss="modal"]');

      startBtn.addEventListener('click', function (e) {
        e.preventDefault();
        if (!startForm.checkValidity()) {
          startForm.reportValidity();
          return;
        }

        // Prevent double submission
        startBtn.disabled = true;
        startBtn.textContent = 'Menyimpan...';
        if (cancelBtn) cancelBtn.disabled = true;

        var formData = new FormData(startForm);

        fetch(startForm.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
              ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              : formData.get('_token'),
          },
        })
          .then(function (response) {
            if (response.ok) {
              return response.json();
            }
            return response.json().then(function (err) {
              var msg = 'Terjadi kesalahan validasi.';
              if (err.errors) {
                msg = Object.values(err.errors).map(function (e) { return e[0]; }).join('\n');
              } else if (err.message) {
                msg = err.message;
              }
              throw new Error(msg);
            });
          })
          .then(function (data) {
            if (data.success) {
              // Store toast for after reload
              sessionStorage.setItem('toast_data', JSON.stringify({
                type: 'success',
                title: 'Success',
                message: data.message,
              }));
              // Close modal then reload
              startModal.hide();
              window.location.reload();
            } else {
              alert(data.message || 'Terjadi kesalahan.');
              resetStartBtn();
            }
          })
          .catch(function (err) {
            alert(err.message || 'Terjadi kesalahan jaringan. Silakan coba lagi.');
            resetStartBtn();
          });
      });

      function resetStartBtn() {
        startBtn.disabled = false;
        startBtn.textContent = 'Mulai';
        if (cancelBtn) cancelBtn.disabled = false;
      }

      var startModalEl = document.getElementById('startProgressModal');
      startModalEl.addEventListener('hidden.bs.modal', function () {
        startForm.reset();
        resetStartBtn();
      });

      // Show toast from sessionStorage after page reload
      var pendingToast = sessionStorage.getItem('toast_data');
      if (pendingToast) {
        sessionStorage.removeItem('toast_data');
        try {
          var toastData = JSON.parse(pendingToast);
          var toastContainer = document.createElement('div');
          toastContainer.className = 'position-fixed top-0 end-0 p-3';
          toastContainer.style.zIndex = '1080';
          toastContainer.innerHTML =
            '<div id="appToast" class="toast align-items-center bg-success text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">' +
              '<div class="d-flex">' +
                '<div class="toast-body">' +
                  '<strong class="me-1">' + (toastData.title || 'Success') + '</strong> ' +
                  (toastData.message || '') +
                '</div>' +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
              '</div>' +
            '</div>';
          document.body.appendChild(toastContainer);
          var toastEl = toastContainer.querySelector('.toast');
          var bsToast = new bootstrap.Toast(toastEl, { delay: 5000 });
          bsToast.show();
        } catch (e) { /* ignore parse errors */ }
      }
    });

    // Delete confirmation via Bootstrap Modal
    var deleteFormId = null;
    var deleteModal = null;

    function confirmDelete(id) {
      deleteFormId = id;
      if (!deleteModal) {
        deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
      }
      deleteModal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
      document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (deleteFormId) {
          document.getElementById('delete-form-' + deleteFormId).submit();
        }
      });
    });

    // Delete confirmation for Applications (reuses the same modal)
    var deleteAppFormId = null;

    function confirmDeleteApp(id) {
      deleteAppFormId = id;
      deleteFormId = null;
      if (!deleteModal) {
        deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
      }
      deleteModal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
      document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (deleteAppFormId) {
          document.getElementById('delete-app-form-' + deleteAppFormId).submit();
          deleteAppFormId = null;
        }
      });
    });

    // Complete Modal
    var completeModal = null;
    var allApplications = [];
    var technicalComponentsLoaded = false;

    /* ========== Technical Components ========== */

    function loadTechnicalComponents(callback) {
      if (technicalComponentsLoaded) { if (callback) callback(); return; }
      fetch('/api/technical-components')
        .then(function (res) { return res.json(); })
        .then(function (components) {
          renderComponentsList(components);
          technicalComponentsLoaded = true;
          if (callback) callback();
        })
        .catch(function () {
          document.getElementById('componentsScrollBox').innerHTML = '<div class="text-danger small">Gagal memuat komponen.</div>';
        });
    }

    function renderComponentsList(components) {
      var box = document.getElementById('componentsScrollBox');
      box.innerHTML = '';
      if (components.length === 0) {
        box.innerHTML = '<div class="text-muted small text-center py-2">Belum ada komponen.</div>';
        return;
      }
      components.forEach(function (comp) {
        var div = document.createElement('div');
        div.className = 'form-check';
        var slug = comp.name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
        div.innerHTML =
          '<input class="form-check-input component-checkbox" type="checkbox" name="technical_component_ids[]" value="' + comp.id + '" id="comp-' + slug + '">' +
          '<label class="form-check-label" for="comp-' + slug + '">' + escapeHtml(comp.name) + '</label>';
        box.appendChild(div);
      });
    }

    /* ========== Add New Component ========== */

    document.addEventListener('DOMContentLoaded', function () {
      var addBtn = document.getElementById('addComponentBtn');
      var addInput = document.getElementById('newComponentInput');
      var feedback = document.getElementById('addComponentFeedback');

      addBtn.addEventListener('click', function () { addNewComponent(); });
      addInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); addNewComponent(); }
      });

      function addNewComponent() {
        var name = addInput.value.trim();
        if (!name) {
          feedback.style.display = 'block';
          feedback.className = 'add-component-feedback small text-danger';
          feedback.textContent = 'Nama komponen tidak boleh kosong.';
          return;
        }

        addBtn.disabled = true;
        addBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Menambahkan...';

        // Resilient CSRF token: meta tag → hidden _token input → error
        var csrfToken = '';
        try {
          var metaTag = document.querySelector('meta[name="csrf-token"]');
          if (metaTag) {
            csrfToken = metaTag.getAttribute('content');
          }
        } catch (e) { /* meta tag not found or error reading it */ }
        if (!csrfToken) {
          var tokenInput = document.querySelector('input[name="_token"]');
          if (tokenInput) {
            csrfToken = tokenInput.value;
          }
        }
        if (!csrfToken) {
          feedback.style.display = 'block';
          feedback.className = 'add-component-feedback small text-danger';
          feedback.textContent = 'Token CSRF tidak ditemukan. Muat ulang halaman.';
          addBtn.disabled = false;
          addBtn.textContent = 'Tambah Komponen';
          return;
        }

        try {
          fetch('/api/technical-components', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ name: name })
          })
          .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, data: data }; }); })
          .then(function (result) {
            if (result.ok && result.data.success) {
              var comp = result.data;
              var box = document.getElementById('componentsScrollBox');
              var placeholder = box.querySelector('.text-muted.text-center');
              if (placeholder) placeholder.remove();

              if (comp.duplicate) {
                // Duplicate: find the existing checkbox and check it
                var existingCb = box.querySelector('input[value="' + comp.id + '"]');
                if (existingCb) {
                  existingCb.checked = true;
                  // Flash the label briefly
                  var parentDiv = existingCb.closest('.form-check');
                  if (parentDiv) {
                    parentDiv.style.background = '#d1e7dd';
                    setTimeout(function () { parentDiv.style.background = ''; }, 1500);
                  }
                }
                feedback.style.display = 'block';
                feedback.className = 'add-component-feedback small text-info';
                feedback.textContent = '"' + comp.name + '" sudah ada. Otomatis dipilih.';
              } else {
                // New component: append and check it
                var div = document.createElement('div');
                div.className = 'form-check';
                var slug = comp.name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
                div.innerHTML =
                  '<input class="form-check-input component-checkbox" type="checkbox" name="technical_component_ids[]" value="' + comp.id + '" id="comp-' + slug + '" checked>' +
                  '<label class="form-check-label" for="comp-' + slug + '">' + escapeHtml(comp.name) + '</label>';
                box.appendChild(div);
                sortComponentsList();

                feedback.style.display = 'block';
                feedback.className = 'add-component-feedback small text-success';
                feedback.textContent = '"' + comp.name + '" berhasil ditambahkan.';
              }

              addInput.value = '';
              setTimeout(function () { feedback.style.display = 'none'; }, 3000);
              technicalComponentsLoaded = false;
            } else {
              feedback.style.display = 'block';
              feedback.className = 'add-component-feedback small text-danger';
              feedback.textContent = result.data.message || 'Gagal menambahkan komponen.';
            }
          })
          .catch(function () {
            feedback.style.display = 'block';
            feedback.className = 'add-component-feedback small text-danger';
            feedback.textContent = 'Terjadi kesalahan jaringan.';
          })
          .finally(function () {
            addBtn.disabled = false;
            addBtn.textContent = 'Tambah Komponen';
          });
        } catch (e) {
          feedback.style.display = 'block';
          feedback.className = 'add-component-feedback small text-danger';
          feedback.textContent = 'Terjadi kesalahan: ' + e.message;
          addBtn.disabled = false;
          addBtn.textContent = 'Tambah Komponen';
        }
      }

      function sortComponentsList() {
        var box = document.getElementById('componentsScrollBox');
        var checks = Array.from(box.querySelectorAll('.form-check'));
        checks.sort(function (a, b) {
          var nameA = a.querySelector('label').textContent.toLowerCase();
          var nameB = b.querySelector('label').textContent.toLowerCase();
          return nameA.localeCompare(nameB);
        });
        checks.forEach(function (el) { box.appendChild(el); });
      }
    });

    /* ========== Affected Applications ========== */

    function loadAllApplications(callback) {
      fetch('/api/applications/search?q=')
        .then(function (res) { return res.json(); })
        .then(function (apps) {
          allApplications = apps.sort(function (a, b) { return a.name.localeCompare(b.name); });
          renderApplicationsList(allApplications);
          var noMsg = document.getElementById('noApplicationsMsg');
          var container = document.getElementById('appSearchContainer');
          if (allApplications.length === 0) {
            noMsg.style.display = 'block';
            container.style.display = 'none';
          } else {
            noMsg.style.display = 'none';
            container.style.display = 'block';
          }
          if (callback) callback();
        })
        .catch(function () {
          document.getElementById('appsScrollBox').innerHTML = '<div class="text-danger small">Gagal memuat aplikasi.</div>';
        });
    }

    function renderApplicationsList(apps) {
      var box = document.getElementById('appsScrollBox');
      box.innerHTML = '';
      if (apps.length === 0) {
        box.innerHTML = '<div class="text-muted small text-center py-2">Tidak ditemukan.</div>';
        return;
      }
      apps.forEach(function (app) {
        var div = document.createElement('div');
        div.className = 'form-check';
        div.setAttribute('data-app-name', app.name.toLowerCase());
        var slug = app.name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
        div.innerHTML =
          '<input class="form-check-input app-checkbox" type="checkbox" value="' + app.id + '" id="app-' + slug + '" data-app-name="' + escapeHtml(app.name) + '">' +
          '<label class="form-check-label" for="app-' + slug + '">' + escapeHtml(app.name) + '</label>';
        box.appendChild(div);
      });
    }

    // App filter
    document.addEventListener('DOMContentLoaded', function () {
      var filterInput = document.getElementById('appFilterInput');
      filterInput.addEventListener('input', function () {
        var query = this.value.trim().toLowerCase();
        var checks = document.querySelectorAll('#appsScrollBox .form-check');
        checks.forEach(function (div) {
          var appName = div.getAttribute('data-app-name') || '';
          if (query === '' || appName.indexOf(query) !== -1) {
            div.style.display = '';
          } else {
            div.style.display = 'none';
          }
        });
      });
    });

    /* ========== Open Complete Modal ========== */

    function openCompleteModal(id) {
      if (id === null || id === undefined || !Number.isFinite(Number(id)) || Number(id) < 1 || !Number.isInteger(Number(id))) {
        alert('ID permintaan tidak valid. Silakan muat ulang halaman.');
        return;
      }
      featureRequestId = Number(id);
      var form = document.getElementById('completeForm');
      form.action = '/feature-requests/' + featureRequestId + '/complete';
      form.reset();
      document.getElementById('componentError').style.display = 'none';
      document.getElementById('appFilterInput').value = '';

      // Remove any _method input
      var methodInput = form.querySelector('input[name="_method"]');
      if (methodInput) methodInput.remove();

      // Reset add component feedback
      var feedback = document.getElementById('addComponentFeedback');
      feedback.style.display = 'none';
      document.getElementById('newComponentInput').value = '';

      // Load components
      technicalComponentsLoaded = false;
      document.getElementById('componentsScrollBox').innerHTML = '<div class="text-muted small text-center py-2">Memuat komponen...</div>';
      loadTechnicalComponents();

      // Load applications
      document.getElementById('appsScrollBox').innerHTML = '<div class="text-muted small text-center py-2">Memuat aplikasi...</div>';
      loadAllApplications();

      if (!completeModal) {
        completeModal = new bootstrap.Modal(document.getElementById('completeModal'));
      }
      completeModal.show();
    }

    /* ========== Open Edit Complete Modal ========== */

    function openEditCompleteModal(id) {
      if (id === null || id === undefined || !Number.isFinite(Number(id)) || Number(id) < 1 || !Number.isInteger(Number(id))) {
        alert('ID permintaan tidak valid. Silakan muat ulang halaman.');
        return;
      }
      featureRequestId = Number(id);
      var form = document.getElementById('completeForm');
      form.action = '/feature-requests/' + featureRequestId + '/update-completed';

      var methodInput = form.querySelector('input[name="_method"]');
      if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        form.appendChild(methodInput);
      }
      methodInput.value = 'PUT';

      document.getElementById('componentError').style.display = 'none';
      document.getElementById('appFilterInput').value = '';
      document.getElementById('complete-lesson').value = '';

      // Reset add component feedback
      var feedback = document.getElementById('addComponentFeedback');
      feedback.style.display = 'none';
      document.getElementById('newComponentInput').value = '';

      fetch('/api/feature-requests/' + id + '/completed-data')
        .then(function (res) { return res.json(); })
        .then(function (data) {
          document.getElementById('complete-lesson').value = data.lesson_learned || '';

          // Load components
          technicalComponentsLoaded = false;
          document.getElementById('componentsScrollBox').innerHTML = '<div class="text-muted small text-center py-2">Memuat komponen...</div>';
          loadTechnicalComponents(function () {
            var compIds = data.technical_component_ids || [];
            compIds.forEach(function (cid) {
              var cb = document.querySelector('#componentsScrollBox input[value="' + cid + '"]');
              if (cb) cb.checked = true;
            });
          });

          // Load applications
          document.getElementById('appsScrollBox').innerHTML = '<div class="text-muted small text-center py-2">Memuat aplikasi...</div>';
          loadAllApplications(function () {
            var appIds = data.affected_application_ids || [];
            appIds.forEach(function (aid) {
              var cb = document.querySelector('#appsScrollBox input[value="' + aid + '"]');
              if (cb) cb.checked = true;
            });
          });
        });

      if (!completeModal) {
        completeModal = new bootstrap.Modal(document.getElementById('completeModal'));
      }
      completeModal.show();
    }

    /* ========== Form Submission ========== */

    document.addEventListener('DOMContentLoaded', function () {
      var completeForm = document.getElementById('completeForm');
      var completeBtn = document.getElementById('confirmCompleteBtn');
      var completeCancelBtn = completeForm.querySelector('[data-bs-dismiss="modal"]');

      completeForm.addEventListener('submit', function (e) {
        e.preventDefault();

        var checkedComponents = completeForm.querySelectorAll('.component-checkbox:checked');
        if (checkedComponents.length === 0) {
          document.getElementById('componentError').style.display = 'block';
          return;
        }
        document.getElementById('componentError').style.display = 'none';

        var lessonField = document.getElementById('complete-lesson');
        if (!lessonField.value.trim()) {
          lessonField.setCustomValidity('Lesson Learned wajib diisi.');
          completeForm.reportValidity();
          lessonField.setCustomValidity('');
          return;
        }

        // Validate featureRequestId before proceeding
        if (!featureRequestId || !Number.isFinite(featureRequestId) || !Number.isInteger(featureRequestId) || featureRequestId < 1) {
          alert('ID permintaan tidak valid. Silakan tutup modal dan coba lagi.');
          return;
        }

        completeBtn.disabled = true;
        completeBtn.textContent = 'Menyimpan...';
        if (completeCancelBtn) completeCancelBtn.disabled = true;

        var formData = new FormData(completeForm);

        // Collect checked app ids
        var checkedApps = completeForm.querySelectorAll('.app-checkbox:checked');
        checkedApps.forEach(function (cb) {
          formData.append('affected_application_ids[]', cb.value);
        });

        // Resilient CSRF token: meta tag → hidden _token input → error
        var csrfToken = '';
        try {
          var metaTag = document.querySelector('meta[name="csrf-token"]');
          if (metaTag) {
            csrfToken = metaTag.getAttribute('content');
          }
        } catch (e) { /* meta tag not found */ }
        if (!csrfToken) {
          csrfToken = formData.get('_token') || '';
        }
        if (!csrfToken) {
          alert('Token CSRF tidak ditemukan. Muat ulang halaman.');
          resetCompleteBtn();
          return;
        }

        fetch(completeForm.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
          }
        })
        .then(function (response) {
          if (response.ok) {
            return response.json();
          }
          return response.json().then(function (err) {
            var msg = 'Terjadi kesalahan validasi.';
            if (err.errors) {
              msg = Object.values(err.errors).map(function (e) { return e[0]; }).join('\n');
            }
            throw new Error(msg);
          });
        })
        .then(function (data) {
          if (data.success) {
            sessionStorage.setItem('toast_data', JSON.stringify({
              type: 'success',
              title: 'Success',
              message: data.message,
            }));
            completeModal.hide();
            window.location.reload();
          } else {
            alert(data.message || 'Terjadi kesalahan.');
            resetCompleteBtn();
          }
        })
        .catch(function (err) {
          alert(err.message || 'Terjadi kesalahan jaringan.');
          resetCompleteBtn();
        });

        function resetCompleteBtn() {
          completeBtn.disabled = false;
          completeBtn.textContent = 'Selesai';
          if (completeCancelBtn) completeCancelBtn.disabled = false;
        }
      });

      document.getElementById('completeModal').addEventListener('hidden.bs.modal', function () {
        completeForm.reset();
        document.getElementById('componentError').style.display = 'none';
        document.getElementById('addComponentFeedback').style.display = 'none';
        document.getElementById('newComponentInput').value = '';
        document.getElementById('appFilterInput').value = '';
        completeBtn.disabled = false;
        completeBtn.textContent = 'Selesai';
        // Remove _method input if present
        var methodInput = completeForm.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
      });
    });

    /* ========== Helpers ========== */

    function escapeHtml(text) {
      var div = document.createElement('div');
      div.appendChild(document.createTextNode(text));
      return div.innerHTML;
    }
  </script>
@endsection

