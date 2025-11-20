@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Guidelines - $event->name")

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Event: {{ $event->name }} /</span> Guidelines
</h4>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Guidelines</h5>
    <a href="#" class="btn btn-primary">Tambah Guideline</a>
  </div>
  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Title</th>
            <th>Price ($)</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {{-- @forelse ($guidelines as $guideline) --}}
          {{-- Hapus komentar ini setelah relasi 'guidelines()' ditambahkan di Model Event --}}
          {{--
          <tr>
            <td><strong>{{ $guideline->title }}</strong></td>
            <td>${{ number_format($guideline->price) }}</td>
            <td>{{ Str::limit($guideline->description, 80) }}</td>
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
          @empty
          --}}
          <tr>
            <td colspan="4" class="text-center">Belum ada guidelines untuk event ini. (Atau relasi 'guidelines' di Model Event belum dibuat)</td>
          </tr>
          {{-- @endforelse --}}
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
