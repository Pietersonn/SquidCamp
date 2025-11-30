@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Master Data Challenges')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Alert Feedback --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">

        {{-- Header Card --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Challenge Management</h5>
            <a href="{{ route('admin.challenges.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Challenge
            </a>
        </div>

        {{-- Table --}}
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Challenge</th>
                        <th>Tingkat Kesulitan</th>
                        <th>Deskripsi Singkat</th>
                        <th class="text-center">PDF</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($challenges as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong class="text-dark">{{ $item->nama }}</strong>
                        </td>
                        <td>
                            @if($item->price == 300000)
                                <span class="badge bg-label-success">Easy (300K)</span>
                            @elseif($item->price == 500000)
                                <span class="badge bg-label-warning">Medium (500K)</span>
                            @elseif($item->price == 700000)
                                <span class="badge bg-label-danger">Hard (700K)</span>
                            @else
                                <span class="badge bg-label-secondary">{{ number_format($item->price) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="d-inline-block text-truncate text-muted" style="max-width: 200px;" title="{{ $item->deskripsi }}">
                                {{ $item->deskripsi }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($item->file_pdf)
                                <a href="{{ asset('storage/'.$item->file_pdf) }}" target="_blank" class="btn btn-icon btn-sm btn-outline-info" title="Lihat PDF">
                                    <i class="bx bxs-file-pdf"></i>
                                </a>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.challenges.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bx bx-edit-alt"></i>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.challenges.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <span class="text-muted">Belum ada data challenge.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
