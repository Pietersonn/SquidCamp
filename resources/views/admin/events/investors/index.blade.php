@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Investors - $event->name")

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Event: {{ $event->name }} /</span> Investors
</h4>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Investor (Internal)</h5>
    <a href="#" class="btn btn-primary">Assign Investor</a>
  </div>
  <div class="card-body">
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Investment Balance ($)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($eventInvestors as $record)
          <tr>
            <td><strong>{{ $record->user->name ?? 'N/A' }}</strong></td>
            <td>{{ $record->user->email ?? 'N/A' }}</td>
            <td>${{ number_format($record->investment_balance) }}</td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#"><i class="bx bx-edit-alt me-1"></i> Edit Saldo</a>
                  <a class="dropdown-item" href="#"><i class="bx bx-trash me-1"></i> Unassign</a>
                </div>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-center">Belum ada investor yang di-assign ke event ini.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
