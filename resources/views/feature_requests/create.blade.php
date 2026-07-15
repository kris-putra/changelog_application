@extends('layouts.app')
@section('content')
  <style>
    :root {
      --cream: #f5efdf;
      --ink: #201d18;
      --muted: #7a746a;
      --border: #e4dccb;
    }
    .page-content {
      position: relative;
      min-height: calc(100vh - 116px);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 0;
      overflow: hidden;
    }
    .page-content .decor {
      position: absolute;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      background-image: radial-gradient(rgba(32,29,24,0.10) 1.5px, transparent 1.5px);
      background-size: 28px 28px;
    }
    .page-content .code-snippet {
      position: absolute;
      font-family: ui-monospace, "SF Mono", Menlo, monospace;
      font-size: 12px;
      line-height: 1.6;
      color: rgba(32,29,24,0.20);
      white-space: pre;
    }
    .code-left { left: 6%; top: 42%; }
    .code-right { right: 7%; bottom: 38%; }
    @media (max-width: 1023px) { .code-snippet { display: none; } }
    .page-content .card {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 860px;
      background: rgba(251,248,240,0.85);
      backdrop-filter: blur(6px);
      border: 1px solid var(--border);
      border-radius: 24px;
      padding: 28px 32px;
      box-shadow: 0 20px 40px rgba(32,29,24,0.06);
    }
    .page-content form { margin-top: 0; }
    .page-content .form-group { margin-bottom: 14px; }
    .page-content .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 6px;
    }
    .page-content input,
    .page-content select,
    .page-content textarea {
      width: 100%;
      padding: 10px 14px;
      font-size: 14px;
      color: var(--ink);
      background: rgba(255,255,255,0.6);
      border: 1px solid var(--border);
      border-radius: 12px;
      outline: none;
      transition: border-color .15s, box-shadow .15s;
    }
    .page-content textarea { min-height: 72px; resize: vertical; }
    .page-content input:focus, .page-content select:focus, .page-content textarea:focus { border-color: var(--ink); box-shadow: 0 0 0 3px rgba(32,29,24,0.12); }
    .page-content input::placeholder, .page-content textarea::placeholder { color: rgba(122,116,106,0.7); }
    .page-content .btn-row {
      display: flex;
      gap: 12px;
      justify-content: flex-end;
      margin-top: 20px;
    }
    .page-content .btn-row button,
    .page-content .btn-row a {
      padding: 12px 24px;
      font-size: 14px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      border-radius: 12px;
      cursor: pointer;
      text-decoration: none;
      text-align: center;
    }
    .page-content .btn-primary {
      color: var(--cream);
      background: var(--ink);
      border: none;
      transition: background-color .15s;
    }
    .page-content .btn-primary:hover { background: #35302a; }
    .page-content .btn-primary:disabled { background: #a8a18f; cursor: not-allowed; }
    .page-content .btn-secondary {
      color: var(--ink);
      background: transparent;
      border: 1px solid var(--border);
      transition: background-color .15s;
    }
    .page-content .btn-secondary:hover { background: rgba(32,29,24,0.05); }
    .page-content .error { color: #b91c1c; font-size: 13px; margin-top: 4px; }
  </style>

  <div class="page-content">
    <div class="decor" aria-hidden="true"></div>
    <pre class="code-snippet code-left" aria-hidden="true">{
  "version": "1.0.0",
  "status": "stable",
  "commits": 128
}</pre>
    <pre class="code-snippet code-right" aria-hidden="true">+ feat: auth
- fix: cache
~ refactor: ui</pre>

    <section class="card">
      <form action="{{ route('feature-requests.store') }}" method="post" id="feature-form" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
          <div class="alert alert-danger" style="border-radius:12px; font-size:13px;">
            <strong>Terdapat kesalahan pada input:</strong>
            <ul class="mb-0 mt-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="row g-3">
          {{-- Baris 1: Aplikasi (full width) --}}
          <div class="col-12">
            <div class="form-group">
              <label for="application_id">Aplikasi</label>
              <select id="application_id" name="application_id" class="w-100" required>
                <option value="" disabled {{ old('application_id') ? '' : 'selected' }}>Pilih Aplikasi</option>
                @foreach($applications as $application)
                  <option value="{{ $application->id }}" {{ (string) old('application_id') === (string) $application->id ? 'selected' : '' }}>
                    {{ $application->name }}
                  </option>
                @endforeach
              </select>
              @error('application_id')
                <div class="error">{{ $message }}</div>
              @enderror
              @if($applications->isEmpty())
                <div class="error">Belum ada data aplikasi. Tambahkan aplikasi terlebih dahulu di halaman Add Application.</div>
              @endif
            </div>
          </div>

          {{-- Baris 2: Nama Perubahan | Pemohon Perubahan (6+6) --}}
          <div class="col-md-6">
            <div class="form-group">
              <label for="title">Nama Perubahan</label>
              <input id="title" name="title" type="text" class="w-100" placeholder="Nama Perubahan" value="{{ old('title') }}" required />
              @error('title')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="pemohon_perubahan">Pemohon Perubahan</label>
              <input id="pemohon_perubahan" name="pemohon_perubahan" type="text" class="w-100" placeholder="Pemohon Perubahan" value="{{ old('pemohon_perubahan') }}" />
              @error('pemohon_perubahan')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Baris 3: Prioritas --}}
          <div class="col-md-6">
            <div class="form-group">
              <label for="priority">Prioritas</label>
              <select id="priority" name="priority" class="w-100" required>
                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ old('priority') == 'medium' || !old('priority') ? 'selected' : '' }}>Medium</option>
                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
              </select>
            </div>
          </div>

          {{-- Baris 4: Tipe --}}
          <div class="col-md-6">
            <div class="form-group">
              <label for="type">Tipe</label>
              <select id="type" name="type" class="w-100" required>
                <option value="feature" {{ old('type') == 'feature' || !old('type') ? 'selected' : '' }}>Feature</option>
                <option value="change" {{ old('type') == 'change' ? 'selected' : '' }}>Change</option>
                <option value="bug" {{ old('type') == 'bug' ? 'selected' : '' }}>Bug</option>
                <option value="incident" {{ old('type') == 'incident' ? 'selected' : '' }}>Incident</option>
              </select>
            </div>
          </div>
          <div class="col-md-6"></div>

          {{-- Baris 5: Deskripsi (full width) --}}
          <div class="col-12">
            <div class="form-group">
              <label for="description">Deskripsi</label>
              <textarea id="description" name="description" class="w-100" placeholder="Deskripsi Permintaan" required>{{ old('description') }}</textarea>
              @error('description')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Baris 6: As-Is | To-Be (6+6) --}}
          <div class="col-md-6">
            <div class="form-group">
              <label for="as_is">As-Is</label>
              <textarea id="as_is" name="as_is" class="w-100" placeholder="Jelaskan kondisi saat ini sebelum perubahan.">{{ old('as_is') }}</textarea>
              @error('as_is')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="to_be">To-Be</label>
              <textarea id="to_be" name="to_be" class="w-100" placeholder="Jelaskan kondisi yang diharapkan setelah perubahan diterapkan.">{{ old('to_be') }}</textarea>
              @error('to_be')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Baris 7: Dampak Perubahan (full width) --}}
          <div class="col-12">
            <div class="form-group">
              <label for="impact">Dampak Perubahan</label>
              <textarea id="impact" name="impact" class="w-100" placeholder="Jelaskan dampak perubahan terhadap layanan, pengguna, atau sistem selama implementasi. Jika tidak ada dampak, tuliskan "Tidak ada dampak".">{{ old('impact') }}</textarea>
              @error('impact')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Baris 8: Lampiran (full width) --}}
          <div class="col-12">
            <div class="form-group">
              <label for="attachment">Lampiran</label>
              <input id="attachment" name="attachment" type="file" class="w-100" accept=".pdf,.jpg,.jpeg,.png" />
              <small style="color: var(--muted); font-size: 12px;">Format: PDF, JPG, JPEG, PNG. Maksimal 3 MB.</small>
              @error('attachment')
                <div class="error">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>


        {{-- Tombol --}}
        <div class="btn-row">
          <a href="{{ route('feature-requests.index') }}" class="btn-secondary">Batal</a>
          <button type="submit" id="submit-button" class="btn-primary">Simpan</button>
        </div>
      </form>
    </section>
  </div>
@endsection