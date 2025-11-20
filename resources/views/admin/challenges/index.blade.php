@extends('admin.layouts.contentNavbarLayout')
@section('title', 'Challenge Management')

@section('content')
<div class="container-xxl">

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Challenge Management</h5>
            <a href="{{ route('admin.challenges.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah Challenge
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>PDF</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($challenges as $c)
                    <tr id="row-{{ $c->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $c->nama }}</td>
                        <td>{{ $c->kategori }}</td>
                        <td>
                            @if ($c->file_pdf)
                                <a href="{{ asset('storage/'.$c->file_pdf) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-info">
                                    <i class="bx bx-file"></i> Lihat PDF
                                </a>
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.challenges.edit', $c->id) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <button class="btn btn-sm btn-danger btn-delete"
                                    data-id="{{ $c->id }}"
                                    data-url="{{ route('admin.challenges.destroy', $c->id) }}">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $challenges->links() }}
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

            Swal.fire({
                title: 'Hapus challenge?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(this.dataset.url, {
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
                        }
                    });
                }
            });
        });
    });
});
</script>
@endsection
