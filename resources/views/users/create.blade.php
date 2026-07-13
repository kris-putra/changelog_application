@extends('layouts.app')

@section('content')
<div class="mb-4">
  <h4 class="mb-0" style="font-weight:700;color:#201d18;">Tambah User</h4>
</div>

<div class="card" style="border-radius:16px;border:1px solid #e4dccb;background:rgba(255,255,255,0.85);box-shadow:0 2px 12px rgba(32,29,24,0.04);">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('users.store') }}">
      @csrf
      <div class="mb-3">
        <label for="username" class="form-label" style="font-weight:600;font-size:13px;">Username</label>
        <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
        @error('username')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label for="email" class="form-label" style="font-weight:600;font-size:13px;">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label for="password" class="form-label" style="font-weight:600;font-size:13px;">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="mb-3">
        <label for="password_confirmation" class="form-label" style="font-weight:600;font-size:13px;">Confirm Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
      </div>
      <div class="mb-3">
        <label for="role_id" class="form-label" style="font-weight:600;font-size:13px;">Role</label>
        <select class="form-select" id="role_id" name="role_id" required>
          <option value="">-- Pilih Role --</option>
          @foreach($roles as $role)
            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
          @endforeach
        </select>
        @error('role_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn" style="background:#201d18;color:#f5efdf;border-radius:12px;padding:8px 24px;font-weight:600;font-size:13px;">Simpan</button>
        <a href="{{ route('users.index') }}" class="btn-ghost" style="border-radius:12px;padding:8px 24px;font-weight:600;font-size:13px;text-decoration:none;">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection