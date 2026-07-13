@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0" style="font-weight:700;color:#201d18;">Kelola User</h4>
  <a href="{{ route('users.create') }}" class="btn-ghost" style="border-radius:12px;padding:8px 20px;font-weight:600;font-size:13px;background:#201d18;color:#f5efdf;text-decoration:none;">
    <i class="bi bi-plus-lg me-1"></i> Tambah User
  </a>
</div>

<div class="card" style="border-radius:16px;border:1px solid #e4dccb;background:rgba(255,255,255,0.85);box-shadow:0 2px 12px rgba(32,29,24,0.04);">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" style="font-size:13px;">
        <thead>
          <tr style="background:#f5efdf;">
            <th class="px-3 py-2" style="font-weight:700;font-size:12px;color:#201d18;">No</th>
            <th class="px-3 py-2" style="font-weight:700;font-size:12px;color:#201d18;">Name</th>
            <th class="px-3 py-2" style="font-weight:700;font-size:12px;color:#201d18;">Email</th>
            <th class="px-3 py-2" style="font-weight:700;font-size:12px;color:#201d18;">Role</th>
            <th class="px-3 py-2 text-center" style="font-weight:700;font-size:12px;color:#201d18;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $i => $user)
            <tr>
              <td class="px-3">{{ $i + 1 }}</td>
              <td class="px-3">{{ $user->name }}</td>
              <td class="px-3">{{ $user->email }}</td>
              <td class="px-3">
                <span class="badge" style="background:#e4dccb;color:#201d18;font-weight:600;font-size:11px;">{{ $user->role->name ?? '-' }}</span>
              </td>
              <td class="px-3 text-center">
                <a href="{{ route('users.show', $user) }}" class="me-1" style="color:#201d18;" data-bs-toggle="tooltip" title="View">
                  <i class="bi bi-eye" style="font-size:15px;"></i>
                </a>
                <a href="{{ route('users.edit', $user) }}" class="me-1" style="color:#201d18;" data-bs-toggle="tooltip" title="Edit">
                  <i class="bi bi-pencil-square" style="font-size:15px;"></i>
                </a>
                <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn p-0 border-0 bg-transparent" style="color:#dc3545;" data-bs-toggle="tooltip" title="Delete">
                    <i class="bi bi-trash" style="font-size:15px;"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-4" style="color:#6c757d;">Tidak ada data user.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (el) { new bootstrap.Tooltip(el); });
  });
</script>
@endsection