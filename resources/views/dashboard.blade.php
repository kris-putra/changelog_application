@extends('layouts.app')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3 mb-1">Dashboard</h1>
      <p class="text-muted mb-0">Ringkasan seluruh permintaan perubahan aplikasi.</p>
    </div>
  </div>

  {{-- Summary Cards --}}
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <h6 class="text-muted">Total Request</h6>
          <h2 class="mb-0">{{ $totalRequests }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <h6 class="text-muted">Open</h6>
          <h2 class="mb-0 text-primary">{{ $openCount }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <h6 class="text-muted">In Progress</h6>
          <h2 class="mb-0 text-warning">{{ $inProgressCount }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <h6 class="text-muted">Completed</h6>
          <h2 class="mb-0 text-success">{{ $completedCount }}</h2>
        </div>
      </div>
    </div>
  </div>

  {{-- Feature Request Table --}}
  <div class="card shadow-sm">
    <div class="card-body">
      @if($requests->isEmpty())
        <div class="alert alert-info mb-0">Belum ada Feature Request.</div>
      @else
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Request Number</th>
                <th>Aplikasi</th>
                <th>Nama Perubahan</th>
                <th>Prioritas</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($requests as $r)
                <tr>
                  <td><code>{{ $r->request_number }}</code></td>
                  <td>{{ $r->application?->name ?? '-' }}</td>
                  <td>{{ $r->title }}</td>
                  <td><span class="badge bg-secondary">{{ ucfirst($r->priority) }}</span></td>
                  <td><span class="badge bg-info text-dark">{{ ucfirst($r->type) }}</span></td>
                  <td>
                    @if($r->status === 'Open')
                      <span class="badge bg-primary">Open</span>
                    @elseif($r->status === 'In Progress')
                      <span class="badge bg-warning text-dark">In Progress</span>
                    @elseif($r->status === 'Completed')
                      <span class="badge bg-success">Completed</span>
                    @endif
                  </td>
                  <td>{{ $r->created_at->format('d M Y') }}</td>
                  <td>
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                      {{-- View (selalu ada) --}}
                      <a href="{{ route('feature-requests.show', $r) }}"
                         class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                        <i class="bi bi-eye fs-5"></i>
                      </a>

                      @if($r->status === 'Open')
                        {{-- Edit --}}
                        <a href="{{ route('feature-requests.edit', $r) }}"
                           class="text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                          <i class="bi bi-pencil-square fs-5"></i>
                        </a>
                        {{-- Start --}}
                        <form action="{{ route('feature-requests.start', $r) }}" method="post" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-link p-0 text-warning border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Start">
                            <i class="bi bi-play-circle fs-5"></i>
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
                            <i class="bi bi-trash fs-5"></i>
                          </button>
                        </form>
                      @elseif($r->status === 'In Progress')
                        {{-- Cancel --}}
                        <form action="{{ route('feature-requests.cancel', $r) }}" method="post" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-link p-0 text-secondary border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
                            <i class="bi bi-arrow-counterclockwise fs-5"></i>
                          </button>
                        </form>
                        {{-- Completed --}}
                        <form action="{{ route('feature-requests.complete', $r) }}" method="post" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-link p-0 text-success border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Completed">
                            <i class="bi bi-check-circle fs-5"></i>
                          </button>
                        </form>
                      @endif
                      {{-- Completed: hanya View --}}
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
  </script>
