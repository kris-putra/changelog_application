<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Changelog Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
  <style>
    body {
      background: radial-gradient(120% 120% at 15% 10%, #fbf8f0 0%, #f5efdf 45%, #e6dcc4 100%);
      min-height: 100vh;
      margin: 0;
      font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
    }
    .app-shell {
      padding-top: 48px;
    }
    .app-navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 48px;
      background: rgba(251,248,240,0.95);
      border-bottom: 1px solid #e4dccb;
      box-shadow: 0 8px 20px rgba(32,29,24,0.05);
      z-index: 50;
      display: flex;
      align-items: center;
      padding: 0 16px;
    }
    .app-navbar .container {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      gap: 24px;
      width: 100%;
      max-width: none;
      padding: 0;
    }
    .app-navbar-brand {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-weight: 700;
      color: #201d18;
      text-decoration: none;
      font-size: 13px;
      flex-shrink: 0;
    }
    .brand-badge {
      width: 32px;
      height: 32px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #201d18;
      color: #f5efdf;
      border-radius: 10px;
      font-weight: 700;
      font-size: 1rem;
    }
    .app-navbar-nav {
      display: flex;
      align-items: center;
      gap: 20px;
      flex: 0;
      justify-content: flex-start;
    }
    .app-navbar-nav a {
      color: #201d18;
      text-decoration: none;
      font-weight: 600;
      font-size: 13px;
      white-space: nowrap;
    }
    .app-navbar-actions {
      display: flex;
      gap: 8px;
      margin-left: auto;
      flex-shrink: 0;
    }
    .btn-ghost {
      border: 1px solid rgba(32,29,24,0.12);
      background: white;
      color: #201d18;
      border-radius: 12px;
      padding: 6px 12px;
      text-decoration: none;
      font-weight: 600;
      font-size: 12px;
    }
    .btn-ghost:hover,
    .btn-logout:hover {
      background: #f2f2f2;
    }
    .btn-logout {
      border: 1px solid rgba(32,29,24,0.12);
      background: white;
      color: #201d18;
      border-radius: 12px;
      padding: 6px 12px;
      font-weight: 600;
      font-size: 12px;
      cursor: pointer;
    }
    .container.body-content {
      max-width: 1280px;
      padding: 24px 16px 40px;
    }
  </style>
</head>
<body>
  <header class="app-navbar">
    <div class="container">
      <a class="app-navbar-brand" href="{{ route('dashboard') }}">
        <span class="brand-badge">C</span>
      </a>
      <nav class="app-navbar-nav">
        <a href="{{ route('applications.create') }}">Tambah Aplikasi</a>
        <a href="{{ route('feature-requests.create') }}">Tambah Permintaan</a>
      </nav>
      <div class="app-navbar-actions">
        <form method="POST" action="{{ route('logout') }}" class="m-0">
          @csrf
          <button type="submit" class="btn-logout">Logout</button>
        </form>
      </div>
    </div>
  </header>
  <main class="app-shell">
    <div class="container body-content">
      @if(session('toast'))
        <x-toast
          :type="session('toast.type', 'success')"
          :title="session('toast.title', '')"
          :message="session('toast.message', '')"
          :extra="session('toast.extra', '')"
        />
      @endif
      @yield('content')
    </div>
  </main>
  @if(session('toast'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var toastEl = document.getElementById('appToast');
        if (toastEl) {
          var toast = new bootstrap.Toast(toastEl, { delay: 5000 });
          toast.show();
        }
      });
    </script>
  @endif
  @yield('scripts')
</body>
</html>
