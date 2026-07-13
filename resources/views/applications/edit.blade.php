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
      max-width: 420px;
      background: rgba(251,248,240,0.85);
      backdrop-filter: blur(6px);
      border: 1px solid var(--border);
      border-radius: 24px;
      padding: 24px;
      box-shadow: 0 20px 40px rgba(32,29,24,0.06);
    }
    .page-content .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
    .page-content .brand-badge {
      width: 44px; height: 44px;
      display: flex; align-items: center; justify-content: center;
      background: var(--ink); color: var(--cream);
      border-radius: 12px; font-weight: 700;
    }
    .page-content .brand-label {
      font-family: ui-monospace, monospace;
      font-size: 12px; letter-spacing: 0.28em;
      text-transform: uppercase; color: var(--muted);
    }
    .page-content h1 {
      font-size: 30px; line-height: 1.15;
      text-transform: uppercase; letter-spacing: -0.01em;
    }
    .page-content .subtitle { margin-top: 12px; font-size: 14px; line-height: 1.6; color: var(--muted); }
    .page-content form { margin-top: 0; display: flex; flex-direction: column; gap: 14px; }
    .page-content .field { display: flex; flex-direction: column; gap: 6px; }
    .page-content label { font-size: 13px; font-weight: 500; }
    .page-content input, .page-content select, .page-content textarea {
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
    .page-content textarea { min-height: 100px; resize: vertical; }
    .page-content input:focus, .page-content select:focus, .page-content textarea:focus { border-color: var(--ink); box-shadow: 0 0 0 3px rgba(32,29,24,0.12); }
    .page-content input::placeholder, .page-content textarea::placeholder { color: rgba(122,116,106,0.7); }
    .page-content .btn-row {
      display: flex;
      gap: 12px;
      margin-top: 4px;
    }
    .page-content .btn-row button,
    .page-content .btn-row a {
      flex: 1;
      padding: 12px;
      font-size: 14px; font-weight: 600;
      text-transform: uppercase; letter-spacing: 0.03em;
      border-radius: 12px; cursor: pointer;
      text-decoration: none; text-align: center;
      transition: background-color .15s;
    }
    .page-content .btn-primary {
      color: var(--cream);
      background: var(--ink);
      border: none;
    }
    .page-content .btn-primary:hover { background: #35302a; }
    .page-content .btn-secondary {
      color: var(--ink);
      background: transparent;
      border: 1px solid var(--border);
    }
    .page-content .btn-secondary:hover { background: rgba(32,29,24,0.05); }
    .page-content .error { color: #b91c1c; font-size: 13px; }
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
      <form action="{{ route('applications.update', $application) }}" method="POST" id="application-form">
        @csrf
        @method('PUT')
        <div class="field">
          <label for="name">Nama Aplikasi</label>
          <input id="name" name="name" type="text" placeholder="Nama Aplikasi" value="{{ old('name', $application->name) }}" required />
          @error('name')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <div class="field">
          <label for="description">Deskripsi Aplikasi</label>
          <textarea id="description" name="description" placeholder="Deskripsi Aplikasi" required>{{ old('description', $application->description) }}</textarea>
          @error('description')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <div class="field">
          <label for="url">URL</label>
          <input id="url" name="url" type="text" placeholder="contoh: example.com" value="{{ old('url', $application->url) }}" required />
          @error('url')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <div class="field">
          <label for="location">Lokasi Aplikasi</label>
          <select id="location" name="location" required>
            <option value="">Pilih lokasi aplikasi</option>
            <option value="Local Server" {{ old('location', $application->location) == 'Local Server' ? 'selected' : '' }}>Local Server</option>
            <option value="PDNS Server" {{ old('location', $application->location) == 'PDNS Server' ? 'selected' : '' }}>PDNS Server</option>
            <option value="Third Party" {{ old('location', $application->location) == 'Third Party' ? 'selected' : '' }}>Third Party</option>
          </select>
          @error('location')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <div class="btn-row">
          <a href="{{ route('dashboard') }}" class="btn-secondary">Batal</a>
          <button type="submit" class="btn-primary">Update</button>
        </div>
      </form>
    </section>
  </div>
@endsection
