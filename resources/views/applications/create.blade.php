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
    .decor {
      position: absolute;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      background-image: radial-gradient(rgba(32,29,24,0.10) 1.5px, transparent 1.5px);
      background-size: 28px 28px;
    }
    .code-snippet {
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
    .card {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 540px;
      background: rgba(251,248,240,0.85);
      backdrop-filter: blur(6px);
      border: 1px solid var(--border);
      border-radius: 24px;
      padding: 40px;
      box-shadow: 0 20px 40px rgba(32,29,24,0.06);
    }
    .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
    .brand-badge {
      width: 44px; height: 44px;
      display: flex; align-items: center; justify-content: center;
      background: var(--ink); color: var(--cream);
      border-radius: 12px; font-weight: 700;
    }
    .brand-label {
      font-family: ui-monospace, monospace;
      font-size: 12px; letter-spacing: 0.28em;
      text-transform: uppercase; color: var(--muted);
    }
    h1 {
      font-size: 30px; line-height: 1.15;
      text-transform: uppercase; letter-spacing: -0.01em;
    }
    .subtitle { margin-top: 12px; font-size: 14px; line-height: 1.6; color: var(--muted); }
    form { margin-top: 32px; display: flex; flex-direction: column; gap: 20px; }
    .field { display: flex; flex-direction: column; gap: 8px; }
    label { font-size: 14px; font-weight: 500; }
    input, select, textarea {
      width: 100%;
      padding: 12px 16px;
      font-size: 14px;
      color: var(--ink);
      background: rgba(255,255,255,0.6);
      border: 1px solid var(--border);
      border-radius: 12px;
      outline: none;
      transition: border-color .15s, box-shadow .15s;
    }
    textarea { min-height: 140px; resize: vertical; }
    input:focus, select:focus, textarea:focus { border-color: var(--ink); box-shadow: 0 0 0 3px rgba(32,29,24,0.12); }
    input::placeholder, textarea::placeholder { color: rgba(122,116,106,0.7); }
    button[type="submit"] {
      margin-top: 8px;
      width: 100%;
      padding: 14px;
      font-size: 14px; font-weight: 600;
      text-transform: uppercase; letter-spacing: 0.03em;
      color: var(--cream); background: var(--ink);
      border: none; border-radius: 12px; cursor: pointer;
      transition: background-color .15s;
    }
    button[type="submit"]:hover { background: #35302a; }
    button[type="submit"]:disabled { background: #a8a18f; cursor: not-allowed; }
    .error { color: #b91c1c; font-size: 13px; }
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
      <div class="brand">
        <span class="brand-badge">C</span>
        <span class="brand-label">Changelog</span>
      </div>
      <h1>Tambah Aplikasi</h1>
      <p class="subtitle">Isi data aplikasi baru untuk ditambahkan ke sistem.</p>

      <form action="{{ route('applications.store') }}" method="POST" id="application-form">
        @csrf
        <div class="field">
          <label for="name">Nama Aplikasi</label>
          <input id="name" name="name" type="text" placeholder="Nama Aplikasi" value="{{ old('name') }}" required />
          @error('name')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <div class="field">
          <label for="description">Deskripsi Aplikasi</label>
          <textarea id="description" name="description" placeholder="Deskripsi Aplikasi" required>{{ old('description') }}</textarea>
          @error('description')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <div class="field">
          <label for="url">URL</label>
          <input id="url" name="url" type="url" placeholder="https://example.com" value="{{ old('url') }}" required />
          @error('url')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <div class="field">
          <label for="location">Lokasi Aplikasi</label>
          <select id="location" name="location" required>
            <option value="">Pilih lokasi aplikasi</option>
            <option value="Local Server" {{ old('location') == 'Local Server' ? 'selected' : '' }}>Local Server</option>
            <option value="PDNS Server" {{ old('location') == 'PDNS Server' ? 'selected' : '' }}>PDNS Server</option>
            <option value="Third Party" {{ old('location') == 'Third Party' ? 'selected' : '' }}>Third Party</option>
          </select>
          @error('location')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <button type="submit" id="submit-button" disabled>Submit</button>
      </form>
    </section>
  </div>

  <script>
    const form = document.getElementById('application-form');
    const submitButton = document.getElementById('submit-button');
    const requiredFields = ['name','description','url','location'];

    const checkForm = () => {
      const isValid = requiredFields.every((field) => {
        const value = form[field].value.trim();
        return value !== '';
      });
      submitButton.disabled = !isValid;
    };

    form.addEventListener('input', checkForm);
    document.addEventListener('DOMContentLoaded', checkForm);
  </script>
@endsection
