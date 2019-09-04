@extends('admin::index')

@section('content')
    <style>
        .panel-body{
            padding: 10px;
        }
        .panel-footer{
            padding: 5px 10px;
        }
        .text-center{
            text-align: center;
        }
    </style>
    <section class="content-header">
        <h1>
            通知
        </h1>
    </section>
    <section class="content">
        <div class="row">
            @if(count($peopleWithManyRooms) > 0)
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">以下人员占用多个房间</h3>
                        <span class="pull-right badge bg-yellow" style="margin-left:5px;">
                            {{ count($peopleWithManyRooms) }}
                        </span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <div class="table-responsive">
                            @foreach($peopleWithManyRooms as $person)
                                <div class="col-md-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <p class="panel-title">
                                                {{$person->name}}
                                            </p>
                                        </div>
                                        <div class="panel-body">
                                            @foreach($person->records as $record)
                                                <div class="col-md-6" style="padding: 5px;">
                                                    <div class="panel panel-default" style="margin-bottom: 0">
                                                        <div class="panel-body text-center">
                                                            {{$record->type->title}} <br>
                                                            {{$record->room->title}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="panel-footer">
                                            <div style="overflow: hidden;">
                                                @if($person->phone_number)
                                                    <a href="{{route('lives.index', ['keyword'=>$person->phone_number])}}" class="btn btn-success btn-xs pull-right">去处理</a>
                                                @else
                                                    <a href="{{route('lives.index', ['keyword'=>$person->name])}}" class="btn btn-success btn-xs pull-right">去处理</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            @endif
            @if(count($recordsNeedRenew) > 0)
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">以下人员租期已到期</h3>
                        <span class="pull-right badge bg-yellow" style="margin-left:5px;">
                            {{ count($recordsNeedRenew) }}
                        </span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-striped">
                                <tr>
                                    <th>房间号</th>
                                    <th>姓名</th>
                                    <th>到期日</th>
                                    <th></th>
                                </tr>
                                @foreach($recordsNeedRenew as $record)
                                    <tr>
                                        <td>{{ $record->room->title }}</td>
                                        <td>{{ $record->person->name }}</td>
                                        <td>{{ $record->end_at }}</td>
                                        <td>
                                            @if($record->person->phone_number)
                                                <a href="{{route('lives.index', ['keyword'=>$record->person->phone_number])}}" class="btn btn-success btn-xs pull-right">去处理</a>
                                            @else
                                                <a href="{{route('lives.index', ['keyword'=>$record->person->name])}}" class="btn btn-success btn-xs pull-right">去处理</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            @endif
            @if (count($billsNotCharged) > 0)
                <div class="col-md-6">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">以下应交费用已超过25天</h3>
                            <span class="pull-right badge bg-yellow" style="margin-left:5px;">
                                {{ count($billsNotCharged) }}
                            </span>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            @foreach($billsNotCharged as $location => $group)
                                <div class="col-md-12">
                                    <div class="box box-danger">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">{{ $location }}</h3>
                                            <a href="{{route('bills.charge', ['field'=>'location', 'keyword'=>$location])}}" class="btn btn-success btn-xs pull-right">查看</a>
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            <table class="table table-striped">
                                                @foreach($group as $bill)
                                                    <tr>
                                                        <td>{{ $bill->name }}</td>
                                                        <td>{{ $bill->type->title }}</td>
                                                        <td>{{ $bill->explain }}</td>
                                                        <td>{{ substr($bill->created_at, 0, 10) }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection