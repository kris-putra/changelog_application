@props(['type' => 'success', 'title' => '', 'message' => '', 'extra' => ''])

@php
  $bgClass = match($type) {
    'success' => 'bg-success',
    'info'    => 'bg-info',
    'warning' => 'bg-warning',
    'error'   => 'bg-danger',
    default   => 'bg-primary',
  };
  $textClass = in_array($type, ['warning']) ? 'text-dark' : 'text-white';
@endphp

<div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
  <div id="appToast" class="toast align-items-center {{ $bgClass }} {{ $textClass }} border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        @if($title)
          <strong class="me-1">{{ $title }}</strong>
        @endif
        {{ $message }}
        @if($extra)
          <br><small class="fw-semibold">{{ $extra }}</small>
        @endif
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>