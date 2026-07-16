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
      <button type="button" class="btn btn-sm btn-outline-primary" onclick="openEditCompleteModal({{ $requestItem->id }})">
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

  {{-- Complete Modal --}}
  <style>
    #completeModal .modal-dialog { max-width: 900px; width: 100%; }
    #completeModal .component-scroll-box,
    #completeModal .app-scroll-box {
      height: 250px;
      overflow-y: auto;
      border: 1px solid #dee2e6;
      border-radius: 0.375rem;
      padding: 8px;
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
    #completeModal .component-error { color: #dc3545; font-size: 0.8rem; display: none; margin-top: 4px; }
    #completeModal .app-filter-input { margin-bottom: 8px; }
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
            <div class="row mb-3">
              {{-- Left column: Technical Components --}}
              <div class="col-md-6">
                <label class="form-label fw-semibold">Komponen yang Diubah <span class="text-danger">*</span></label>
                <div class="component-scroll-box" id="componentsScrollBox">
                  <div class="text-muted small text-center py-2">Memuat komponen...</div>
                </div>
                <div class="component-error" id="componentError">Pilih minimal satu komponen.</div>

                {{-- Add New Component --}}
                <div class="add-component-section">
                  <small class="fw-semibold text-muted">+ Tambah Komponen Baru</small>
                  <div class="input-group input-group-sm mt-1">
                    <input type="text" class="form-control" id="newComponentInput" placeholder="Nama komponen baru...">
                    <button class="btn btn-outline-primary" type="button" id="addComponentBtn">Tambah</button>
                  </div>
                  <div id="addComponentFeedback" class="small mt-1" style="display: none;"></div>
                </div>
              </div>

              {{-- Right column: Affected Applications --}}
              <div class="col-md-6">
                <label class="form-label fw-semibold">Aplikasi Terdampak</label>
                <div id="noApplicationsMsg" class="text-muted small mb-2" style="display: none;">Tidak ada aplikasi yang tersedia.</div>
                <div id="appSearchContainer">
                  <input type="text" class="form-control form-control-sm app-filter-input" id="appFilterInput"
                         placeholder="Cari aplikasi..." autocomplete="off">
                  <div class="app-scroll-box" id="appsScrollBox">
                    <div class="text-muted small text-center py-2">Memuat aplikasi...</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="complete-lesson" class="form-label fw-semibold">Lesson Learned <span class="text-danger">*</span></label>
              <textarea class="form-control" id="complete-lesson" name="lesson_learned" rows="4" placeholder="Tuliskan pembelajaran, kendala, atau rekomendasi setelah perubahan selesai." required></textarea>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success px-4" id="confirmCompleteBtn">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
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
          feedback.className = 'small mt-1 text-danger';
          feedback.textContent = 'Nama komponen tidak boleh kosong.';
          return;
        }

        addBtn.disabled = true;
        addBtn.textContent = '...';

        fetch('/api/technical-components', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ name: name })
        })
        .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, data: data }; }); })
        .then(function (result) {
          if (result.ok && result.data.success) {
            var comp = result.data;
            // Append to list and check it
            var box = document.getElementById('componentsScrollBox');
            var placeholder = box.querySelector('.text-muted.text-center');
            if (placeholder) placeholder.remove();

            var div = document.createElement('div');
            div.className = 'form-check';
            var slug = comp.name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
            div.innerHTML =
              '<input class="form-check-input component-checkbox" type="checkbox" name="technical_component_ids[]" value="' + comp.id + '" id="comp-' + slug + '" checked>' +
              '<label class="form-check-label" for="comp-' + slug + '">' + escapeHtml(comp.name) + '</label>';
            box.appendChild(div);

            // Sort the list alphabetically
            sortComponentsList();

            addInput.value = '';
            feedback.style.display = 'block';
            feedback.className = 'small mt-1 text-success';
            feedback.textContent = '"' + comp.name + '" berhasil ditambahkan.';
            setTimeout(function () { feedback.style.display = 'none'; }, 3000);

            // Invalidate cache so next load picks it up
            technicalComponentsLoaded = false;
          } else {
            feedback.style.display = 'block';
            feedback.className = 'small mt-1 text-danger';
            feedback.textContent = result.data.message || 'Gagal menambahkan komponen.';
          }
        })
        .catch(function () {
          feedback.style.display = 'block';
          feedback.className = 'small mt-1 text-danger';
          feedback.textContent = 'Terjadi kesalahan jaringan.';
        })
        .finally(function () {
          addBtn.disabled = false;
          addBtn.textContent = 'Tambah';
        });
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

    /* ========== Open Edit Complete Modal ========== */

    function openEditCompleteModal(id) {
      var form = document.getElementById('completeForm');
      form.action = '/feature-requests/' + id + '/update-completed';

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

        completeBtn.disabled = true;
        completeBtn.textContent = 'Menyimpan...';

        var formData = new FormData(completeForm);

        // Collect checked app ids
        var checkedApps = completeForm.querySelectorAll('.app-checkbox:checked');
        checkedApps.forEach(function (cb) {
          formData.append('affected_application_ids[]', cb.value);
        });

        fetch(completeForm.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            completeModal.hide();
            window.location.reload();
          } else {
            alert(data.message || 'Terjadi kesalahan.');
            resetBtn();
          }
        })
        .catch(function (err) {
          alert(err.message || 'Terjadi kesalahan jaringan.');
          resetBtn();
        });

        function resetBtn() {
          completeBtn.disabled = false;
          completeBtn.textContent = 'Simpan Perubahan';
        }
      });

      document.getElementById('completeModal').addEventListener('hidden.bs.modal', function () {
        completeForm.reset();
        document.getElementById('componentError').style.display = 'none';
        document.getElementById('addComponentFeedback').style.display = 'none';
        document.getElementById('newComponentInput').value = '';
        document.getElementById('appFilterInput').value = '';
        completeBtn.disabled = false;
        completeBtn.textContent = 'Simpan Perubahan';
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