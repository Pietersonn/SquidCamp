@extends('main.layouts.mobileMaster')

@section('title', 'Tim Saya')

@section('styles')
<style>
    /* Header Style */
    .team-header {
        background: white;
        padding: 30px 20px;
        text-align: center;
        border-bottom-left-radius: 30px;
        border-bottom-right-radius: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    .team-avatar-large {
        width: 80px; height: 80px;
        background: #e0f2f1; color: #00a79d;
        border-radius: 20px;
        font-size: 2.5rem;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 15px auto;
        box-shadow: 0 8px 16px rgba(0, 167, 157, 0.2);
    }

    /* Member Card */
    .member-card {
        background: white;
        border-radius: 16px;
        padding: 15px;
        margin-bottom: 10px;
        display: flex; align-items: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        border: 1px solid transparent;
        transition: all 0.2s ease-in-out;
    }

    .member-avatar {
        width: 45px; height: 45px;
        border-radius: 50%; object-fit: cover;
        margin-right: 15px; border: 2px solid #f0f0f0;
    }

    /* Role Badges */
    .badge-role {
        font-size: 0.65rem;
        padding: 4px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        font-weight: 700;
    }
    .role-captain { background: #fff7cd; color: #ff9f43; }
    .role-cocaptain { background: #e7f1ff; color: #007bff; }
    .role-member { background: #f2f2f2; color: #697a8d; }

    /* Highlight Me */
    .is-me {
        border-color: #00a79d;
        background-color: #f0fdfa;
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0, 167, 157, 0.15);
    }
</style>
@endsection

@section('content')

{{-- HEADER --}}
<div class="team-header">
    <div class="team-avatar-large">
        <i class='bx bx-group'></i>
    </div>
    <h4 class="fw-bold text-dark mb-1">{{ $group->name }}</h4>
    <p class="text-muted small mb-3">
        Mentor: <span class="fw-bold text-primary">{{ $group->mentor->name ?? 'Belum Ada' }}</span>
    </p>

    <div class="d-flex justify-content-center gap-3">
        <div class="text-center px-3 py-2 bg-light rounded-3">
            <small class="d-block text-muted fw-bold" style="font-size: 0.65rem;">TOTAL ASET</small>
            <span class="fw-bold text-dark fs-5">$ {{ number_format($group->squid_dollar) }}</span>
        </div>
        <div class="text-center px-3 py-2 bg-light rounded-3">
            <small class="d-block text-muted fw-bold" style="font-size: 0.65rem;">ANGGOTA</small>
            <span class="fw-bold text-dark fs-5">{{ $group->members->count() }}</span>
        </div>
    </div>
</div>

{{-- MEMBER LIST --}}
<div class="container px-3 pb-5">
    <h6 class="text-muted small fw-bold mb-3 ms-1">DAFTAR ANGGOTA</h6>

    @foreach($group->members as $member)
        @php
            // Determine Role
            $isCaptain = $group->captain_id == $member->user_id;
            $isCoCaptain = $group->cocaptain_id == $member->user_id;

            // Check if this member is the logged-in user
            $isMe = Auth::id() == $member->user_id;
        @endphp

        <div class="member-card {{ $isMe ? 'is-me' : '' }}">
            <div class="flex-grow-1">
                <h6 class="mb-0 fw-bold text-dark">
                    {{ $member->user->name }}
                    @if($isMe)
                        <small class="text-primary ms-1" style="font-size: 0.7rem;">(Saya)</small>
                    @endif
                </h6>
                <small class="text-muted" style="font-size: 0.75rem;">{{ $member->user->email }}</small>
            </div>

            <div>
                @if($isCaptain)
                    <span class="badge badge-role role-captain">
                        <i class='bx bxs-crown me-1'></i> Captain
                    </span>
                @elseif($isCoCaptain)
                    <span class="badge badge-role role-cocaptain">
                        <i class='bx bxs-star me-1'></i> Co-Captain
                    </span>
                @else
                    <span class="badge badge-role role-member">Member</span>
                @endif
            </div>
        </div>
    @endforeach
</div>

@endsection
