@extends('admin.layouts.contentNavbarLayout')

@section('title', "Mentors - $event->name")

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-secondary: #23d2c3;
    }
    /* --- GOKIL CARD STYLES --- */
    .gokil-card {
        border: none;
        background: #fff;
        border-radius: 1rem;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
        border-top: 4px solid transparent;
    }
    .gokil-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 167, 157, 0.2); /* Green Shadow */
        border-top-color: var(--squid-primary);
    }
    /* Avatar Glow */
    .mentor-avatar {
        width: 60px;
        height: 60px;
        border: 3px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    .gokil-card:hover .mentor-avatar {
        transform: scale(1.1) rotate(5deg);
        border-color: var(--squid-primary);
    }
    /* Custom Scrollbar */
    .group-list-custom {
        max-height: 180px;
        overflow-y: auto;
        padding-right: 5px;
        margin-top: 15px;
    }
    .group-list-custom::-webkit-scrollbar { width: 5px; }
    .group-list-custom::-webkit-scrollbar-thumb { background: #d9dee3; border-radius: 10px; }
    .group-list-custom::-webkit-scrollbar-thumb:hover { background: var(--squid-primary); }

    /* Badge Custom */
    .badge-squid {
        background-color: rgba(0, 167, 157, 0.15);
        color: #008f85;
    }
    .btn-squid {
        background-color: var(--squid-primary);
        border-color: var(--squid-primary);
        color: white;
    }
    .btn-squid:hover {
        background-color: #008f85;
        border-color: #008f85;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 167, 157, 0.4);
    }
</style>
@endsection

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color: #008f85;">
            <i class="bx bx-user-voice fs-3 me-2"></i>Event Mentors
        </h4>
        <span class="text-muted">Event: <strong class="text-dark">{{ $event->name }}</strong></span>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-outline-secondary shadow-sm btn-lg">
            <i class="bx bx-arrow-back me-1"></i> Kembali
        </a>
        <a href="{{ route('admin.events.mentors.create', $event->id) }}" class="btn btn-squid shadow-sm btn-lg">
            <i class="bx bx-plus me-1"></i> Tambah Mentor
        </a>
    </div>
</div>

<div class="row g-4">
  @forelse ($mentors as $mentor)
    <div class="col-md-6 col-lg-4 col-xl-4">
      <div class="card h-100 gokil-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        @if($mentor->avatar)
                            <img src="{{ asset($mentor->avatar) }}" alt="Avatar" class="rounded-circle mentor-avatar">
                        @else
                            <span class="avatar-initial rounded-circle bg-label-teal text-teal fs-4 fw-bold mentor-avatar d-flex align-items-center justify-content-center" style="color: #00a79d; background-color: #e0f2f1;">
                                {{ substr($mentor->name, 0, 1) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark text-truncate" style="max-width: 160px;" title="{{ $mentor->name }}">{{ $mentor->name }}</h5>
                        <small class="text-muted d-block mb-1">{{ $mentor->email }}</small>
                        <span class="badge badge-squid rounded-pill" style="font-size: 0.65rem;">MENTOR</span>
                    </div>
                </div>
                <div class="text-center bg-label-secondary rounded p-2" style="min-width: 60px;">
                    <small class="d-block text-uppercase text-muted fw-bold" style="font-size: 0.6rem;">Groups</small>
                    <span class="fs-5 fw-bold" style="color: #00a79d;">{{ $mentor->groups->count() }}</span>
                </div>
            </div>

            <hr class="my-3 border-light">

            <div class="d-flex justify-content-between align-items-center">
                <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Daftar Binaan</small>
            </div>

            <div class="group-list-custom">
                @if($mentor->groups->isEmpty())
                    <div class="d-flex flex-column align-items-center justify-content-center py-4 bg-lighter rounded border border-dashed mt-2">
                        <i class="bx bx-ghost fs-2 text-muted mb-1"></i>
                        <small class="text-muted fst-italic">Belum ada kelompok.</small>
                    </div>
                @else
                    <div class="d-flex flex-column gap-2">
                        @foreach($mentor->groups as $group)
                            <div class="d-flex align-items-center p-2 rounded" style="background-color: #e0f2f1; border-left: 3px solid #00a79d;">
                                <div class="avatar avatar-xs me-2">
                                    <span class="avatar-initial rounded-circle bg-white" style="color: #00a79d;">
                                        <i class="bx bx-group" style="font-size: 0.8rem;"></i>
                                    </span>
                                </div>
                                <span class="fw-semibold small text-truncate text-dark" style="max-width: 85%;">
                                    {{ $group->name }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="card-footer bg-white border-top p-3">
            <div class="row g-2">
                <div class="col-8">
                    <a href="{{ route('admin.events.mentors.edit', ['event' => $event->id, 'mentor' => $mentor->id]) }}" class="btn btn-outline-secondary w-100 btn-sm d-flex align-items-center justify-content-center">
                        <i class="bx bx-edit-alt me-1"></i> Edit Groups
                    </a>
                </div>
                <div class="col-4">
                    <form action="{{ route('admin.events.mentors.destroy', ['event' => $event->id, 'mentor' => $mentor->id]) }}" method="POST" onsubmit="return confirm('Yakin hapus mentor ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-label-danger w-100 btn-sm"><i class="bx bx-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12 text-center py-5">
        <div class="badge bg-label-teal p-4 rounded-circle mb-3" style="color: #00a79d; background-color: #e0f2f1;"><i class="bx bx-user-voice fs-1"></i></div>
        <h4 class="text-muted">Belum ada Mentor</h4>
        <a href="{{ route('admin.events.mentors.create', $event->id) }}" class="btn btn-squid">Tambah Mentor</a>
    </div>
  @endforelse
</div>
@endsection
