@extends('layouts.app')
@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Dashboard</h1>
    <a href="{{ route('feature-requests.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg me-1"></i>Tambah Permintaan
    </a>
  </div>

  {{-- ======================================== --}}
  {{-- Filter Bar                              --}}
  {{-- ======================================== --}}
  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body px-4 py-3">
      <div class="row g-2 align-items-end">
        <div class="col-md-3">
          <label class="form-label small fw-semibold text-muted mb-1">Status</label>
          <select class="form-select form-select-sm" id="filterStatus">
            <option value="">Semua Status</option>
            <option value="Open">Open</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold text-muted mb-1">Prioritas</label>
          <select class="form-select form-select-sm" id="filterPriority">
            <option value="">Semua Prioritas</option>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold text-muted mb-1">Tipe</label>
          <select class="form-select form-select-sm" id="filterType">
            <option value="">Semua Tipe</option>
            <option value="Feature">Feature</option>
            <option value="Incident">Incident</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold text-muted mb-1">Pencarian</label>
          <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Cari judul atau request number...">
        </div>
      </div>
    </div>
  </div>

  {{-- ======================================== --}}
  {{-- Requests Table                          --}}
  {{-- ======================================== --}}
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4" style="width: 140px;">Request Number</th>
              <th>Judul</th>
              <th style="width: 100px;">Prioritas</th>
              <th style="width: 100px;">Tipe</th>
              <th style="width: 120px;">Status</th>
              <th style="width: 160px;">Tanggal</th>
              <th class="text-center" style="width: 180px;">Aksi</th>
            </tr>
          </thead>
          <tbody id="requestsTableBody">
            @forelse($requests as $request)
              <tr class="request-row"
                  data-status="{{ $request->status }}"
                  data-priority="{{ $request->priority }}"
                  data-type="{{ $request->type }}"
                  data-search="{{ strtolower($request->request_number . ' ' . $request->title) }}">
                <td class="ps-4">
                  <code class="small">{{ $request->request_number }}</code>
                </td>
                <td>
                  <a href="{{ route('feature-requests.show', $request) }}" class="text-decoration-none fw-semibold text-dark">
                    {{ $request->title }}
                  </a>
                </td>
                <td>
                  <span class="badge bg-secondary">{{ ucfirst($request->priority) }}</span>
                </td>
                <td>
                  <span class="badge bg-info text-dark">{{ ucfirst($request->type) }}</span>
                </td>
                <td>
                  @if($request->status === 'Open')
                    <span class="badge bg-primary">Open</span>
                  @elseif($request->status === 'In Progress')
                    <span class="badge bg-warning text-dark">In Progress</span>
                  @elseif($request->status === 'Completed')
                    <span class="badge bg-success">Completed</span>
                  @else
                    <span class="badge bg-secondary">{{ $request->status }}</span>
                  @endif
                </td>
                <td class="small text-muted">
                  {{ $request->created_at->format('d M Y H:i') }}
                </td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm">
                    <a href="{{ route('feature-requests.show', $request) }}" class="btn btn-outline-secondary" title="Detail">
                      <i class="bi bi-eye"></i>
                    </a>
                    @if($request->status === 'Open')
                      <button type="button" class="btn btn-outline-warning" title="Mulai"
                              onclick="startRequest({{ $request->id }})">
                        <i class="bi bi-play-fill"></i>
                      </button>
                    @endif
                    @if($request->status === 'In Progress')
                      <button type="button" class="btn btn-outline-success" title="Selesaikan"
                              onclick="CompleteModal.open({{ $request->id }})">
                        <i class="bi bi-check-lg"></i>
                      </button>
                    @endif
                    @if($request->status === 'Completed')
                      <button type="button" class="btn btn-outline-primary" title="Edit Penyelesaian"
                              onclick="CompleteModal.openEdit({{ $request->id }})">
                        <i class="bi bi-pencil"></i>
                      </button>
                    @endif
                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                            onclick="deleteRequest({{ $request->id }})">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-5">
                  <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                  Belum ada permintaan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Empty state when filters hide all rows --}}
  <div id="noResultsMsg" class="text-center text-muted py-5" style="display: none;">
    <i class="bi bi-search fs-1 d-block mb-2"></i>
    Tidak ada permintaan yang sesuai filter.
  </div>

  {{-- ======================================== --}}
  {{-- Start Confirmation Modal                --}}
  {{-- ======================================== --}}
  <div class="modal fade" id="startModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <form id="startForm" method="POST" action="">
          @csrf
          <input type="hidden" name="_method" value="PATCH">
          <div class="modal-header">
            <h5 class="modal-title">Mulai Pengerjaan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="start-pic" class="form-label fw-semibold">PIC <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="start-pic" name="pic" placeholder="Nama PIC" required>
            </div>
            <div class="mb-0">
              <label for="start-rollback" class="form-label fw-semibold">Rollback Plan <span class="text-danger">*</span></label>
              <textarea class="form-control" id="start-rollback" name="rollback_plan" rows="3" placeholder="Rencana rollback jika terjadi masalah." required></textarea>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning px-4">Mulai</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- ======================================== --}}
  {{-- Delete Confirmation Modal               --}}
  {{-- ======================================== --}}
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <form id="deleteForm" method="POST" action="">
          @csrf
          @method('DELETE')
          <div class="modal-header">
            <h5 class="modal-title">Hapus Permintaan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="mb-0">Apakah Anda yakin ingin menghapus permintaan ini? Tindakan ini tidak dapat dibatalkan.</p>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger px-4">Hapus</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- ======================================== --}}
  {{-- Complete / Edit Complete Modal           --}}
  {{-- (single modal, powered by shared JS)    --}}
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
            <h5 class="modal-title" id="completeModalLabel">Selesaikan Permintaan</h5>
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

      var startModal = null;
      var deleteModal = null;

      /* ========== Start Request ========== */
      window.startRequest = function (id) {
        var form = document.getElementById('startForm');
        form.action = '/feature-requests/' + id + '/start';
        document.getElementById('start-pic').value = '';
        document.getElementById('start-rollback').value = '';
        if (!startModal) {
          startModal = new bootstrap.Modal(document.getElementById('startModal'));
        }
        startModal.show();
      };

      document.getElementById('startForm').addEventListener('submit', function (e) {
        e.preventDefault();
        var form = this;
        fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(function (response) {
          if (response.ok) return response.json();
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
            startModal.hide();
            window.location.reload();
          } else {
            alert(data.message || 'Terjadi kesalahan.');
          }
        })
        .catch(function (err) {
          alert(err.message || 'Terjadi kesalahan jaringan.');
        });
      });

      /* ========== Delete Request ========== */
      window.deleteRequest = function (id) {
        var form = document.getElementById('deleteForm');
        form.action = '/feature-requests/' + id;
        if (!deleteModal) {
          deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        }
        deleteModal.show();
      };

      document.getElementById('deleteForm').addEventListener('submit', function (e) {
        e.preventDefault();
        var form = this;
        fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(function (response) {
          if (response.ok) return response.json();
          throw new Error('Gagal menghapus permintaan.');
        })
        .then(function (data) {
          if (data.success) {
            sessionStorage.setItem('toast_data', JSON.stringify({
              type: 'success',
              title: 'Success',
              message: data.message,
            }));
            deleteModal.hide();
            window.location.reload();
          } else {
            alert(data.message || 'Terjadi kesalahan.');
          }
        })
        .catch(function (err) {
          alert(err.message || 'Terjadi kesalahan jaringan.');
        });
      });

      /* ========== Filters ========== */
      var filterStatus = document.getElementById('filterStatus');
      var filterPriority = document.getElementById('filterPriority');
      var filterType = document.getElementById('filterType');
      var filterSearch = document.getElementById('filterSearch');
      var noResultsMsg = document.getElementById('noResultsMsg');

      function applyFilters() {
        var status = filterStatus.value.toLowerCase();
        var priority = filterPriority.value.toLowerCase();
        var type = filterType.value.toLowerCase();
        var search = filterSearch.value.trim().toLowerCase();
        var rows = document.querySelectorAll('.request-row');
        var visibleCount = 0;

        rows.forEach(function (row) {
          var rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
          var rowPriority = (row.getAttribute('data-priority') || '').toLowerCase();
          var rowType = (row.getAttribute('data-type') || '').toLowerCase();
          var rowSearch = row.getAttribute('data-search') || '';

          var matchStatus = !status || rowStatus === status;
          var matchPriority = !priority || rowPriority === priority;
          var matchType = !type || rowType === type;
          var matchSearch = !search || rowSearch.indexOf(search) !== -1;

          if (matchStatus && matchPriority && matchType && matchSearch) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });

        noResultsMsg.style.display = visibleCount === 0 && rows.length > 0 ? 'block' : 'none';
      }

      filterStatus.addEventListener('change', applyFilters);
      filterPriority.addEventListener('change', applyFilters);
      filterType.addEventListener('change', applyFilters);
      filterSearch.addEventListener('input', applyFilters);
    });
  </script>
@endsection