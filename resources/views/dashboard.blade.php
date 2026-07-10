@extends('layouts.app')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3 mb-1">Dashboard</h1>
      <p class="text-muted mb-0">Ringkasan seluruh permintaan perubahan aplikasi.</p>
    </div>
  </div>

  {{-- Summary Cards --}}
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <h6 class="text-muted">Total Request</h6>
          <h2 class="mb-0">{{ $totalRequests }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <h6 class="text-muted">Open</h6>
          <h2 class="mb-0 text-primary">{{ $openCount }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <h6 class="text-muted">In Progress</h6>
          <h2 class="mb-0 text-warning">{{ $inProgressCount }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <h6 class="text-muted">Completed</h6>
          <h2 class="mb-0 text-success">{{ $completedCount }}</h2>
        </div>
      </div>
    </div>
  </div>

  {{-- Feature Request Table --}}
  <div class="card shadow-sm">
    <div class="card-body">
      @if($requests->isEmpty())
        <div class="alert alert-info mb-0">Belum ada Feature Request.</div>
      @else
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Request Number</th>
                <th>Aplikasi</th>
                <th>Nama Perubahan</th>
                <th>Prioritas</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($requests as $r)
                <tr>
                  <td><code>{{ $r->request_number }}</code></td>
                  <td>{{ $r->application?->name ?? '-' }}</td>
                  <td>{{ $r->title }}</td>
                  <td><span class="badge bg-secondary">{{ ucfirst($r->priority) }}</span></td>
                  <td><span class="badge bg-info text-dark">{{ ucfirst($r->type) }}</span></td>
                  <td>
                    @if($r->status === 'Open')
                      <span class="badge bg-primary">Open</span>
                    @elseif($r->status === 'In Progress')
                      <span class="badge bg-warning text-dark">In Progress</span>
                    @elseif($r->status === 'Completed')
                      <span class="badge bg-success">Completed</span>
                    @endif
                  </td>
                  <td>{{ $r->created_at->format('d M Y') }}</td>
                  <td>
                    <div class="d-flex gap-1 flex-wrap">
                      {{-- Lihat (selalu ada) --}}
                      <a href="{{ route('feature-requests.show', $r) }}" class="btn btn-sm btn-outline-secondary">Lihat</a>

                      @if($r->status === 'Open')
                        <a href="{{ route('feature-requests.edit', $r) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('feature-requests.start', $r) }}" method="post" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-warning">Start</button>
                        </form>
                        <form action="{{ route('feature-requests.destroy', $r) }}" method="post" class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus permintaan ini?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                      @elseif($r->status === 'In Progress')
                        <form action="{{ route('feature-requests.cancel', $r) }}" method="post" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-outline-secondary">Cancel</button>
                        </form>
                        <form action="{{ route('feature-requests.complete', $r) }}" method="post" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-success">Completed</button>
                        </form>
                      @endif
                      {{-- Completed: hanya Lihat --}}
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
@endsection