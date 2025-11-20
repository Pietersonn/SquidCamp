@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Mentors - $event->name")

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Event: {{ $event->name }} /</span> Mentors
</h4>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Mentor (Internal)</h5>
    <a href="#" class="btn btn-primary">Assign Mentor</a>
  </div>
  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Grup Bimbingan</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {{-- @forelse ($eventMentors as $record) --}}
          {{-- Hapus komentar ini setelah relasi 'eventMentors()' ditambahkan di Model Event --}}
          {{--
          <tr>
            <td><strong>{{ $record->user->name ?? 'N/A' }}</strong></td>
            <td>{{ $record->user->email ?? 'N/A' }}</td>
            <td>
              {{-- Logika untuk mencari grup bimbingan mentor ini --}}
              {{-- {{ $event->groups->where('mentor_id', $record->user_id)->pluck('name')->join(', ') ?: 'Belum ada' }} --}}
            </td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  {{-- Assign ke grup ada di halaman 'Groups' --}}
                  <a class="dropdown-item" href="#"><i class="bx bx-trash me-1"></i> Unassign</a>
                </div>
              </div>
            </td>
          </tr>
          @empty
          --}}
          <tr>
            <td colspan="4" class="text-center">Belum ada mentor yang di-assign ke event ini. (Atau relasi 'eventMentors' di Model Event belum dibuat)</td>
          </tr>
          {{-- @endforelse --}}
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
