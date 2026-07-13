@extends('layouts.app')

@section('content')
<div class="mb-4">
  <h4 class="mb-0" style="font-weight:700;color:#201d18;">Pengaturan</h4>
</div>

<div class="row g-4">
  {{-- Profile --}}
  <div class="col-md-6">
    <div class="card" style="border-radius:16px;border:1px solid #e4dccb;background:rgba(255,255,255,0.85);box-shadow:0 2px 12px rgba(32,29,24,0.04);">
      <div class="card-body p-4">
        <h5 class="mb-3" style="font-weight:700;font-size:15px;color:#201d18;">Profil Saya</h5>
        <form method="POST" action="{{ route('settings.profile') }}">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label for="name" class="form-label" style="font-weight:600;font-size:13px;">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="email" class="form-label" style="font-weight:600;font-size:13px;">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-weight:600;font-size:13px;">Role</label>
            <input type="text" class="form-control" value="{{ $user->role->name ?? '-' }}" disabled>
          </div>
          <button type="submit" class="btn" style="background:#201d18;color:#f5efdf;border-radius:12px;padding:8px 24px;font-weight:600;font-size:13px;">Simpan Profil</button>
        </form>
      </div>
    </div>
  </div>

  {{-- Password --}}
  <div class="col-md-6">
    <div class="card" style="border-radius:16px;border:1px solid #e4dccb;background:rgba(255,255,255,0.85);box-shadow:0 2px 12px rgba(32,29,24,0.04);">
      <div class="card-body p-4">
        <h5 class="mb-3" style="font-weight:700;font-size:15px;color:#201d18;">Ubah Password</h5>
        <form method="POST" action="{{ route('settings.password') }}">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label for="current_password" class="form-label" style="font-weight:600;font-size:13px;">Password Lama</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
            @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="password" class="form-label" style="font-weight:600;font-size:13px;">Password Baru</label>
            <input type="password" class="form-control" id="password" name="password" required>
            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="password_confirmation" class="form-label" style="font-weight:600;font-size:13px;">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
          </div>
          <button type="submit" class="btn" style="background:#201d18;color:#f5efdf;border-radius:12px;padding:8px 24px;font-weight:600;font-size:13px;">Ubah Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection