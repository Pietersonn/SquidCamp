@extends('main.layouts.mobileMaster')

@section('title', 'Pilih Tim')

@section('styles')
<style>
    /* Header Sticky */
    .header-onboarding {
        position: sticky; top: 0; z-index: 50;
        background: rgba(255,255,255,0.95); backdrop-filter: blur(5px);
        padding: 20px 20px 10px 20px;
        border-bottom-left-radius: 25px; border-bottom-right-radius: 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        text-align: center;
    }

    /* Group Card List */
    .group-label { cursor: pointer; width: 100%; display: block; margin-bottom: 12px; }
    .group-card {
        background: white; border-radius: 16px; padding: 15px;
        display: flex; align-items: center;
        border: 2px solid transparent;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        transition: all 0.2s;
    }
    .group-input:checked + .group-card {
        border-color: #00a79d; background: #f0fdfa;
        transform: scale(1.02);
    }
    .group-icon {
        width: 45px; height: 45px; background: #e7e7ff; color: #696cff;
        border-radius: 12px; display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; margin-right: 15px;
    }
    .group-input:checked + .group-card .group-icon { background: #00a79d; color: white; }

    /* Bottom Sheet (Role) */
    #roleSheet {
        position: fixed; bottom: -100%; left: 0; width: 100%;
        background: white; border-top-left-radius: 30px; border-top-right-radius: 30px;
        padding: 30px 20px; box-shadow: 0 -10px 50px rgba(0,0,0,0.15);
        z-index: 100; transition: bottom 0.4s cubic-bezier(0.33, 1, 0.68, 1);
    }
    #roleSheet.show { bottom: 0; }

    .role-box-label { cursor: pointer; width: 100%; }
    .role-box {
        background: #f8f9fa; border: 2px solid transparent;
        border-radius: 15px; padding: 15px 5px; text-align: center;
        transition: 0.2s;
    }
    .role-input:checked + .role-box {
        background: #e0f2f1; border-color: #00a79d; color: #00a79d;
    }
    .role-input:disabled + .role-box {
        opacity: 0.4; filter: grayscale(1); cursor: not-allowed;
    }

    .btn-confirm {
        background: #00a79d; color: white; border: none;
        width: 100%; padding: 15px; border-radius: 15px;
        font-weight: bold; font-size: 1rem; margin-top: 20px;
        box-shadow: 0 5px 15px rgba(0, 167, 157, 0.3);
    }
</style>
@endsection

@section('content')

<div class="header-onboarding">
    <h4 class="fw-bold m-0 text-dark">Pilih Pasukan</h4>
    <p class="text-muted small m-0">Gabung ke tim untuk memulai event.</p>
</div>

<form action="{{ route('main.onboarding.store', $event->id) }}" method="POST">
    @csrf
    <div class="container px-3 pt-3 pb-5" style="margin-bottom: 200px;">
        @foreach($groups as $group)
            <label class="group-label">
                <input type="radio" name="group_id" value="{{ $group->id }}" class="d-none group-input"
                       data-cap="{{ $group->captain_id ? '1' : '0' }}"
                       data-cocap="{{ $group->cocaptain_id ? '1' : '0' }}"
                       onchange="openRoleSheet(this)">

                <div class="group-card">
                    <div class="group-icon"><i class='bx bx-group'></i></div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ $group->name }}</h6>
                        <small class="text-muted">{{ $group->members_count }} Anggota</small>
                    </div>
                    <i class='bx bxs-check-circle ms-auto fs-4 text-primary opacity-0 check-mark'></i>
                </div>
            </label>
        @endforeach
    </div>

    {{-- Bottom Sheet --}}
    <div id="roleSheet">
        <div class="text-center mb-4">
            <div style="width: 50px; height: 5px; background: #e0e0e0; border-radius: 10px; margin: 0 auto;"></div>
        </div>
        <h5 class="fw-bold text-center mb-4">Pilih Peran</h5>

        <div class="row g-2">
            <div class="col-4">
                <label class="role-box-label">
                    <input type="radio" name="role" value="captain" class="d-none role-input" id="radio-cap">
                    <div class="role-box">
                        <i class='bx bxs-crown fs-2 mb-1 text-warning'></i>
                        <span class="d-block small fw-bold">Captain</span>
                    </div>
                </label>
            </div>
            <div class="col-4">
                <label class="role-box-label">
                    <input type="radio" name="role" value="cocaptain" class="d-none role-input" id="radio-cocap">
                    <div class="role-box">
                        <i class='bx bxs-star fs-2 mb-1 text-info'></i>
                        <span class="d-block small fw-bold">Co-Capt</span>
                    </div>
                </label>
            </div>
            <div class="col-4">
                <label class="role-box-label">
                    <input type="radio" name="role" value="member" class="d-none role-input" checked>
                    <div class="role-box">
                        <i class='bx bxs-user fs-2 mb-1 text-secondary'></i>
                        <span class="d-block small fw-bold">Member</span>
                    </div>
                </label>
            </div>
        </div>

        <button type="submit" class="btn-confirm">Konfirmasi & Masuk</button>
    </div>
</form>

@endsection

@push('scripts')
<script>
    function openRoleSheet(input) {
        // Tampilkan Sheet
        document.getElementById('roleSheet').classList.add('show');

        // Cek Role Availability
        const isCapTaken = input.getAttribute('data-cap') === '1';
        const isCoCapTaken = input.getAttribute('data-cocap') === '1';
        const radioCap = document.getElementById('radio-cap');
        const radioCoCap = document.getElementById('radio-cocap');

        // Reset
        radioCap.disabled = false;
        radioCoCap.disabled = false;

        // Disable if taken
        if(isCapTaken) {
            radioCap.disabled = true;
            if(radioCap.checked) document.querySelector('input[value="member"]').checked = true;
        }
        if(isCoCapTaken) {
            radioCoCap.disabled = true;
            if(radioCoCap.checked) document.querySelector('input[value="member"]').checked = true;
        }
    }
</script>
@endpush
