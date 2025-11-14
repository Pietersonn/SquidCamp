@extends('admin/layouts/contentNavbarLayout')

@section('title', 'Tambah User')

@section('content')
<div class="row gy-6">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah User</h5>
                <small class="text-body">Form untuk menambahkan user baru</small>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <!-- NAMA -->
                    <div class="mb-6">
                        <label class="form-label" for="user-name">Nama</label>
                        <input
                            type="text"
                            id="user-name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Masukkan nama lengkap"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- EMAIL -->
                    <div class="mb-6">
                        <label class="form-label" for="user-email">Email</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="icon-base bx bx-envelope"></i></span>
                            <input
                                type="email"
                                id="user-email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="contoh@email.com"
                                required>
                        </div>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-6">
                        <label class="form-label" for="user-password">Password</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="icon-base bx bx-lock"></i></span>
                            <input
                                type="password"
                                id="user-password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimal 6 karakter"
                                required>
                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- ROLE -->
                    <div class="mb-6">
                        <label class="form-label" for="user-role">Role</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="icon-base bx bx-user-pin"></i></span>
                            <select
                                id="user-role"
                                name="role"
                                class="form-control @error('role') is-invalid @enderror"
                                required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="mentor">Mentor</option>
                                <option value="investor">Investor</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="bx bx-save me-1"></i> Simpan
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
