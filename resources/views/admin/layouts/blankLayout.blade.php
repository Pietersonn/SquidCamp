@php
  use App\Helpers\Helper;
@endphp

@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset


@extends('admin/layouts/commonMaster')

@section('admin/layoutContent')
<!-- Content -->
@yield('content')
<!--/ Content -->
@endsection
