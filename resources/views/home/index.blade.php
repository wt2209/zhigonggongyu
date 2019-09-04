@extends('admin::index')

@section('content')
    <section class="content-header">
        <h1>
            基础信息
        </h1>
    </section>
    <section class="content">
        @include('admin::partials.error')
        @include('admin::partials.success')
        @include('admin::partials.exception')
        @include('admin::partials.toastr')

        <div class="row">
            <div class="col-md-4" style="padding: 0;">
                @include('home.partials.main-info')
            </div>
            <div class="col-md-4">
                @include('home.partials.bills-count')
            </div>
            <div class="col-md-4">
                @include('home.partials.repairing')
            </div>
        </div>
        <div class="row">
            @include('home.partials.types-info')
        </div>
        <div class="row">
            @include('home.partials.building-detail')
        </div>
    </section>
    @stack('home.scripts')
@endsection
