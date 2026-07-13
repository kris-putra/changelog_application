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
                        <form action="{{ route('feature-requests.start', $r) }}" method="post" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-link p-0 text-warning border-0"
                                  data-bs-toggle="tooltip" data-bs-placement="top" title="Start">
                            <i class="bi bi-play-circle"></i>
                          </button>
                        </form>
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
                        <form action="{{ route('feature-requests.complete', $r) }}" method="post" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-link p-0 text-success border-0"
                                  data-bs-toggle="tooltip" data-bs-placement="top" title="Completed">
                            <i class="bi bi-check-circle"></i>
                          </button>
                        </form>
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
  </script>
@endsection

