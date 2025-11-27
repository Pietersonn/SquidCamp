@extends('admin.layouts.contentNavbarLayout')

@section('title', "Tambah Investor - $event->name")

@section('styles')
<style>
    :root { --squid-primary: #00a79d; }
    .form-control:focus, .form-select:focus {
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
        border-color: #008f85;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 167, 157, 0.4);
    }
    .step-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--squid-primary);
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.85rem;
        margin-right: 10px;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card mb-4 border-0 shadow-sm">

            <div class="card-header border-bottom bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--squid-primary);">Tambah Investor Baru</h5>
                    <small class="text-muted">Event: {{ $event->name }}</small>
                </div>
                <a href="{{ route('admin.events.investors.index', $event->id) }}" class="btn btn-label-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body pt-4">

                @if($availableInvestors->isEmpty())
                    <div class="text-center py-5">
                        <div class="avatar avatar-xl bg-label-warning rounded-circle mx-auto mb-3">
                            <i class="bx bx-info-circle fs-1"></i>
                        </div>
                        <h5 class="mb-2 text-dark">Semua Investor Sudah Terdaftar</h5>
                        <p class="text-muted mb-4">Tidak ada user dengan role 'Investor' yang tersedia untuk ditambahkan.</p>
                        <a href="{{ route('admin.events.investors.index', $event->id) }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                @else
                    <form action="{{ route('admin.events.investors.store', $event->id) }}" method="POST">
                        @csrf

                        {{-- Step 1 --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex align-items-center mb-2">
                                <span class="step-circle">1</span> Pilih User Investor
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white border-end-0"><i class="bx bx-user text-muted"></i></span>
                                <select class="form-select border-start-0 ps-0" name="user_id" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach($availableInvestors as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Step 2 --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex align-items-center mb-2">
                                <span class="step-circle">2</span> Saldo Awal Investasi ($)
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-label-success border-success text-success fw-bold">$</span>
                                <input type="number" class="form-control border-success text-success fw-bold"
                                       name="investment_balance"
                                       placeholder="Contoh: 10000"
                                       min="0" required>
                            </div>
                            <div class="form-text mt-2 ms-1 text-muted">
                                <i class="bx bx-info-circle me-1"></i> Masukkan jumlah modal awal yang dimiliki investor untuk event ini.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.events.investors.index', $event->id) }}" class="btn btn-label-secondary">Batal</a>
                            <button type="submit" class="btn btn-squid px-4 shadow-sm">
                                <i class="bx bx-save me-1"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
