@extends('admin.layouts.contentNavbarLayout')

@section('title', "Edit Saldo - $event->name")

@section('styles')
<style>
    :root { --squid-primary: #00a79d; }
    .form-control:focus {
        border-color: var(--squid-primary);
        box-shadow: 0 0 0 0.25rem rgba(0, 167, 157, 0.25);
    }
    .btn-squid {
        background-color: var(--squid-primary);
        border-color: var(--squid-primary);
        color: white;
        transition: 0.3s;
    }
    .btn-squid:hover {
        background-color: #008f85;
        box-shadow: 0 4px 12px rgba(0, 167, 157, 0.4);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4 border-0 shadow-sm">

            <div class="card-header border-bottom bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-md">
                        @if($investor->user->avatar)
                            <img src="{{ asset($investor->user->avatar) }}" class="rounded-circle">
                        @else
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                {{ substr($investor->user->name, 0, 1) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">{{ $investor->user->name }}</h5>
                        <small class="text-muted">Edit Modal Investasi</small>
                    </div>
                </div>

                <a href="{{ route('admin.events.investors.index', $event->id) }}" class="btn btn-sm btn-icon btn-label-secondary">
                    <i class="bx bx-x"></i>
                </a>
            </div>

            <div class="card-body pt-5 pb-5">

                <form action="{{ route('admin.events.investors.update', ['event' => $event->id, 'investor' => $investor->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="text-center mb-4">
                        <label class="form-label text-uppercase text-muted fw-bold small">Saldo Saat Ini</label>
                        <h2 class="text-success fw-bold mb-0 display-6">
                            ${{ number_format($investor->investment_balance, 0, ',', '.') }}
                        </h2>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Update Saldo ($)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="bx bx-dollar"></i></span>
                            <input type="number" class="form-control fw-bold text-dark"
                                   name="investment_balance"
                                   value="{{ old('investment_balance', $investor->investment_balance) }}"
                                   min="0" required>
                        </div>
                        <div class="form-text text-center mt-2">
                            Ubah angka di atas untuk memperbarui modal investor.
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-squid btn-lg shadow-sm">
                            <i class="bx bx-check-circle me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
