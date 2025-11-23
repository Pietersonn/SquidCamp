@extends('admin.layouts.contentNavbarLayout')

@section('title', "Tambah Guideline ke Event")

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Event: {{ $event->name }} / Guidelines /</span> Tambah
</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <h5 class="mb-1 text-primary">Pilih Guideline dari Master Data</h5>
                    <small class="text-muted">Centang guideline yang ingin dimasukkan ke event ini</small>
                </div>
                <a href="{{ route('admin.events.guidelines.index', $event->id) }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.events.guidelines.store', $event->id) }}" method="POST">
                    @csrf

                    @if($guidelines->isEmpty())
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="bx bx-info-circle me-2 fs-4"></i>
                            <div>
                                Tidak ada guideline tersedia (atau semua guideline master sudah dimasukkan).
                            </div>
                        </div>
                    @else

                        {{-- Search Bar --}}
                        <div class="mb-4">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text bg-light border-0"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control bg-light border-0" id="searchInput" placeholder="Cari nama guideline, deskripsi..." aria-label="Search...">
                            </div>
                        </div>

                        <div class="table-responsive text-nowrap border rounded mb-3" style="max-height: 600px; overflow-y: auto;">
                            <table class="table table-hover sticky-header" id="guidelineTable">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th style="width: 50px; background-color: #f8f9fa; z-index: 10;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll">
                                            </div>
                                        </th>
                                        <th style="background-color: #f8f9fa;">Judul Guideline</th>
                                        <th style="background-color: #f8f9fa;">Harga (Price)</th>
                                        <th style="background-color: #f8f9fa;">Deskripsi Singkat</th>
                                        <th style="background-color: #f8f9fa;">PDF</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($guidelines as $g)
                                    <tr class="guideline-row cursor-pointer" onclick="toggleCheckbox(event, 'guideline_{{ $g->id }}')">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input guideline-item" type="checkbox" name="guideline_ids[]" value="{{ $g->id }}" id="guideline_{{ $g->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $g->title }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-info">
                                                ${{ number_format($g->price, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="text-wrap" style="max-width: 300px;">
                                            <small class="text-muted">{{ Str::limit($g->description, 60) }}</small>
                                        </td>
                                        <td>
                                            @if($g->file_pdf)
                                                <a href="{{ asset('storage/'.$g->file_pdf) }}" target="_blank" class="btn btn-sm btn-icon btn-outline-info" title="Lihat PDF" onclick="event.stopPropagation();">
                                                    <i class="bx bx-file"></i>
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div id="noResults" class="text-center py-5 d-none">
                                <i class="bx bx-search-alt text-muted fs-1 mb-2"></i>
                                <p class="text-muted mb-0">Tidak ditemukan guideline yang cocok.</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end align-items-center mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bx bx-check-double me-1"></i> Simpan Terpilih
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function toggleCheckbox(e, id) {
        if (e.target.type === 'checkbox' || e.target.tagName === 'A' || e.target.closest('a')) {
            return;
        }
        const checkbox = document.getElementById(id);
        checkbox.checked = !checkbox.checked;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('checkAll');
        if(checkAll) {
            checkAll.addEventListener('change', function() {
                const visibleRows = document.querySelectorAll('.guideline-row:not(.d-none) .guideline-item');
                visibleRows.forEach(item => {
                    item.checked = this.checked;
                });
            });
        }

        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('.guideline-row');
        const noResults = document.getElementById('noResults');

        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                let visibleCount = 0;

                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    if (rowText.includes(searchTerm)) {
                        row.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        row.classList.add('d-none');
                    }
                });

                if (visibleCount === 0) {
                    noResults.classList.remove('d-none');
                } else {
                    noResults.classList.add('d-none');
                }
            });
        }
    });
</script>
@endsection
