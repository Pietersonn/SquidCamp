@extends('layouts/blankLayout')

@section('title', 'Mentor')

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <div class="card px-sm-6 px-0">
                <div class="card-body text-center">
                    <h2 class="mb-4">Welcome, {{ Auth::user()->name ?? 'User' }}!</h2>
                    <p>You are now logged in. This is your mentor dashboard.</p>

                    <form action="{{ route('logout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
