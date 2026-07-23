/**
 * Complete Modal — Shared implementation
 *
 * Used by:
 *   - Dashboard (Complete + Edit Complete)
 *   - Show Page  (Edit Complete)
 *
 * Exposes a global `CompleteModal` object.  Call `CompleteModal.init()` once on
 * DOMContentLoaded, then use `CompleteModal.open(id)` or
 * `CompleteModal.openEdit(id)` to launch the modal.
 */
(function () {
  'use strict';

  /* ===== State ===== */
  var completeModal       = null;
  var allApplications     = [];
  var technicalComponentsLoaded = false;
  var featureRequestId    = null;

  /* ===== DOM helpers (resolved lazily so the HTML can live anywhere) ===== */
  function el(id) { return document.getElementById(id); }

  /* ===== Technical Components ===== */

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
        el('componentsScrollBox').innerHTML = '<div class="text-danger small">Gagal memuat komponen.</div>';
      });
  }

  function renderComponentsList(components) {
    var box = el('componentsScrollBox');
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

  /* ===== Add New Component ===== */

  function addNewComponent() {
    var addBtn   = el('addComponentBtn');
    var addInput = el('newComponentInput');
    var feedback = el('addComponentFeedback');

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
      if (metaTag) { csrfToken = metaTag.getAttribute('content'); }
    } catch (e) { /* meta tag not found */ }
    if (!csrfToken) {
      var tokenInput = document.querySelector('input[name="_token"]');
      if (tokenInput) { csrfToken = tokenInput.value; }
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
          var box = el('componentsScrollBox');
          var placeholder = box.querySelector('.text-muted.text-center');
          if (placeholder) placeholder.remove();

          if (comp.duplicate) {
            // Duplicate: find the existing checkbox and check it
            var existingCb = box.querySelector('input[value="' + comp.id + '"]');
            if (existingCb) {
              existingCb.checked = true;
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
    var box = el('componentsScrollBox');
    var checks = Array.from(box.querySelectorAll('.form-check'));
    checks.sort(function (a, b) {
      var nameA = a.querySelector('label').textContent.toLowerCase();
      var nameB = b.querySelector('label').textContent.toLowerCase();
      return nameA.localeCompare(nameB);
    });
    checks.forEach(function (el) { box.appendChild(el); });
  }

  /* ===== Affected Applications ===== */

  function loadAllApplications(callback) {
    fetch('/api/applications/search?q=')
      .then(function (res) { return res.json(); })
      .then(function (apps) {
        allApplications = apps.sort(function (a, b) { return a.name.localeCompare(b.name); });
        renderApplicationsList(allApplications);
        var noMsg = el('noApplicationsMsg');
        var container = el('appSearchContainer');
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
        el('appsScrollBox').innerHTML = '<div class="text-danger small">Gagal memuat aplikasi.</div>';
      });
  }

  function renderApplicationsList(apps) {
    var box = el('appsScrollBox');
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

  /* ===== Open Complete Modal (new completion) ===== */

  function openCompleteModal(id) {
    if (!isValidId(id)) return;
    featureRequestId = Number(id);

    var form = el('completeForm');
    form.action = '/feature-requests/' + featureRequestId + '/complete';
    form.reset();
    el('componentError').style.display = 'none';
    el('appFilterInput').value = '';

    // Set modal title
    var titleEl = el('completeModalLabel');
    if (titleEl) titleEl.textContent = 'Selesaikan Permintaan';

    // Set button text
    var completeBtn = el('confirmCompleteBtn');
    if (completeBtn) completeBtn.textContent = 'Selesai';

    // Remove any _method input
    var methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();

    // Reset add component feedback
    resetAddComponentFeedback();

    // Load components
    technicalComponentsLoaded = false;
    el('componentsScrollBox').innerHTML = '<div class="text-muted small text-center py-2">Memuat komponen...</div>';
    loadTechnicalComponents();

    // Load applications
    el('appsScrollBox').innerHTML = '<div class="text-muted small text-center py-2">Memuat aplikasi...</div>';
    loadAllApplications();

    showModal();
  }

  /* ===== Open Edit Complete Modal ===== */

  function openEditCompleteModal(id) {
    if (!isValidId(id)) return;
    featureRequestId = Number(id);

    var form = el('completeForm');
    form.action = '/feature-requests/' + featureRequestId + '/update-completed';

    // Set modal title
    var titleEl = el('completeModalLabel');
    if (titleEl) titleEl.textContent = 'Edit Data Penyelesaian';

    // Set button text
    var completeBtn = el('confirmCompleteBtn');
    if (completeBtn) completeBtn.textContent = 'Selesai';

    var methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
      methodInput = document.createElement('input');
      methodInput.type = 'hidden';
      methodInput.name = '_method';
      form.appendChild(methodInput);
    }
    methodInput.value = 'PUT';

    el('componentError').style.display = 'none';
    el('appFilterInput').value = '';
    el('complete-lesson').value = '';

    // Reset add component feedback
    resetAddComponentFeedback();

    fetch('/api/feature-requests/' + id + '/completed-data')
      .then(function (res) { return res.json(); })
      .then(function (data) {
        el('complete-lesson').value = data.lesson_learned || '';

        // Load components
        technicalComponentsLoaded = false;
        el('componentsScrollBox').innerHTML = '<div class="text-muted small text-center py-2">Memuat komponen...</div>';
        loadTechnicalComponents(function () {
          var compIds = data.technical_component_ids || [];
          compIds.forEach(function (cid) {
            var cb = document.querySelector('#componentsScrollBox input[value="' + cid + '"]');
            if (cb) cb.checked = true;
          });
        });

        // Load applications
        el('appsScrollBox').innerHTML = '<div class="text-muted small text-center py-2">Memuat aplikasi...</div>';
        loadAllApplications(function () {
          var appIds = data.affected_application_ids || [];
          appIds.forEach(function (aid) {
            var cb = document.querySelector('#appsScrollBox input[value="' + aid + '"]');
            if (cb) cb.checked = true;
          });
        });
      });

    showModal();
  }

  /* ===== Form Submission ===== */

  function handleSubmit(e) {
    e.preventDefault();

    var completeForm = el('completeForm');
    var completeBtn  = el('confirmCompleteBtn');
    var cancelBtn    = completeForm.querySelector('[data-bs-dismiss="modal"]');

    var checkedComponents = completeForm.querySelectorAll('.component-checkbox:checked');
    if (checkedComponents.length === 0) {
      el('componentError').style.display = 'block';
      return;
    }
    el('componentError').style.display = 'none';

    var lessonField = el('complete-lesson');
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
    if (cancelBtn) cancelBtn.disabled = true;

    var formData = new FormData(completeForm);

    // Collect checked app ids
    var checkedApps = completeForm.querySelectorAll('.app-checkbox:checked');
    checkedApps.forEach(function (cb) {
      formData.append('affected_application_ids[]', cb.value);
    });

    // Resilient CSRF token
    var csrfToken = '';
    try {
      var metaTag = document.querySelector('meta[name="csrf-token"]');
      if (metaTag) { csrfToken = metaTag.getAttribute('content'); }
    } catch (ex) { /* meta tag not found */ }
    if (!csrfToken) { csrfToken = formData.get('_token') || ''; }
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
      if (response.ok) { return response.json(); }
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
      if (cancelBtn) cancelBtn.disabled = false;
    }
  }

  /* ===== Modal lifecycle helpers ===== */

  function showModal() {
    if (!completeModal) {
      completeModal = new bootstrap.Modal(el('completeModal'));
    }
    completeModal.show();
  }

  function resetAddComponentFeedback() {
    var feedback = el('addComponentFeedback');
    feedback.style.display = 'none';
    el('newComponentInput').value = '';
  }

  function isValidId(id) {
    if (id === null || id === undefined || !Number.isFinite(Number(id)) || Number(id) < 1 || !Number.isInteger(Number(id))) {
      alert('ID permintaan tidak valid. Silakan muat ulang halaman.');
      return false;
    }
    return true;
  }

  /* ===== App filter ===== */

  function setupAppFilter() {
    var filterInput = el('appFilterInput');
    if (!filterInput) return;
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
  }

  /* ===== Add Component button wiring ===== */

  function setupAddComponent() {
    var addBtn   = el('addComponentBtn');
    var addInput = el('newComponentInput');
    if (!addBtn || !addInput) return;

    addBtn.addEventListener('click', function () { addNewComponent(); });
    addInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); addNewComponent(); }
    });
  }

  /* ===== Modal hidden cleanup ===== */

  function setupModalCleanup() {
    var modalEl = el('completeModal');
    if (!modalEl) return;
    modalEl.addEventListener('hidden.bs.modal', function () {
      var completeForm = el('completeForm');
      var completeBtn  = el('confirmCompleteBtn');
      completeForm.reset();
      el('componentError').style.display = 'none';
      el('addComponentFeedback').style.display = 'none';
      el('newComponentInput').value = '';
      el('appFilterInput').value = '';
      completeBtn.disabled = false;
      completeBtn.textContent = 'Selesai';
      // Remove _method input if present
      var methodInput = completeForm.querySelector('input[name="_method"]');
      if (methodInput) methodInput.remove();
    });
  }

  /* ===== Toast helper (for pages that don't already handle toasts) ===== */

  function showSessionToast() {
    var pendingToast = sessionStorage.getItem('toast_data');
    if (!pendingToast) return;
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

  /* ===== Helpers ===== */

  function escapeHtml(text) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
  }

  /* ===== Public API ===== */

  window.CompleteModal = {
    init: function () {
      setupAppFilter();
      setupAddComponent();
      setupModalCleanup();

      // Form submission
      var completeForm = el('completeForm');
      if (completeForm) {
        completeForm.addEventListener('submit', handleSubmit);
      }

      // Show toast from sessionStorage after page reload
      showSessionToast();
    },
    open: openCompleteModal,
    openEdit: openEditCompleteModal
  };
})();