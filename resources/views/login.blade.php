<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Changelog — Masuk</title>
    <style>
        :root {
            --cream: #f5efdf;
            --cream-light: #fbf8f0;
            --ink: #201d18;
            --muted: #7a746a;
            --border: #e4dccb;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            position: relative;
            overflow: hidden;
            background: radial-gradient(120% 120% at 15% 10%, #fbf8f0 0%, #f5efdf 45%, #e6dcc4 100%);
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
            max-width: 440px;
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
        input {
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
        input:focus { border-color: var(--ink); box-shadow: 0 0 0 3px rgba(32,29,24,0.12); }
        input::placeholder { color: rgba(122,116,106,0.7); }
        button {
            margin-top: 8px;
            width: 100%;
            padding: 14px;
            font-size: 14px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.03em;
            color: var(--cream); background: var(--ink);
            border: none; border-radius: 12px; cursor: pointer;
            transition: background-color .15s;
        }
        button:hover { background: #35302a; }
        .footer {
            margin-top: 24px; text-align: center;
            font-family: ui-monospace, monospace; font-size: 12px; color: var(--muted);
        }
        .error { color: #b91c1c; font-size: 13px; }
    </style>
</head>
<body>
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
        <h1>Selamat Datang di Aplikasi Changelog</h1>
        <p class="subtitle">Masuk untuk melihat riwayat perubahan, versi, dan catatan rilis terbaru pada aplikasi pemerintah kota mataram.</p>

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="field">
                <label for="username">Nama Pengguna</label>
                <input id="username" name="username" type="text" placeholder="nama pengguna anda" autocomplete="username" value="{{ old('username') }}" />
                @error('username')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label for="password">Kata Sandi</label>
                <input id="password" name="password" type="password" placeholder="kata sandi anda" autocomplete="current-password" />
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            @if ($errors->has('username') && !$errors->has('password'))
                <div class="error">{{ $errors->first('username') }}</div>
            @endif
            <button type="submit">Masuk</button>
        </form>

    </section>
</body>
</html>
