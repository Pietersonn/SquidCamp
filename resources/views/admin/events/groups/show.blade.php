@extends('admin.layouts.contentNavbarLayout')

@section('title', "Detail Kelompok - $group->name")

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <span class="text-muted fw-light">Event: {{ $event->name }} / Groups /</span> Detail
    </h4>
    <a href="{{ route('admin.events.groups.index', $event->id) }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>

<div class="row">
    {{-- Kolom Kiri: Info Utama --}}
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-start">
                        <div class="avatar avatar-lg me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-group fs-1"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-primary">{{ $group->name }}</h4>
                            <small class="text-muted">Total Anggota: {{ $group->members->count() }}</small>
                        </div>
                    </div>
                    <span class="badge bg-label-success fs-5">${{ number_format($group->squid_dollar, 0, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between flex-wrap mt-4 gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar avatar-sm">
                            <span class="avatar-initial rounded-circle bg-label-info"><i class='bx bx-user-voice'></i></span>
                        </div>
                        <div>
                            <small class="text-muted d-block">Mentor</small>
                            <span class="fw-semibold">{{ $group->mentor->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="pb-1">Struktur Tim</h6>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm me-3">
                            <span class="avatar-initial rounded-circle bg-warning text-white">C</span>
                        </div>
                        <div>
                            <small class="text-muted d-block">Captain</small>
                            <span class="fw-semibold text-heading">{{ $group->captain->name ?? 'Belum ditentukan' }}</span>
                        </div>
                    </li>
                    <li class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-3">
                            <span class="avatar-initial rounded-circle bg-secondary text-white">Co</span>
                        </div>
                        <div>
                            <small class="text-muted d-block">Co-Captain</small>
                            <span class="fw-semibold text-heading">{{ $group->cocaptain->name ?? 'Belum ditentukan' }}</span>
                        </div>
                    </li>
                </ul>

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('admin.events.groups.edit', ['event' => $event->id, 'group' => $group->id]) }}" class="btn btn-primary">
                        <i class="bx bx-edit-alt me-1"></i> Edit Kelompok
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Daftar Anggota --}}
    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
        <div class="card mb-4">
            <h5 class="card-header">Daftar Anggota Kelompok</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($group->members as $member)
                        @php
                            $name = $member->user->name ?? $member->name ?? 'Unknown';
                            $email = $member->user->email ?? $member->email ?? '-';
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs me-2">
                                        <span class="avatar-initial rounded-circle bg-label-secondary">
                                            {{ substr($name, 0, 1) }}
                                        </span>
                                    </div>
                                    <span class="fw-semibold">{{ $name }}</span>

                                    {{-- Badge Label --}}
                                    @if($group->captain_id == ($member->user_id ?? 0))
                                        <span class="badge bg-warning ms-2" style="font-size: 0.65rem;">Captain</span>
                                    @elseif($group->cocaptain_id == ($member->user_id ?? 0))
                                        <span class="badge bg-secondary ms-2" style="font-size: 0.65rem;">Co-Captain</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $email }}</td>
                            <td>
                                <span class="badge bg-label-success">Aktif</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                Belum ada anggota di kelompok ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
