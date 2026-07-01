@extends('layouts.app')
@section('content')
  <h1>Ajukan Permintaan</h1>
  <form action="{{ route('feature-requests.store') }}" method="post">
    @csrf
    <div class="mb-3">
      <label class="form-label">Judul</label>
      <input name="title" value="{{ old('title') }}" class="form-control">
      @error('title')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Deskripsi</label>
      <textarea name="description" rows="6" class="form-control">{{ old('description') }}</textarea>
      @error('description')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="row mb-3">
      <div class="col">
        <label class="form-label">Tipe</label>
        <select name="type" class="form-select">
          <option value="feature">Feature</option>
          <option value="change">Change</option>
          <option value="bug">Bug</option>
        </select>
      </div>
      <div class="col">
        <label class="form-label">Prioritas</label>
        <select name="priority" class="form-select">
          <option value="low">Low</option>
          <option value="medium" selected>Medium</option>
          <option value="high">High</option>
          <option value="urgent">Urgent</option>
        </select>
      </div>
    </div>
    <button class="btn btn-success">Simpan</button>
  </form>
@endsection
