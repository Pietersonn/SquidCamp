@php
    use App\Helpers\Helper;
@endphp

@isset($pageConfigs)
    {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
<html lang="en"
    class="layout-menu-fixed layout-compact"
    data-assets-path="{{ asset('/assets') . '/' }}"
    dir="ltr"
    data-bs-theme="light"
    data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | {{ config('variables.templateName') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- Styles Admin --}}
    @include('admin.layouts.sections.styles')

    {{-- Scripts (helpers, configs, etc) --}}
    @include('admin.layouts.sections.scriptsIncludes')
</head>

<body>

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">

                <!-- Dashboard Card -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body text-center">
                        <h2 class="mb-4">Welcome, {{ Auth::user()->name ?? 'User' }}!</h2>
                        <p>You are now logged in. This is your main dashboard.</p>

                        <form action="{{ route('logout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    </div>
                </div>
                <!-- /Dashboard Card -->

            </div>
        </div>
    </div>

    {{-- Admin Scripts --}}
    @include('admin.layouts.sections.scripts')

</body>
</html>
