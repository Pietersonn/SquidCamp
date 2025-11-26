@extends('admin.layouts.contentNavbarLayout')

@section('title', "Tambah Guideline - $event->name")

@section('styles')
<style>
    :root {
        --squid-primary: #00a79d;
        --squid-light: #e0f2f1;
    }

    /* --- GOKIL SELECTION CARD --- */
    .guideline-select-card {
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

    .guideline-select-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 167, 157, 0.15);
        border-color: rgba(0, 167, 157, 0.3);
    }

    /* Selected State */
    .guideline-select-card.selected {
        border-color: var(--squid-primary);
        background-color: #f0fdfa;
        box-shadow: 0 8px 25px rgba(0, 167, 157, 0.25);
    }

    /* Indicator Centang Gokil */
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

    .guideline-select-card.selected .check-indicator {
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
        height: 80px;
        background: linear-gradient(135deg, var(--squid-light) 0%, #b2dfdb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--squid-primary);
        font-size: 2.5rem;
    }

    .guideline-select-card.selected .card-visual-header {
        background: linear-gradient(135deg, var(--squid-primary) 0%, #00796b 100%);
        color: white;
    }

    /* Price Badge */
    .price-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #fff;
        color: var(--squid-primary);
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        z-index: 5;
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
        z-index: 20; /* Supaya bisa diklik tanpa trigger select */
        position: relative;
    }
</style>
@endsection

@section('content')

<form action="{{ route('admin.events.guidelines.store', $event->id) }}" method="POST">
    @csrf

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--squid-primary);">Tambah Guideline</h4>
            <span class="text-muted">Event: {{ $event->name }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.events.guidelines.index', $event->id) }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
            <button type="submit" class="btn btn-squid shadow-sm px-4" id="btn-save" disabled>
                <i class="bx bx-plus me-1"></i> Tambahkan (<span id="count-display">0</span>)
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-none bg-transparent">

        @if($guidelines->isEmpty())
            <div class="text-center py-5">
                <div class="avatar avatar-xl bg-label-info rounded-circle mx-auto mb-3">
                    <i class="bx bx-book-open fs-1"></i>
                </div>
                <h5 class="mb-2 text-muted">Tidak Ada Guideline Tersedia</h5>
                <p class="text-muted mb-4">Semua guideline dari Master Data sudah ditambahkan ke event ini, atau belum ada data master.</p>
                <a href="{{ route('admin.guidelines.create') }}" class="btn btn-outline-primary">
                    <i class="bx bx-plus me-1"></i> Buat Master Guideline Baru
                </a>
            </div>
        @else
            <div class="row g-4" id="guidelineList">
                @foreach($guidelines as $g)
                    <div class="col-md-6 col-lg-4 col-xl-3 guideline-item">
                        <div class="guideline-select-card h-100" onclick="toggleSelection('guideline_{{ $g->id }}')">

                            {{-- Hidden Checkbox --}}
                            <input type="checkbox" class="d-none guideline-checkbox"
                                   name="guideline_ids[]"
                                   value="{{ $g->id }}"
                                   id="guideline_{{ $g->id }}">

                            {{-- Check Indicator --}}
                            <div class="check-indicator">
                                <i class='bx bx-check'></i>
                            </div>

                            {{-- Visual Header --}}
                            <div class="card-visual-header">
                                <i class="bx {{ $g->file_pdf ? 'bxs-file-pdf' : 'bx-book' }}"></i>
                            </div>

                            {{-- Price Badge --}}
                            <div class="price-badge">
                                ${{ number_format($g->price, 0, ',', '.') }}
                            </div>

                            <div class="card-body d-flex flex-column p-3">
                                <h6 class="card-title fw-bold mb-2 text-dark guideline-title">{{ $g->title }}</h6>

                                <p class="card-text text-muted small mb-3 flex-grow-1 guideline-desc" style="line-height: 1.5;">
                                    {{ Str::limit($g->description, 70) ?? 'Tidak ada deskripsi.' }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top border-light">
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        {{ $g->created_at->format('d M Y') }}
                                    </small>

                                    @if($g->file_pdf)
                                        {{-- Stop Propagation agar tidak men-trigger centang saat klik PDF --}}
                                        <a href="{{ asset('storage/'.$g->file_pdf) }}" target="_blank"
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
                <h5 class="text-muted">Guideline tidak ditemukan.</h5>
            </div>
        @endif
    </div>
</form>

<script>
    function toggleSelection(id) {
        const checkbox = document.getElementById(id);
        if(!checkbox) return;

        const card = checkbox.closest('.guideline-select-card');

        checkbox.checked = !checkbox.checked;

        if(checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
        updateCounter();
    }

    function updateCounter() {
        const count = document.querySelectorAll('.guideline-checkbox:checked').length;
        const display = document.getElementById('count-display');
        const btnSave = document.getElementById('btn-save');

        if(display) display.innerText = count;

        // Enable/Disable button based on selection
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

    // Live Search Function
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const guidelineList = document.getElementById('guidelineList');
        const noResults = document.getElementById('noResults');

        if(searchInput && guidelineList) {
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                const items = guidelineList.querySelectorAll('.guideline-item');
                let visibleCount = 0;

                items.forEach(item => {
                    const title = item.querySelector('.guideline-title').textContent.toLowerCase();
                    const desc = item.querySelector('.guideline-desc').textContent.toLowerCase();

                    if(title.includes(term) || desc.includes(term)) {
                        item.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        item.classList.add('d-none');
                    }
                });

                if(visibleCount === 0) {
                    noResults.classList.remove('d-none');
                } else {
                    noResults.classList.add('d-none');
                }
            });
        }
    });
</script>

@endsection
