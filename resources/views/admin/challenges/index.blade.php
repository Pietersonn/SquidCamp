@extends('admin.layouts.contentNavbarLayout')
@section('title', 'Challenge Management')

@section('content')
<div class="container-xxl">

    {{-- Alert Feedback --}}
    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Challenge Management</h5>
            <a href="{{ route('admin.challenges.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah Challenge
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Reward / Kategori</th>
                        <th>PDF</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($challenges as $c)
                    <tr id="row-{{ $c->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $c->nama }}</strong></td>

                        {{-- FORMAT DOLAR --}}
                        <td>
                            <span class="badge bg-label-success">
                                ${{ number_format($c->kategori, 0, ',', ',') }}
                            </span>
                        </td>

                        <td>
                            @if ($c->file_pdf)
                                <a href="{{ asset('storage/'.$c->file_pdf) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-icon btn-outline-info" title="Lihat PDF">
                                    <i class="bx bx-file"></i>
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.challenges.edit', $c->id) }}" class="btn btn-sm btn-warning">
                                <i class="bx bx-edit-alt"></i>
                            </a>

                            <button class="btn btn-sm btn-danger btn-delete"
                                    data-id="{{ $c->id }}"
                                    data-url="{{ route('admin.challenges.destroy', $c->id) }}">
                                <i class="bx bx-trash"></i>
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
            const url = this.dataset.url;

            Swal.fire({
                title: 'Hapus challenge?',
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
