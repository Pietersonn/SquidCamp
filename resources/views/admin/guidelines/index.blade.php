@extends('admin.layouts.contentNavbarLayout')
@section('title', 'Guideline Management')

@section('content')
<div class="container-xxl">

    {{-- Alert Feedback --}}
    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Guideline Management</h5>
            <a href="{{ route('admin.guidelines.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah Guideline
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul Guideline</th>
                        <th>Harga (Price)</th>
                        <th>PDF</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($guidelines as $g)
                    <tr id="row-{{ $g->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $g->title }}</strong></td>

                        {{-- Format Harga Dolar dengan Badge Biru (agar beda dikit dgn challenge) --}}
                        <td>
                            <span class="badge bg-label-primary">
                                ${{ number_format($g->price, 0, ',', '.') }}
                            </span>
                        </td>

                        <td>
                            @if ($g->file_pdf)
                                <a href="{{ asset('storage/'.$g->file_pdf) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-icon btn-outline-info"
                                   title="Lihat PDF">
                                    <i class="bx bx-file"></i>
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.guidelines.edit', $g->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bx bx-edit-alt"></i>
                                </a>

                                <button class="btn btn-sm btn-danger btn-delete"
                                        data-id="{{ $g->id }}"
                                        data-url="{{ route('admin.guidelines.destroy', $g->id) }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada data guideline.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{-- Pagination Links --}}
            {{ $guidelines->links() }}
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrf = '{{ csrf_token() }}';

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const url = this.dataset.url;

            Swal.fire({
                title: 'Hapus guideline?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-danger me-3',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('row-' + id).remove();
                            Swal.fire('Terhapus!', data.message, 'success');
                        } else {
                             Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                        }
                    })
                    .catch(err => {
                         console.error(err);
                         Swal.fire('Error!', 'Terjadi kesalahan jaringan.', 'error');
                    });
                }
            });
        });
    });
});
</script>
@endsection
