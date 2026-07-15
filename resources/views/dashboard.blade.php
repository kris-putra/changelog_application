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

  {{-- Completed Modal --}}
  <style>
    /* Completed Modal – responsive width */
    #completeModal .modal-dialog {
      max-width: 900px;
      width: 100%;
    }
    @media (min-width: 992px) {
      #completeModal .col-components {
        flex: 0 0 40%;
        max-width: 40%;
      }
      #completeModal .col-apps {
        flex: 0 0 60%;
        max-width: 60%;
      }
    }
    @media (min-width: 576px) and (max-width: 991.98px) {
      #completeModal .modal-dialog {
        max-width: 90vw;
      }
      #completeModal .col-components,
      #completeModal .col-apps {
        flex: 0 0 50%;
        max-width: 50%;
      }
    }
    @media (max-width: 575.98px) {
      #completeModal .modal-dialog {
        max-width: calc(100vw - 1rem);
        margin: 0.5rem auto;
      }
      #completeModal .col-components,
      #completeModal .col-apps {
        flex: 0 0 100%;
        max-width: 100%;
      }
    }
    #completeModal .modal-header {
      padding: 24px 24px 16px 24px;
    }
    #completeModal .modal-body {
      padding: 0 24px 24px 24px;
    }
    #completeModal .modal-body .mb-3:last-child {
      margin-bottom: 0 !important;
    }
    #completeModal .modal-footer {
      padding: 16px 24px 20px 24px;
    }
    /* Component checkboxes */
    #completeModal .component-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 6px 16px;
    }
    @media (max-width: 575.98px) {
      #completeModal .component-grid {
        grid-template-columns: 1fr;
      }
    }
    /* Searchable multi-select */
    #completeModal .app-search-container {
      position: relative;
    }
    #completeModal .app-search-results {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      z-index: 1050;
      max-height: 200px;
      overflow-y: auto;
      background: #fff;
      border: 1px solid #dee2e6;
      border-top: none;
      border-radius: 0 0 0.375rem 0.375rem;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      display: none;
    }
    #completeModal .app-search-results .dropdown-item {
      padding: 8px 12px;
      cursor: pointer;
      font-size: 0.875rem;
    }
    #completeModal .app-search-results .dropdown-item:hover {
      background-color: #f8f9fa;
    }
    #completeModal .selected-apps {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 8px;
    }
    #completeModal .selected-apps .badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 0.8rem;
      padding: 5px 10px;
    }
    #completeModal .selected-apps .badge .btn-close {
      font-size: 0.55rem;
      padding: 0;
      margin-left: 2px;
      filter: invert(1);
    }
    #completeModal .component-error {
      color: #dc3545;
      font-size: 0.8rem;
      display: none;
      margin-top: 4px;
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
            {{-- Two-column layout: Components + Affected Applications --}}
            <div class="row mb-3">
              {{-- Left Column: Komponen yang Diubah --}}
              <div class="col-12 col-md-6 col-components">
                <label class="form-label fw-semibold">Komponen yang Diubah <span class="text-danger">*</span></label>
                <div class="component-grid" id="componentsGrid">
                  <div class="text-muted small text-center py-2" id="componentsLoading">Memuat komponen...</div>
                </div>
                <div class="component-error" id="componentError">Pilih minimal satu komponen.</div>
              </div>

              {{-- Right Column: Aplikasi Terdampak --}}
              <div class="col-12 col-md-6 col-apps">
                <label class="form-label fw-semibold">Aplikasi Terdampak</label>
                <div id="noApplicationsMsg" class="text-muted small mb-2" style="display: none;">Tidak ada aplikasi yang tersedia.</div>
                <div class="app-search-container" id="appSearchContainer">
                  <input type="text" class="form-control" id="appSearchInput"
                         placeholder="Ketik minimal 2 karakter untuk mencari..."
                         autocomplete="off">
                  <div class="app-search-results" id="appSearchResults"></div>
                </div>
                <div class="selected-apps" id="selectedApps"></div>
              </div>
            </div>

            {{-- Lesson Learned (full width, below both columns) --}}
            <div class="mb-3">
              <label for="complete-lesson" class="form-label fw-semibold">Lesson Learned <span class="text-danger">*</span></label>
              <textarea class="form-control" id="complete-lesson" name="lesson_learned" rows="5" placeholder="Tuliskan pembelajaran, kendala, atau rekomendasi setelah perubahan selesai." required></textarea>
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

    function openStartModal(id) {
      var form = document.getElementById('startProgressForm');
      form.action = '/feature-requests/' + id + '/save-execution';
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
    var selectedAppIds = [];
    var technicalComponentsLoaded = false;

    function loadTechnicalComponents(callback) {
      if (technicalComponentsLoaded) {
        if (callback) callback();
        return;
      }
      fetch('/api/technical-components')
        .then(function (res) { return res.json(); })
        .then(function (components) {
          var grid = document.getElementById('componentsGrid');
          grid.innerHTML = '';
          components.forEach(function (comp) {
            var div = document.createElement('div');
            div.className = 'form-check';
            var slug = comp.name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
            div.innerHTML =
              '<input class="form-check-input component-checkbox" type="checkbox" ' +
              'name="technical_component_ids[]" value="' + comp.id + '" ' +
              'id="comp-' + slug + '">' +
              '<label class="form-check-label" for="comp-' + slug + '">' + comp.name + '</label>';
            grid.appendChild(div);
          });
          technicalComponentsLoaded = true;
          if (callback) callback();
        })
        .catch(function () {
          document.getElementById('componentsGrid').innerHTML =
            '<div class="text-danger small">Gagal memuat komponen.</div>';
        });
    }

    function checkApplicationsExist() {
      fetch('/api/applications/search?q=')
        .then(function (res) { return res.json(); })
        .then(function (apps) {
          var noMsg = document.getElementById('noApplicationsMsg');
          var container = document.getElementById('appSearchContainer');
          if (apps.length === 0) {
            noMsg.style.display = 'block';
            container.style.display = 'none';
          } else {
            noMsg.style.display = 'none';
            container.style.display = 'block';
          }
        });
    }

    function openCompleteModal(id) {
      var form = document.getElementById('completeForm');
      form.action = '/feature-requests/' + id + '/complete';
      form.reset();
      selectedAppIds = [];
      renderSelectedApps();
      document.getElementById('componentError').style.display = 'none';
      document.getElementById('appSearchResults').style.display = 'none';
      document.getElementById('completeModalLabel').textContent = 'Selesaikan Perubahan';
      document.getElementById('confirmCompleteBtn').textContent = 'Selesai';

      // Remove any _method input (in case it was set from edit mode)
      var methodInput = form.querySelector('input[name="_method"]');
      if (methodInput) methodInput.remove();

      // Load dynamic components
      technicalComponentsLoaded = false;
      document.getElementById('componentsGrid').innerHTML = '<div class="text-muted small text-center py-2">Memuat komponen...</div>';
      loadTechnicalComponents();

      // Check applications exist
      checkApplicationsExist();

      if (!completeModal) {
        completeModal = new bootstrap.Modal(document.getElementById('completeModal'));
      }
      completeModal.show();
    }

    function openEditCompleteModal(id) {
      var form = document.getElementById('completeForm');
      form.action = '/feature-requests/' + id + '/update-completed';

      // Set PUT method
      var methodInput = form.querySelector('input[name="_method"]');
      if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        form.appendChild(methodInput);
      }
      methodInput.value = 'PUT';

      document.getElementById('completeModalLabel').textContent = 'Edit Data Penyelesaian';
      document.getElementById('confirmCompleteBtn').textContent = 'Simpan Perubahan';

      selectedAppIds = [];
      renderSelectedApps();
      document.getElementById('componentError').style.display = 'none';
      document.getElementById('appSearchResults').style.display = 'none';

      // Load existing completed data
      fetch('/api/feature-requests/' + id + '/completed-data')
        .then(function (res) { return res.json(); })
        .then(function (data) {
          document.getElementById('complete-lesson').value = data.lesson_learned || '';

          // Load and check technical components
          technicalComponentsLoaded = false;
          document.getElementById('componentsGrid').innerHTML = '<div class="text-muted small text-center py-2">Memuat komponen...</div>';
          loadTechnicalComponents(function () {
            // Check the existing component IDs
            var compIds = data.technical_component_ids || [];
            compIds.forEach(function (cid) {
              var cb = document.querySelector('#componentsGrid input[value="' + cid + '"]');
              if (cb) cb.checked = true;
            });
          });

          // Load existing affected applications
          selectedAppIds = [];
          if (data.affected_application_names) {
            Object.keys(data.affected_application_names).forEach(function (id) {
              selectedAppIds.push({
                id: parseInt(id),
                name: data.affected_application_names[id]
              });
            });
            renderSelectedApps();
          }

          checkApplicationsExist();
        });

      if (!completeModal) {
        completeModal = new bootstrap.Modal(document.getElementById('completeModal'));
      }
      completeModal.show();
    }

    // Searchable multi-select for applications (improved)
    document.addEventListener('DOMContentLoaded', function () {
      var searchInput = document.getElementById('appSearchInput');
      var searchResults = document.getElementById('appSearchResults');
      var searchTimeout = null;

      searchInput.addEventListener('input', function () {
        var query = this.value.trim();
        clearTimeout(searchTimeout);
        if (query.length < 2) {
          searchResults.style.display = 'none';
          return;
        }
        searchTimeout = setTimeout(function () {
          fetch('/api/applications/search?q=' + encodeURIComponent(query))
            .then(function (response) { return response.json(); })
            .then(function (apps) {
              // Filter out already selected and sort alphabetically
              var filtered = apps
                .filter(function (app) {
                  return !selectedAppIds.some(function (a) {
                    return (typeof a === 'object' ? a.id : a) === app.id;
                  });
                })
                .sort(function (a, b) { return a.name.localeCompare(b.name); });

              if (filtered.length === 0) {
                searchResults.innerHTML = '<div class="dropdown-item text-muted">Tidak ditemukan</div>';
              } else {
                searchResults.innerHTML = filtered.map(function (app) {
                  return '<div class="dropdown-item" data-id="' + app.id + '" data-name="' + app.name.replace(/"/g, '"') + '">' + app.name + '</div>';
                }).join('');
              }
              searchResults.style.display = 'block';
            })
            .catch(function () {
              searchResults.style.display = 'none';
            });
        }, 300);
      });

      searchResults.addEventListener('click', function (e) {
        var item = e.target.closest('.dropdown-item');
        if (!item || !item.dataset.id) return;
        var id = parseInt(item.dataset.id);
        var name = item.dataset.name;
        // Prevent duplicates
        var alreadySelected = selectedAppIds.some(function (a) {
          return (typeof a === 'object' ? a.id : a) === id;
        });
        if (!alreadySelected) {
          selectedAppIds.push({ id: id, name: name });
          selectedAppIds.sort(function (a, b) { return a.name.localeCompare(b.name); });
          renderSelectedApps();
        }
        // Clear search box after selection
        searchInput.value = '';
        searchResults.style.display = 'none';
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', function (e) {
        if (!e.target.closest('#appSearchInput') && !e.target.closest('#appSearchResults')) {
          searchResults.style.display = 'none';
        }
      });
    });

    function renderSelectedApps() {
      var container = document.getElementById('selectedApps');
      container.innerHTML = '';
      selectedAppIds.forEach(function (app, index) {
        var badge = document.createElement('span');
        badge.className = 'badge bg-primary';
        var nameSpan = document.createElement('span');
        nameSpan.textContent = app.name;
        badge.appendChild(nameSpan);
        var closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'btn-close btn-close-white';
        closeBtn.setAttribute('aria-label', 'Remove');
        closeBtn.addEventListener('click', function () {
          selectedAppIds.splice(index, 1);
          renderSelectedApps();
        });
        badge.appendChild(closeBtn);
        container.appendChild(badge);
      });
    }

    document.addEventListener('DOMContentLoaded', function () {
      var completeForm = document.getElementById('completeForm');
      var completeBtn = document.getElementById('confirmCompleteBtn');
      var completeCancelBtn = completeForm.querySelector('[data-bs-dismiss="modal"]');
      var lessonField = document.getElementById('complete-lesson');
      var componentError = document.getElementById('componentError');

      // Override the click to store app names when fetching
      var origFetch = window.fetch;
      var searchInput = document.getElementById('appSearchInput');
      var searchResults = document.getElementById('appSearchResults');

      completeBtn.addEventListener('click', function (e) {
        e.preventDefault();

        // Validate at least one component selected
        var checkedComponents = completeForm.querySelectorAll('.component-checkbox:checked');
        if (checkedComponents.length === 0) {
          componentError.style.display = 'block';
          return;
        }
        componentError.style.display = 'none';

        // Validate lesson_learned
        if (!lessonField.value.trim()) {
          lessonField.setCustomValidity('Lesson Learned wajib diisi.');
          completeForm.reportValidity();
          lessonField.setCustomValidity('');
          return;
        }

        // Prevent double submission
        completeBtn.disabled = true;
        completeBtn.textContent = 'Menyimpan...';
        if (completeCancelBtn) completeCancelBtn.disabled = true;

        var formData = new FormData(completeForm);

        // Append affected applications
        selectedAppIds.forEach(function (app) {
          formData.append('affected_application_ids[]', app.id);
        });

        fetch(completeForm.action, {
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
            alert(err.message || 'Terjadi kesalahan jaringan. Silakan coba lagi.');
            resetCompleteBtn();
          });
      });

      function resetCompleteBtn() {
        completeBtn.disabled = false;
        completeBtn.textContent = 'Selesai';
        if (completeCancelBtn) completeCancelBtn.disabled = false;
      }

      var completeModalEl = document.getElementById('completeModal');
      completeModalEl.addEventListener('hidden.bs.modal', function () {
        completeForm.reset();
        selectedAppIds = [];
        renderSelectedApps();
        componentError.style.display = 'none';
        resetCompleteBtn();
        // Reset modal title and button text
        document.getElementById('completeModalLabel').textContent = 'Selesaikan Perubahan';
        document.getElementById('confirmCompleteBtn').textContent = 'Selesai';
        // Remove _method input if present
        var methodInput = completeForm.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
      });
    });
  </script>
@endsection

