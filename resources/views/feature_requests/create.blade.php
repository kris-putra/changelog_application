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
    .page-content form { margin-top: 0; display: flex; flex-direction: column; gap: 14px; }
    .page-content .field { display: flex; flex-direction: column; gap: 6px; }
    .page-content .field-row { display: flex; gap: 12px; }
    .page-content .field-row .field { flex: 1; }
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
    .page-content button[type="submit"] {
      margin-top: 4px;
      width: 100%;
      padding: 12px;
      font-size: 14px; font-weight: 600;
      text-transform: uppercase; letter-spacing: 0.03em;
      color: var(--cream); background: var(--ink);
      border: none; border-radius: 12px; cursor: pointer;
      transition: background-color .15s;
    }
    .page-content button[type="submit"]:hover { background: #35302a; }
    .page-content button[type="submit"]:disabled { background: #a8a18f; cursor: not-allowed; }
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
      <form action="{{ route('feature-requests.store') }}" method="post" id="feature-form">
        @csrf
        <div class="field">
          <label for="title">Judul</label>
          <input id="title" name="title" type="text" placeholder="Judul Permintaan" value="{{ old('title') }}" required />
          @error('title')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <div class="field">
          <label for="description">Deskripsi</label>
          <textarea id="description" name="description" placeholder="Deskripsi Permintaan" required>{{ old('description') }}</textarea>
          @error('description')
            <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <div class="field-row">
          <div class="field">
            <label for="type">Tipe</label>
            <select id="type" name="type" required>
              <option value="feature" {{ old('type') == 'feature' ? 'selected' : '' }}>Feature</option>
              <option value="change" {{ old('type') == 'change' ? 'selected' : '' }}>Change</option>
              <option value="bug" {{ old('type') == 'bug' ? 'selected' : '' }}>Bug</option>
            </select>
          </div>
          <div class="field">
            <label for="priority">Prioritas</label>
            <select id="priority" name="priority" required>
              <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
              <option value="medium" {{ old('priority') == 'medium' || !old('priority') ? 'selected' : '' }}>Medium</option>
              <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
              <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
            </select>
          </div>
        </div>
        <button type="submit" id="submit-button">Simpan</button>
      </form>
    </section>
  </div>
@endsection
