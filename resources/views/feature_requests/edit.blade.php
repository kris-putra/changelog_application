@extends('layouts.app')
@section('content')
  <h1>Edit Permintaan</h1>
  <form action="{{ route('feature-requests.update', $featureRequest) }}" method="post">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label class="form-label">Judul</label>
      <input name="title" value="{{ old('title', $featureRequest->title) }}" class="form-control">
      @error('title')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Deskripsi</label>
      <textarea name="description" rows="6" class="form-control">{{ old('description', $featureRequest->description) }}</textarea>
      @error('description')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="row mb-3">
      <div class="col">
        <label class="form-label">Tipe</label>
        <select name="type" class="form-select">
          @foreach(['feature' => 'Feature', 'change' => 'Change', 'bug' => 'Bug'] as $value => $label)
            <option value="{{ $value }}" {{ old('type', $featureRequest->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col">
        <label class="form-label">Prioritas</label>
        <select name="priority" class="form-select">
          @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'] as $value => $label)
            <option value="{{ $value }}" {{ old('priority', $featureRequest->priority) === $value ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <button class="btn btn-primary">Update</button>
  </form>
@endsection
