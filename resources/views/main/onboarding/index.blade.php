@extends('main.layouts.mobileMaster')

@section('title', 'Pilih Tim')

@section('content')

<div class="onboarding-overlay">
    <form action="{{ route('main.onboarding.store') }}" method="POST" class="onboarding-card" id="onboardingForm">
        @csrf

        <div class="onboarding-header">
            <div class="avatar mx-auto mb-3">
                <span class="avatar-initial rounded-circle bg-label-primary p-3 fs-2">🚀</span>
            </div>
            <h4 class="fw-bold mb-1 text-dark">Pilih Pasukanmu!</h4>
            <p class="text-muted small">Kamu harus bergabung dalam tim untuk memulai.</p>
        </div>

        <div class="onboarding-body">
            <h6 class="text-uppercase text-muted small fw-bold mb-3">Daftar Kelompok</h6>

            {{-- Loop Groups --}}
            @foreach($groups as $group)
                <label class="w-100">
                    <input type="radio" name="group_id" value="{{ $group->id }}" class="d-none"
                           data-cap="{{ $group->captain_id ? '1' : '0' }}"
                           data-cocap="{{ $group->cocaptain_id ? '1' : '0' }}"
                           onchange="selectGroup(this)">

                    <div class="group-option">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xs me-3">
                                <span class="avatar-initial rounded-circle bg-label-info"><i class='bx bx-group'></i></span>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">{{ $group->name }}</h6>
                                <small class="text-muted">{{ $group->members_count }} Anggota</small>
                            </div>
                        </div>
                        <i class='bx bx-chevron-right text-muted'></i>
                    </div>
                </label>
            @endforeach

            {{-- Role Selection (Hidden Awalnya) --}}
            <div id="roleSelection" class="role-selector">
                <h6 class="text-uppercase text-muted small fw-bold mb-3 text-center">Pilih Peran</h6>

                <div class="row g-2">
                    {{-- Captain --}}
                    <div class="col-4">
                        <label class="w-100">
                            <input type="radio" name="role" value="captain" class="d-none role-radio" id="radio-cap">
                            <div class="menu-item-card p-2 d-flex flex-column align-items-center justify-content-center h-100" style="border: 1px solid #eee;">
                                <i class='bx bxs-crown text-warning fs-3 mb-1'></i>
                                <span class="small fw-bold d-block">Captain</span>
                            </div>
                        </label>
                    </div>

                    {{-- Co-Captain --}}
                    <div class="col-4">
                        <label class="w-100">
                            <input type="radio" name="role" value="cocaptain" class="d-none role-radio" id="radio-cocap">
                            <div class="menu-item-card p-2 d-flex flex-column align-items-center justify-content-center h-100" style="border: 1px solid #eee;">
                                <i class='bx bxs-star text-secondary fs-3 mb-1'></i>
                                <span class="small fw-bold d-block">Co-Capt</span>
                            </div>
                        </label>
                    </div>

                    {{-- Member --}}
                    <div class="col-4">
                        <label class="w-100">
                            <input type="radio" name="role" value="member" class="d-none role-radio" checked>
                            <div class="menu-item-card p-2 d-flex flex-column align-items-center justify-content-center h-100" style="border: 1px solid #eee;">
                                <i class='bx bxs-user text-info fs-3 mb-1'></i>
                                <span class="small fw-bold d-block">Member</span>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-main">Masuk ke Event</button>
            </div>

        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    function selectGroup(input) {
        // Tampilkan bagian Role
        document.getElementById('roleSelection').style.display = 'block';

        // Scroll ke bawah
        document.querySelector('.onboarding-body').scrollTo({ top: 1000, behavior: 'smooth' });

        // Cek slot role
        const isCapTaken = input.getAttribute('data-cap') === '1';
        const isCoCapTaken = input.getAttribute('data-cocap') === '1';

        const radioCap = document.getElementById('radio-cap');
        const radioCoCap = document.getElementById('radio-cocap');
        const cardCap = radioCap.nextElementSibling;
        const cardCoCap = radioCoCap.nextElementSibling;

        // Reset State
        radioCap.disabled = false;
        radioCoCap.disabled = false;
        cardCap.style.opacity = '1';
        cardCoCap.style.opacity = '1';

        // Disable jika taken
        if(isCapTaken) {
            radioCap.disabled = true;
            cardCap.style.opacity = '0.5';
            if(radioCap.checked) document.querySelector('input[value="member"]').checked = true;
        }

        if(isCoCapTaken) {
            radioCoCap.disabled = true;
            cardCoCap.style.opacity = '0.5';
            if(radioCoCap.checked) document.querySelector('input[value="member"]').checked = true;
        }
    }

    // CSS Active State untuk Radio Button Role
    document.querySelectorAll('.role-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            // Reset semua border
            document.querySelectorAll('.role-radio').forEach(r => {
                r.nextElementSibling.style.borderColor = '#eee';
                r.nextElementSibling.style.backgroundColor = '#fff';
            });

            // Set active style
            if(this.checked) {
                this.nextElementSibling.style.borderColor = '#00a79d';
                this.nextElementSibling.style.backgroundColor = '#f0fdfa';
            }
        });
    });
</script>
@endpush
