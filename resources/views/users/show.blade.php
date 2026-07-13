@extends('layouts.app')

@section('content')
<div class="mb-4">
  <h4 class="mb-0" style="font-weight:700;color:#201d18;">Detail User</h4>
</div>

<div class="card" style="border-radius:16px;border:1px solid #e4dccb;background:rgba(255,255,255,0.85);box-shadow:0 2px 12px rgba(32,29,24,0.04);">
  <div class="card-body p-4">
    <table class="table mb-0" style="font-size:13px;">
      <tr>
        <th style="width:200px;color:#6c757d;font-weight:600;">Username</th>
        <td>{{ $user->username }}</td>
      </tr>
      <tr>
        <th style="width:200px;color:#6c757d;font-weight:600;">Nama Tampilan</th>
        <td>{{ $user->profile_name ?? '-' }}</td>
      </tr>
      <tr>
        <th style="color:#6c757d;font-weight:600;">Email</th>
        <td>{{ $user->email }}</td>
      </tr>
      <tr>
        <th style="color:#6c757d;font-weight:600;">Role</th>
        <td><span class="badge" style="background:#e4dccb;color:#201d18;font-weight:600;font-size:11px;">{{ $user->role->name ?? '-' }}</span></td>
      </tr>
      <tr>
        <th style="color:#6c757d;font-weight:600;">Terdaftar</th>
        <td>{{ $user->created_at->format('d M Y H:i') }}</td>
      </tr>
    </table>
    <div class="mt-3 d-flex gap-2">
      <a href="{{ route('users.edit', $user) }}" class="btn" style="background:#201d18;color:#f5efdf;border-radius:12px;padding:8px 24px;font-weight:600;font-size:13px;">Edit</a>
      <a href="{{ route('users.index') }}" class="btn-ghost" style="border-radius:12px;padding:8px 24px;font-weight:600;font-size:13px;text-decoration:none;">Kembali</a>
    </div>
  </div>
</div>
@endsection