@extends('admin.layouts.contentNavbarLayout')

@section('title', 'Buat Event Baru')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Event</label>
                <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label for="instansi" class="form-label">Instansi Penyelenggara</label>
                <input type="text" class="form-control" id="instansi" name="instansi" placeholder="Misal: Telkom Indonesia (Opsional)" value="{{ old('instansi') }}">
            </div>

            <div class="mb-3">
                <label for="banner_image" class="form-label">Banner Image (Opsional)</label>
                <input class="form-control" type="file" id="banner_image" name="banner_image">
            </div>

            <hr>

            {{-- Input Tanggal Event (Tipe 'date') --}}
            <div class="mb-3">
                <label for="event_date" class="form-label">Tanggal Event</label>
                <input class="form-control" type="date" id="event_date" name="event_date" value="{{ old('event_date') }}">
            </div>

            <h5 class="mb-3">Timer Fase (Input ini 'datetime-local' untuk Jam)</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="challenge_start_time" class="form-label">Mulai Challenge</label>
                    <input class="form-control" type="datetime-local" id="challenge_start_time" name="challenge_start_time" value="{{ old('challenge_start_time') }}">
                </div>
                <div class="col-md-6">
                    <label for="challenge_end_time" class="form-label">Selesai Challenge</label>
                    <input class="form-control" type="datetime-local" id="challenge_end_time" name="challenge_end_time" value="{{ old('challenge_end_time') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="case_start_time" class="form-label">Mulai Case</label>
                    <input class="form-control" type="datetime-local" id="case_start_time" name="case_start_time" value="{{ old('case_start_time') }}">
                </div>
                <div class="col-md-6">
                    <label for="case_end_time" class="form-label">Selesai Case</label>
                    <input class="form-control" type="datetime-local" id="case_end_time" name="case_end_time" value="{{ old('case_end_time') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="show_start_time" class="form-label">Mulai Show</label>
                    <input class="form-control" type="datetime-local" id="show_start_time" name="show_start_time" value="{{ old('show_start_time') }}">
                </div>
                <div class="col-md-6">
                    <label for="show_end_time" class="form-label">Selesai Show</label>
                    <input class="form-control" type="datetime-local" id="show_end_time" name="show_end_time" value="{{ old('show_end_time') }}">
                </div>
            </div>

            <hr>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1">
                <label class="form-check-label" for="is_active">Aktifkan Event Ini?</label>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
