@extends('admin::index')

@section('content')
    <section class="content-header">
        <h1>
            搜索
        </h1>
    </section>
    <section class="content">
        @include('admin::partials.error')
        @include('admin::partials.success')
        @include('admin::partials.exception')
        @include('admin::partials.toastr')

        {{--在住--}}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ \App\Models\Record::$statusMap[\App\Models\Record::STATUS_NULL] }}</h3>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                @if (isset($records[\App\Models\Record::STATUS_NULL]))
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>类型</th>
                            <th>房间号</th>
                            <th>姓名</th>
                            <th>部门</th>
                            <th>身份证号</th>
                            <th>电话</th>
                        </tr>
                        @foreach($records[\App\Models\Record::STATUS_NULL] as $record)
                            <tr>
                                <td>{{ $record->room->type->title }}</td>
                                <td>{{ $record->room->title }}</td>
                                <td>
                                    <a href="{{ route('lives.index', ['keyword' => $record->person->name]) }}">
                                        {{ $record->person->name }}
                                    </a>
                                </td>
                                <td>{{ $record->person->department }}</td>
                                <td>{{ $record->person->identify }}</td>
                                <td>{{ $record->person->phone_number }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-success alert-dismissible">
                        <h4 style="margin:0;"><i class="icon fa fa-remove"></i> 没有找到！</h4>
                    </div>
                @endif
            </div>
            <!-- /.box-body -->
        </div>

        @if (isset($records[\App\Models\Record::STATUS_MOVE]))
            {{--调房--}}
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ \App\Models\Record::$statusMap[\App\Models\Record::STATUS_MOVE] }}</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>类型</th>
                            <th>房间号</th>
                            <th>姓名</th>
                            <th>部门</th>
                            <th>身份证号</th>
                            <th>电话</th>
                            <th>调房到</th>
                            <th>调房时间</th>
                        </tr>
                        @foreach($records[\App\Models\Record::STATUS_MOVE] as $record)
                            <tr>
                                <td>{{ $record->room->type->title }}</td>
                                <td>{{ $record->room->title }}</td>
                                <td>{{ $record->person->name }}</td>
                                <td>{{ $record->person->department }}</td>
                                <td>{{ $record->person->identify }}</td>
                                <td>{{ $record->person->phone_number }}</td>
                                <td>{{ $record->toRoom->title }}</td>
                                <td>{{ $record->deleted_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                <!-- /.box-body -->
            </div>
        @endif
        @if (isset($records[\App\Models\Record::STATUS_QUIT]))
            {{--退房--}}
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ \App\Models\Record::$statusMap[\App\Models\Record::STATUS_QUIT] }}</h3>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>类型</th>
                            <th>房间号</th>
                            <th>姓名</th>
                            <th>部门</th>
                            <th>身份证号</th>
                            <th>电话</th>
                            <th>入住公寓时间</th>
                            <th>退房时间</th>
                        </tr>
                        @foreach($records[\App\Models\Record::STATUS_QUIT] as $record)
                            <tr>
                                <td>{{ $record->room->type->title }}</td>
                                <td>{{ $record->room->title }}</td>
                                <td>{{ $record->person->name }}</td>
                                <td>{{ $record->person->department }}</td>
                                <td>{{ $record->person->identify }}</td>
                                <td>{{ $record->person->phone_number }}</td>
                                <td>{{ $record->person->entered_at }}</td>
                                <td>{{ $record->deleted_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        @endif
    </section>

@endsection
