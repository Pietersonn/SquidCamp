@extends('admin.layouts.contentNavbarLayout')
@section('title', 'Guidelines')

@section('content')
<div class="container-xxl">

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Guidelines</h5>
            <a href="{{ route('admin.guidelines.create') }}" class="btn btn-primary">
                <i class="icon-base bx bx-plus"></i> Tambah Guideline
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($guidelines as $g)
                        <tr id="guideline-row-{{ $g->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $g->title }}</td>
                            <td>Rp {{ number_format($g->price) }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.guidelines.edit', $g->id) }}">
                                            <i class="icon-base bx bx-edit-alt me-2"></i>Edit
                                        </a>

                                        <a href="javascript:void(0);"
                                           class="dropdown-item text-danger btn-delete"
                                           data-id="{{ $g->id }}"
                                           data-url="{{ route('admin.guidelines.destroy', $g->id) }}">
                                            <i class="bx bx-trash me-2"></i>Hapus
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $guidelines->links() }}
        </div>

    </div>

</div>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;

            Swal.fire({
                title: 'Hapus guideline?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                backdrop: false
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/guidelines/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const row = document.getElementById('guideline-row-' + id);
                            if (row) row.remove();
                            Swal.fire('Terhapus!', data.message, 'success');
                        } else {
                            Swal.fire('Gagal!', data.message, 'error');
                        }
                    })
                    .catch(() => Swal.fire('Gagal!', 'Kesalahan server.', 'error'));
                }
            });
        });
    });
});
</script>
@endsection
