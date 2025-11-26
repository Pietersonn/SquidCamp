@extends('admin.layouts.contentNavbarLayout')

@section('title', "Tambah Case - $event->name")

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-light: #e0f2f1;
        --squid-dark: #00796b;
    }

    /* --- GOKIL SELECTION CARD --- */
    .case-select-card {
        border: 2px solid transparent;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .case-select-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 167, 157, 0.15);
        border-color: rgba(0, 167, 157, 0.3);
    }

    /* Selected State */
    .case-select-card.selected {
        border-color: var(--squid-primary);
        background-color: #f0fdfa;
        box-shadow: 0 8px 25px rgba(0, 167, 157, 0.25);
    }

    /* Indicator Centang */
    .check-indicator {
        position: absolute;
        top: 0;
        right: 0;
        background-color: var(--squid-primary);
        color: white;
        width: 45px;
        height: 45px;
        border-bottom-left-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: translate(100%, -100%);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: 10;
        box-shadow: -3px 3px 10px rgba(0,0,0,0.1);
    }

    .case-select-card.selected .check-indicator {
        opacity: 1;
        transform: translate(0, 0);
    }

    .check-indicator i {
        font-size: 1.8rem;
        margin-bottom: 5px;
        margin-left: 5px;
    }

    /* Header Visual Kecil */
    .card-visual-header {
        height: 90px;
        background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--squid-primary);
        font-size: 2.5rem;
        transition: 0.3s;
    }

    .case-select-card.selected .card-visual-header {
        background: linear-gradient(135deg, var(--squid-primary) 0%, #00796b 100%);
        color: white;
    }

    /* Difficulty Badge */
    .difficulty-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #fff;
        color: var(--squid-primary);
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        z-index: 5;
        text-transform: uppercase;
    }

    /* Filter Buttons */
    .btn-filter {
        border: 1px solid #d9dee3;
        background: #fff;
        color: #566a7f;
        border-radius: 50px;
        padding: 5px 15px;
        font-size: 0.85rem;
        transition: 0.2s;
    }
    .btn-filter:hover, .btn-filter.active {
        background-color: var(--squid-primary);
        border-color: var(--squid-primary);
        color: #fff;
        box-shadow: 0 4px 10px rgba(0, 167, 157, 0.3);
    }

    /* Tombol Custom */
    .btn-squid {
        background-color: var(--squid-primary);
        border-color: var(--squid-primary);
        color: white;
        transition: 0.3s;
    }
    .btn-squid:hover {
        background-color: #008f85;
        box-shadow: 0 4px 15px rgba(0, 167, 157, 0.4);
        color: white;
    }

    .btn-view-pdf {
        font-size: 0.75rem;
        padding: 5px 10px;
        border-radius: 50px;
        z-index: 20;
        position: relative;
        color: var(--squid-primary);
        border-color: var(--squid-primary);
    }
    .btn-view-pdf:hover {
        background-color: var(--squid-primary);
        color: white;
    }
</style>
@endsection

@section('content')

<form action="{{ route('admin.events.cases.store', $event->id) }}" method="POST">
    @csrf

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--squid-primary);">Tambah Case</h4>
            <span class="text-muted">Event: {{ $event->name }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.events.cases.index', $event->id) }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
            <button type="submit" class="btn btn-squid shadow-sm px-4" id="btn-save" disabled>
                <i class="bx bx-plus me-1"></i> Tambahkan (<span id="count-display">0</span>)
            </button>
        </div>
    </div>

    {{-- Minimalist Filter Section --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-md-row gap-3 align-items-center justify-content-between">

                {{-- Search Input --}}
                <div class="position-relative flex-grow-1 w-100" style="max-width: 400px;">
                    <i class="bx bx-search position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index: 10;"></i>
                    <input type="text" id="searchInput" class="form-control rounded-pill border-0 shadow-none"
                           placeholder="Cari judul case..."
                           style="padding-left: 3.5rem; background-color: #fff;">
                </div>

                {{-- Difficulty Filters --}}
                <div class="d-flex gap-2 overflow-auto" id="difficultyFilters">
                    <button type="button" class="btn btn-filter active" data-filter="all">Semua</button>
                    <button type="button" class="btn btn-filter" data-filter="Easy">Easy</button>
                    <button type="button" class="btn btn-filter" data-filter="Medium">Medium</button>
                    <button type="button" class="btn btn-filter" data-filter="Hard">Hard</button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-transparent">
        @if($cases->isEmpty())
            <div class="text-center py-5">
                <div class="avatar avatar-xl bg-label-warning rounded-circle mx-auto mb-3">
                    <i class="bx bx-briefcase-alt-2 fs-1"></i>
                </div>
                <h5 class="mb-2 text-muted">Tidak Ada Case Tersedia</h5>
                <p class="text-muted mb-4">Semua case dari Master Data sudah ditambahkan ke event ini.</p>
                <a href="{{ route('admin.cases.create') }}" class="btn btn-outline-primary">
                    <i class="bx bx-plus me-1"></i> Buat Master Case Baru
                </a>
            </div>
        @else
            <div class="row g-4" id="caseList">
                @foreach($cases as $c)
                    {{-- Item Case dengan data-difficulty --}}
                    <div class="col-md-6 col-lg-4 col-xl-3 case-item" data-difficulty="{{ $c->difficulty }}">
                        <div class="case-select-card h-100" onclick="toggleSelection('case_{{ $c->id }}')">

                            {{-- Hidden Checkbox --}}
                            <input type="checkbox" class="d-none case-checkbox"
                                   name="case_ids[]"
                                   value="{{ $c->id }}"
                                   id="case_{{ $c->id }}">

                            {{-- Check Indicator --}}
                            <div class="check-indicator">
                                <i class='bx bx-check'></i>
                            </div>

                            {{-- Difficulty Badge --}}
                            <div class="difficulty-badge">
                                {{ $c->difficulty }}
                            </div>

                            {{-- Visual Header --}}
                            <div class="card-visual-header">
                                @if($c->file_pdf)
                                    <i class="bx bxs-file-pdf"></i>
                                @else
                                    <i class="bx bx-briefcase-alt-2"></i>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column p-3">
                                <h6 class="card-title fw-bold mb-2 text-dark case-title">{{ $c->title }}</h6>

                                <p class="card-text text-muted small mb-3 flex-grow-1 case-desc" style="line-height: 1.5;">
                                    {{ Str::limit($c->description, 70) ?? 'Tidak ada deskripsi.' }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top border-light">
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        Dokumen
                                    </small>

                                    @if($c->file_pdf)
                                        {{-- Stop Propagation --}}
                                        <a href="{{ asset('storage/'.$c->file_pdf) }}" target="_blank"
                                           class="btn btn-outline-info btn-view-pdf"
                                           onclick="event.stopPropagation()">
                                            <i class="bx bx-show me-1"></i> Preview
                                        </a>
                                    @else
                                        <span class="badge bg-label-secondary" style="font-size: 0.65rem;">No File</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- No Results Message --}}
            <div id="noResults" class="text-center py-5 d-none">
                <i class="bx bx-search-alt text-muted fs-1 mb-3"></i>
                <h5 class="text-muted">Case tidak ditemukan.</h5>
            </div>
        @endif
    </div>
</form>

<script>
    // 1. Logic Seleksi Kartu
    function toggleSelection(id) {
        const checkbox = document.getElementById(id);
        if(!checkbox) return;

        const card = checkbox.closest('.case-select-card');
        checkbox.checked = !checkbox.checked;

        if(checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
        updateCounter();
    }

    function updateCounter() {
        const count = document.querySelectorAll('.case-checkbox:checked').length;
        const display = document.getElementById('count-display');
        const btnSave = document.getElementById('btn-save');

        if(display) display.innerText = count;

        if(btnSave) {
            if(count > 0) {
                btnSave.removeAttribute('disabled');
                btnSave.innerHTML = `<i class="bx bx-check-double me-1"></i> Tambahkan (${count})`;
            } else {
                btnSave.setAttribute('disabled', true);
                btnSave.innerHTML = `<i class="bx bx-plus me-1"></i> Tambahkan (0)`;
            }
        }
    }

    // 2. Logic Filtering (Search + Difficulty) + DEBOUNCE
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterButtons = document.querySelectorAll('.btn-filter');
        const list = document.getElementById('caseList');
        const noResults = document.getElementById('noResults');

        let currentSearch = '';
        let currentDifficulty = 'all';
        let debounceTimer;

        // Helper: Apply Filter
        function applyFilters() {
            if(!list) return;

            const items = list.querySelectorAll('.case-item');
            let visibleCount = 0;

            items.forEach(item => {
                const title = item.querySelector('.case-title').textContent.toLowerCase();
                const desc = item.querySelector('.case-desc').textContent.toLowerCase();
                const itemDiff = item.getAttribute('data-difficulty');

                // Logic Pencarian Teks
                const matchesSearch = title.includes(currentSearch) || desc.includes(currentSearch);

                // Logic Filter Difficulty
                const matchesDifficulty = (currentDifficulty === 'all') || (itemDiff === currentDifficulty);

                if(matchesSearch && matchesDifficulty) {
                    item.classList.remove('d-none');
                    visibleCount++;
                } else {
                    item.classList.add('d-none');
                }
            });

            // Show/Hide No Results
            if(visibleCount === 0) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        }

        // Event: Search Input (Debounced 500ms)
        if(searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    currentSearch = this.value.toLowerCase();
                    applyFilters();
                }, 500);
            });
        }

        // Event: Difficulty Filter Buttons
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentDifficulty = this.getAttribute('data-filter');
                applyFilters();
            });
        });
    });
</script>

@endsection
