@extends('lives.common._header')

@section('lives.content')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{$pageTitle}}</h3>
            <div class="box-tools">
                <div class="btn-group pull-right" style="margin-right: 10px">
                    <a href="{{route('lives.index')}}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                </div> <div class="btn-group pull-right" style="margin-right: 10px">
                    <a class="btn btn-sm btn-default form-history-back" href="{{\URL::previous()}}"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form action="{{route('lives.move', ['id'=>$record->id])}}" method="post" accept-charset="UTF-8" class="form-horizontal" pjax-container="">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_previous_" value="{{\URL::previous()}}">
            <div class="box-body">
                <div class="fields-group">
                    <div class="form-group ">
                        <label class="col-sm-2  control-label">姓名</label>
                        <div class="col-sm-8">
                            <div class="no-margin" >
                                <div class="box-body" style="padding: 7px;">
                                    {{ $record->person->name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label class="col-sm-2  control-label">当前房间</label>
                        <div class="col-sm-8">
                            <div class="no-margin">
                                <div class="box-body" style="padding: 7px;">
                                    {{ $record->room->title }} ({{$record->room->type->title}})
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group {!! !$errors->has('move_to') ? '' : 'has-error' !!} ">
                        <label for="move_to" class="col-sm-2  control-label">调整到</label>
                        <div class="col-sm-8">
                            @if($errors->has('move_to'))
                                @foreach($errors->get('move_to') as $message)
                                    <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                                @endforeach
                            @endif
                            <input type="text" name="move_to" class="form-control" placeholder="房间号" value="{{old('move_to')}}">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> 保存">保存</button>
                    </div>
                </div>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>
@endsection
