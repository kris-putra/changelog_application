<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Changelog Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: radial-gradient(120% 120% at 15% 10%, #fbf8f0 0%, #f5efdf 45%, #e6dcc4 100%);
      min-height: 100vh;
      margin: 0;
      font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
    }
    .app-shell {
      padding-top: 76px;
    }
    .app-navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 76px;
      background: rgba(251,248,240,0.95);
      border-bottom: 1px solid #e4dccb;
      box-shadow: 0 10px 30px rgba(32,29,24,0.05);
      z-index: 50;
      display: flex;
      align-items: center;
    }
    .app-navbar .container {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }
    .app-navbar-brand {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      font-weight: 700;
      color: #201d18;
      text-decoration: none;
    }
    .brand-badge {
      width: 44px;
      height: 44px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #201d18;
      color: #f5efdf;
      border-radius: 12px;
      font-weight: 700;
    }
    .app-navbar-nav {
      display: flex;
      align-items: center;
      gap: 16px;
      flex: 1;
      justify-content: center;
    }
    .app-navbar-nav a {
      color: #201d18;
      text-decoration: none;
      font-weight: 600;
    }
    .app-navbar-actions {
      display: flex;
      gap: 12px;
    }
    .btn-ghost {
      border: 1px solid rgba(32,29,24,0.12);
      background: white;
      color: #201d18;
      border-radius: 12px;
      padding: 10px 18px;
      text-decoration: none;
      font-weight: 600;
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
      padding: 10px 18px;
      font-weight: 600;
      cursor: pointer;
    }
    .container.body-content {
      max-width: 1040px;
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
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @yield('content')
    </div>
  </main>
</body>
</html>
