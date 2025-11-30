@extends('admin.layouts.contentNavbarLayout')

@section('title', "Event Challenges - $event->name")

@section('styles')
<style>
    .challenge-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: 0.3s;
        overflow: hidden;
    }
    .challenge-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 167, 157, 0.15);
    }
    .visual-header {
        height: 180px;
        background: #f2fcfb; /* Light Teal Background */
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .visual-header i {
        font-size: 6rem;
        color: #00a79d;
        opacity: 0.8;
        transition: 0.3s;
    }
    .challenge-card:hover .visual-header i {
        transform: scale(1.1) rotate(-10deg);
    }
    .reward-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #00a79d;
        color: white;
        font-weight: bold;
        padding: 8px 15px;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(0, 167, 157, 0.3);
    }
    .btn-action-squid {
        color: #00a79d;
        background: #e0f2f1;
        border: none;
    }
    .btn-action-squid:hover {
        background: #00a79d;
        color: white;
    }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #008f85;"><i class="bx bx-joystick me-2"></i>Challenges</h4>
        <span class="text-muted">Event: {{ $event->name }}</span>
    </div>
    <a href="{{ route('admin.events.challenges.create', $event->id) }}" class="btn btn-primary" style="background-color: #00a79d; border:none;">
        <i class="bx bx-plus me-1"></i> Pilih Challenge
    </a>
</div>

<div class="row g-4">
  {{-- PERBAIKAN: Mengganti $selected_challenges menjadi $challenges --}}
  @forelse ($challenges as $challenge)
    <div class="col-md-6 col-lg-4">
      <div class="card challenge-card h-100">

        <span class="reward-badge">
            {{-- Menggunakan 'price' sesuai kolom database terbaru, fallback ke kategori jika null --}}
            ${{ number_format($challenge->price ?? $challenge->kategori, 0, ',', '.') }}
        </span>

        <div class="visual-header">
            <i class="bx bx-trophy"></i>
        </div>

        <div class="card-body d-flex flex-column">
            <h5 class="card-title fw-bold mb-2 text-dark">{{ $challenge->nama }}</h5>
            <p class="card-text text-muted small mb-4 flex-grow-1">
                {{ Str::limit($challenge->deskripsi, 90) ?? 'Tidak ada deskripsi.' }}
            </p>

            <div class="d-grid gap-2">
                @if($challenge->file_pdf)
                    <a href="{{ asset('storage/'.$challenge->file_pdf) }}" target="_blank" class="btn btn-action-squid btn-sm">
                        <i class="bx bx-file me-1"></i> Lihat Instruksi
                    </a>
                @else
                    <button class="btn btn-light btn-sm text-muted" disabled><i class="bx bx-x-circle"></i> No File</button>
                @endif
            </div>
        </div>

        <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
            {{-- Form Delete (Detach dari Event) --}}
            <form action="{{ route('admin.events.challenges.destroy', ['event' => $event->id, 'challenge' => $challenge->id]) }}" method="POST" onsubmit="return confirm('Hapus challenge ini dari event?');" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm text-danger fw-bold p-0 border-0 bg-transparent">
                    <i class="bx bx-trash me-1"></i> Remove
                </button>
            </form>

            {{-- Edit mengarah ke Master Data Challenge --}}
            <a href="{{ route('admin.challenges.edit', $challenge->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                Edit Master <i class="bx bx-edit ms-1"></i>
            </a>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12 text-center py-5">
        <div class="badge p-4 rounded-circle mb-3" style="background-color: #e0f2f1; color: #00a79d;"><i class="bx bx-joystick fs-1"></i></div>
        <h4 class="text-muted">Belum ada Challenge di Event ini</h4>
        <p class="text-muted mb-4">Tambahkan challenge dari master data untuk memulai.</p>
        <a href="{{ route('admin.events.challenges.create', $event->id) }}" class="btn btn-primary" style="background-color: #00a79d; border:none;">Pilih Challenge</a>
    </div>
  @endforelse
</div>
@endsection
