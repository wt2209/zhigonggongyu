@extends('lives.common._header')

@push('lives.css')
    <style>
        .box-body{
            padding:7px;
        }
    </style>
@endpush
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
    <form action="{{route('lives.renew', ['id'=>$record->id])}}" method="post" accept-charset="UTF-8" class="form-horizontal" pjax-container="">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_previous_" value="{{\URL::previous()}}">
        <div class="box-body">
            <div class="fields-group">
                <div class="form-group ">
                    <label class="col-sm-2  control-label">姓名</label>
                    <div class="col-sm-8">
                        <div class="no-margin">
                            <div class="box-body">
                                {{ $record->person->name }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group ">
                    <label class="col-sm-2  control-label">当前房间</label>
                    <div class="col-sm-8">
                        <div class="no-margin">
                            <div class="box-body">
                                {{ $record->room->title }} ({{$record->room->type->title}})
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group ">
                    <label class="col-sm-2  control-label">劳动合同</label>
                    <div class="col-sm-8">
                        <div class="no-margin">
                            <div class="box-body">
                                {{ $record->person->contract_start}} — {{ $record->person->contract_end}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group ">
                    <label class="col-sm-2  control-label">租期</label>
                    <div class="col-sm-8">
                        <div class="no-margin">
                            <div class="box-body">
                                {{ $record->start_at}} — {{$record->end_at}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group {!! !$errors->has('new_contract_end') ? '' : 'has-error' !!} ">
                    <label for="new_contract_end" class="col-sm-2  control-label">新劳动合同结束日</label>
                    <div class="col-sm-8 form-inline">
                        @if($errors->has('new_contract_end'))
                            @foreach($errors->get('new_contract_end') as $message)
                                <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                            @endforeach
                        @endif
                        <div class="input-group">
                            <input style="width: 200px" type="text" id="person_contract_end"
                                   name="new_contract_end"
                                   value="{{old('new_contract_end')}}"
                                   class="form-control"
                                   placeholder="格式：2018-9-4"
                                   @if(old('new_contract_end') === \App\Models\Person::CONTRACT_RETIRE_END)
                                   readonly
                                   @endif
                            >
                        </div>
                        <div class="checkbox" style="margin-left: 10px;">
                            <label>
                                <input type="checkbox" id="no-deadline"
                                    @if(old('new_contract_end') === \App\Models\Person::CONTRACT_RETIRE_END)
                                        checked
                                    @endif
                                > 无固定期
                            </label>
                        </div>
                        <script>
                            $('#no-deadline').click(function() {
                                if($(this).prop("checked")) {
                                    $('#person_contract_end').val('{{\App\Models\Person::CONTRACT_RETIRE_END}}')
                                        .attr('readonly', 'readonly');
                                } else {
                                    $('#person_contract_end').val('').removeAttr('readonly');
                                }
                            })
                        </script>
                    </div>
                </div>
                <div class="form-group {!! !$errors->has('new_end_at') ? '' : 'has-error' !!} ">
                    <label for="new_end_at" class="col-sm-2  control-label">新租期结束日</label>
                    <div class="col-sm-8">
                        @if($errors->has('new_end_at'))
                            @foreach($errors->get('new_end_at') as $message)
                                <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message}}</label><br/>
                            @endforeach
                        @endif
                        <div class="input-group">
                            <input style="width: 200px" type="text"  name="new_end_at" value="{{old('new_end_at')}}" class="form-control" placeholder="格式：2018-9-4">
                        </div>
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