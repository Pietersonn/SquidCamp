@extends('admin.layouts.contentNavbarLayout')
@section('title', 'User Management')

@section('content')
  <div class="container-xxl">

    <div class="card">

      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">User Management</h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
          <i class="icon-base bx bx-plus"></i> Tambah User
        </a>
      </div>

      <div class="table-responsive text-nowrap">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($users as $u)
              <tr id="user-row-{{ $u->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>
                  @php
                    $badge =
                        [
                            'admin' => 'primary',
                            'mentor' => 'success',
                            'investor' => 'warning',
                            'user' => 'info',
                        ][$u->role] ?? 'secondary';
                  @endphp
                  <span class="badge bg-label-{{ $badge }}">{{ ucfirst($u->role) }}</span>
                </td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="icon-base bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{ route('admin.users.edit', $u->id) }}">
                        <i class="icon-base bx bx-edit-alt me-2"></i>Edit
                      </a>
                      <a href="javascript:void(0);" class="dropdown-item text-danger btn-delete"
                        data-id="{{ $u->id }}" data-url="{{ route('admin.users.destroy', $u->id) }}">
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
        {{ $users->links() }}
      </div>

    </div>

  </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const csrfToken = '{{ csrf_token() }}';
  document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function() {
      const userId = this.dataset.id;

      Swal.fire({
        title: 'Hapus user?',
        text: "Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        backdrop: false // <-- hilangkan overlay gelap
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`/admin/users/${userId}`, {
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
                const row = document.getElementById('user-row-' + userId);
                if (row) row.remove();
                Swal.fire('Terhapus!', data.message, 'success');
              } else {
                Swal.fire('Gagal!', data.message, 'error');
              }
            })
            .catch(() => Swal.fire('Gagal!', 'Terjadi kesalahan server.', 'error'));
        }
      });
    });
  });
});
</script>
@endsection
