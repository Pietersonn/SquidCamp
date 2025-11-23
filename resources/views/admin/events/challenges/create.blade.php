 @extends('admin.layouts.contentNavbarLayout')

@section('title', "Tambah Challenge ke Event")

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Event: {{ $event->name }} / Challenges /</span> Tambah
</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <h5 class="mb-1 text-primary">Pilih Challenge dari Master Data</h5>
                    <small class="text-muted">Centang challenge yang ingin dimasukkan ke event ini</small>
                </div>
                <a href="{{ route('admin.events.challenges.index', $event->id) }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.events.challenges.store', $event->id) }}" method="POST">
                    @csrf

                    @if($challenges->isEmpty())
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="bx bx-info-circle me-2 fs-4"></i>
                            <div>
                                Tidak ada challenge tersedia (atau semua challenge master sudah dimasukkan ke event ini).
                            </div>
                        </div>
                    @else

                        {{-- Input Pencarian --}}
                        <div class="mb-4">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text bg-light border-0"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control bg-light border-0" id="searchInput" placeholder="Cari nama challenge, deskripsi, atau nominal reward..." aria-label="Search...">
                            </div>
                        </div>

                        <div class="table-responsive text-nowrap border rounded mb-3" style="max-height: 600px; overflow-y: auto;">
                            <table class="table table-hover sticky-header" id="challengeTable">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th style="width: 50px; background-color: #f8f9fa; z-index: 10;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll">
                                            </div>
                                        </th>
                                        <th style="background-color: #f8f9fa;">Nama Challenge</th>
                                        <th style="background-color: #f8f9fa;">Reward / Kategori</th>
                                        <th style="background-color: #f8f9fa;">Deskripsi Singkat</th>
                                        <th style="background-color: #f8f9fa;">PDF</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($challenges as $c)
                                    <tr class="challenge-row cursor-pointer" onclick="toggleCheckbox(event, 'challenge_{{ $c->id }}')">
                                        <td>
                                            <div class="form-check">
                                                {{-- Checkbox selection --}}
                                                <input class="form-check-input challenge-item" type="checkbox" name="challenge_ids[]" value="{{ $c->id }}" id="challenge_{{ $c->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $c->nama }}</strong>
                                        </td>
                                        <td>
                                            {{-- Format sesuai index master: Badge Hijau & Format Dolar --}}
                                            <span class="badge bg-label-success">
                                                ${{ number_format($c->kategori, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="text-wrap" style="max-width: 300px;">
                                            <small class="text-muted">{{ Str::limit($c->deskripsi, 60) }}</small>
                                        </td>
                                        <td>
                                            {{-- Tombol Preview PDF sesuai index master --}}
                                            @if($c->file_pdf)
                                                {{-- Stop propagation agar saat klik tombol PDF, checkbox baris tidak ikut tercentang --}}
                                                <a href="{{ asset('storage/'.$c->file_pdf) }}" target="_blank" class="btn btn-sm btn-icon btn-outline-info" title="Lihat PDF" onclick="event.stopPropagation();">
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

                            {{-- Pesan jika hasil pencarian tidak ditemukan --}}
                            <div id="noResults" class="text-center py-5 d-none">
                                <i class="bx bx-search-alt text-muted fs-1 mb-2"></i>
                                <p class="text-muted mb-0">Tidak ditemukan challenge yang cocok.</p>
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
    // Fungsi agar bisa klik di mana saja pada baris untuk mencentang
    function toggleCheckbox(e, id) {
        // Jika yang diklik adalah checkbox itu sendiri atau link/tombol, jangan lakukan apa-apa (biar default action jalan)
        if (e.target.type === 'checkbox' || e.target.tagName === 'A' || e.target.closest('a')) {
            return;
        }
        const checkbox = document.getElementById(id);
        checkbox.checked = !checkbox.checked;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Fitur Check All
        const checkAll = document.getElementById('checkAll');

        if(checkAll) {
            checkAll.addEventListener('change', function() {
                // Hanya centang item yang terlihat (tidak disembunyikan oleh filter)
                const visibleRows = document.querySelectorAll('.challenge-row:not(.d-none) .challenge-item');
                visibleRows.forEach(item => {
                    item.checked = this.checked;
                });
            });
        }

        // Fitur Pencarian Real-time
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('.challenge-row');
        const noResults = document.getElementById('noResults');

        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                let visibleCount = 0;

                tableRows.forEach(row => {
                    // Cari teks di seluruh baris (Nama, Reward, Deskripsi)
                    const rowText = row.textContent.toLowerCase();

                    if (rowText.includes(searchTerm)) {
                        row.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        row.classList.add('d-none');
                    }
                });

                // Tampilkan pesan jika tidak ada hasil
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
