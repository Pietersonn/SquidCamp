@extends('mentor.layouts.master')
@section('title', 'My Teams')

@section('styles')
<style>
    .header-teams {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px 25px 80px 25px;
        border-bottom-left-radius: 35px;
        border-bottom-right-radius: 35px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .team-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
        margin-bottom: 15px;
        position: relative;
        overflow: hidden;
        border-left: 5px solid transparent;
    }
    .team-card:active { transform: scale(0.98); }

    .team-icon {
        width: 50px; height: 50px;
        background: #f0f4ff;
        color: #667eea;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    .squid-dollar {
        color: #00a79d;
        font-weight: 800;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')

    {{-- HEADER --}}
    <div class="header-teams">
        <h3 class="fw-bold text-white mb-1">My Teams</h3>
        <p class="text-white opacity-75 small mb-0">Memantau progres {{ $groups->count() }} kelompok binaan.</p>
        <i class='bx bx-group position-absolute' style="font-size: 8rem; top: 10px; right: -20px; opacity: 0.1; color: white;"></i>
    </div>

    <div class="container-fluid px-4" style="margin-top: -50px;">

        @forelse($groups as $group)
        <a href="{{ route('mentor.groups.show', $group->id) }}" class="text-decoration-none">
            <div class="team-card p-3" style="border-left-color: {{ $group->completed_challenges_count > 0 ? '#00a79d' : '#667eea' }};">
                <div class="d-flex align-items-center mb-3">
                    <div class="team-icon me-3 shadow-sm text-primary bg-label-primary">
                        <i class='bx bxs-face-mask'></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ $group->name }}</h6>
                        <span class="squid-dollar">SQ$ {{ number_format($group->squid_dollar) }}</span>
                    </div>
                    <div class="ms-auto text-muted">
                        <i class='bx bx-chevron-right fs-3'></i>
                    </div>
                </div>

                {{-- Stats Row --}}
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <small class="d-block text-muted" style="font-size: 10px; text-transform:uppercase; font-weight:700;">Misi Selesai</small>
                            <span class="fw-bold text-success">{{ $group->completed_challenges_count }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-2 text-center">
                            <small class="d-block text-muted" style="font-size: 10px; text-transform:uppercase; font-weight:700;">Anggota</small>
                            <span class="fw-bold text-dark">{{ $group->members->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        @empty
            <div class="text-center py-5 mt-5">
                <div class="avatar bg-white rounded-circle p-3 shadow-sm mx-auto mb-3">
                    <i class='bx bx-search-alt text-muted fs-1'></i>
                </div>
                <h6 class="fw-bold text-muted">Belum ada kelompok assigned.</h6>
            </div>
        @endforelse

    </div>
    <div style="height: 50px;"></div>
@endsection
