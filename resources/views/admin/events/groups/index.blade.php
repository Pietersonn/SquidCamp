@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Groups - $event->name")

@section('styles')
<style>
    .card-hover-animation {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        z-index: 1;
    }
    .card-hover-animation:hover {
        transform: scale(1.03);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        z-index: 10;
    }
    .member-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .member-list li {
        padding: 5px 0;
        border-bottom: 1px dashed #eee;
        display: flex;
        align-items: center;
    }
    .member-list li:last-child {
        border-bottom: none;
    }
    .member-badge {
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 0.75rem;
        margin-right: 8px;
    }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <span class="text-muted fw-light">Event: {{ $event->name }} /</span> Groups
    </h4>
    {{-- Jika ada fitur generate group otomatis, tombolnya bisa ditaruh sini --}}
    {{-- <button class="btn btn-primary"><i class="bx bx-shuffle me-1"></i> Generate Group</button> --}}
</div>

@if(session('success'))
<div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

<div class="row">
  @forelse ($groups as $group)
    <div class="col-md-6 col-lg-4 mb-4">
      <div class="card h-100 card-hover-animation">

        {{-- Header Card: Nama Kelompok --}}
        <div class="card-header bg-label-primary d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-primary card-title">
                <i class="bx bx-group me-2"></i> {{ $group->name }}
            </h5>
            <span class="badge bg-white text-primary">{{ $group->members->count() }} Anggota</span>
        </div>

        <div class="card-body mt-3">
            <h6 class="text-muted small text-uppercase fw-bold mb-3">Daftar Peserta</h6>

            @if($group->members->isEmpty())
                <div class="text-center py-3 text-muted">
                    <small>Belum ada anggota di kelompok ini.</small>
                </div>
            @else
                <ul class="member-list">
                    @foreach($group->members as $member)
                        <li>
                            {{-- Avatar/Initial (Optional) --}}
                            <span class="member-badge bg-label-secondary">
                                {{ substr($member->name, 0, 1) }}
                            </span>

                            <div>
                                <span class="fw-semibold text-dark">{{ $member->name }}</span>
                                @if(!empty($member->is_leader))
                                    <span class="badge bg-warning ms-1" style="font-size: 0.6rem;">Leader</span>
                                @endif
                                <br>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ $member->email ?? '-' }}</small>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Footer Card: Actions --}}
        <div class="card-footer border-top d-flex justify-content-end bg-light p-3 rounded-bottom">
            <form action="{{ route('admin.events.groups.destroy', ['event' => $event->id, 'group' => $group->id]) }}" method="POST" onsubmit="return confirm('Yakin hapus kelompok ini beserta anggotanya?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bx bx-trash me-1"></i> Hapus Kelompok
                </button>
            </form>
        </div>

      </div>
    </div>
  @empty
    <div class="col-12">
        <div class="card text-center">
            <div class="card-body py-5">
                <div class="mb-3">
                    <div class="badge bg-label-secondary p-3 rounded-circle">
                        <i class="bx bx-group" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <h4>Belum ada Kelompok</h4>
                <p class="text-muted">Event ini belum memiliki kelompok peserta.</p>
            </div>
        </div>
    </div>
  @endforelse
</div>

@endsection
