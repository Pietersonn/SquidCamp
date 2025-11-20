@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Cases - $event->name")

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Event: {{ $event->name }} /</span> Cases
</h4>

<div class="card">
  <h5 class="card-header">Daftar Squid Case</h5>
  <div class="card-body">
    @if($squidCase)
      <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Title</th>
              <th>Description</th>
              <th>Reward ($)</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>{{ $squidCase->title }}</strong></td>
              <td>{{ Str::limit($squidCase->description, 80) }}</td>
              <td>${{ number_format($squidCase->reward_dollar ?? 0) }}</td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="#"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                    <a class="dropdown-item" href="#"><i class="bx bx-trash me-1"></i> Delete</a>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    @else
      <p>Belum ada Squid Case untuk event ini.</p>
      <a href="#" class="btn btn-primary">Buat Squid Case</a>
    @endif
  </div>
</div>
@endsection
