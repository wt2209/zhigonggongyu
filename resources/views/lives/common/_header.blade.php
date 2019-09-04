@extends('admin::index')

@section('content')
    @stack('lives.css')
    <section class="content-header">
        <h1>
            {{ $pageTitle }}
        </h1>
    </section>
    <section class="content">
        @include('admin::partials.error')
        @include('admin::partials.success')
        @include('admin::partials.exception')
        @include('admin::partials.toastr')

        @yield('lives.content')
    </section>
    @stack('lives.scripts')
@endsection




