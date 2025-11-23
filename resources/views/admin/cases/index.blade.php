@extends('admin.layouts.contentNavbarLayout')
@section('title', 'Case Management')

@section('content')
<div class="container-xxl">

    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Case Management</h5>
            <a href="{{ route('admin.cases.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah Case
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul Case</th>
                        <th>Tingkat Kesulitan</th>
                        <th>PDF</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($cases as $c)
                    <tr id="row-{{ $c->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $c->title }}</strong></td>

                        <td>
                            @php
                                $badgeClass = match($c->difficulty) {
                                    'Easy' => 'bg-label-success',
                                    'Medium' => 'bg-label-warning',
                                    'Hard' => 'bg-label-danger',
                                    default => 'bg-label-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $c->difficulty }}
                            </span>
                        </td>

                        <td>
                            @if ($c->file_pdf)
                                <a href="{{ asset('storage/'.$c->file_pdf) }}"
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
                                <a href="{{ route('admin.cases.edit', $c->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bx bx-edit-alt"></i>
                                </a>

                                <button class="btn btn-sm btn-danger btn-delete"
                                        data-id="{{ $c->id }}"
                                        data-url="{{ route('admin.cases.destroy', $c->id) }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada data Case.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $cases->links() }}
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
                title: 'Hapus case?',
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
