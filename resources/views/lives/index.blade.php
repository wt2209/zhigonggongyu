@extends('lives.common._header')

@section('lives.content')
    <div class="container-fluid">
            <div class="row">
                <div class="box">
                    <div class="box-header">
                        <div class="col-md-12" style="padding:0 5px;">
                            <div class="select-button">
                                <button class="btn btn-default" type="button" data-toggle="dropdown" aria-expanded="false" id="select-building-btn">
                                    @if (request('building'))
                                        {{request('building')}} /
                                        {{request('unit')}}
                                    @else
                                        选择楼号
                                    @endif
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @foreach($structure as $building => $units)
                                        <li class="dropdown-submenu">
                                            <a tabindex="0" data-toggle="dropdown" class="building">{{ $building }}</a>
                                            <ul class="dropdown-menu">
                                                @foreach($units as $unit)
                                                    <li>
                                                        <a href="{{route('lives.index', ['building'=>$building, 'unit'=>$unit])}}">{{ $unit }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="search">
                                <form action="{{route('lives.index')}}" method="GET" class="form-inline" role="form" pjax-container>
                                    <div class="input-group">
                                        <input type="text" class="form-control" style="padding:6px;"
                                               name="keyword" placeholder="姓名，房间号，或电话" value="{{request('keyword')}}">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                            <div class="date-search">
                                <form action="{{route('lives.index')}}" method="GET" class="form-inline" role="form" pjax-container>
                                        <div class="input-group">
                                            <select name="date_type_id" title="类型" style="padding:6px;" class="form-control">
                                                <option value="0">--选择类型--</option>
                                                @foreach($types as $typeId => $title)
                                                    <option value="{{$typeId}}" @if(request('date_type_id') == $typeId) selected @endif>{{$title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group">
                                            <select name="status" class="form-control" style="padding:6px;">
                                                @if (request('status') == 'end')
                                                    <option value="start">租期开始日</option>
                                                    <option value="end" selected>租期结束日</option>
                                                @else
                                                    <option value="start" selected>租期开始日</option>
                                                    <option value="end">租期结束日</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="input-group">
                                            <input type="text" name="start" class="form-control" style="width: 101px;padding:6px;" value="{{request('start')}}" placeholder="起始日">
                                        </div>
                                        <div class="input-group">
                                            <input type="text" name="end" class="form-control" style="width: 101px;padding:6px;" value="{{request('end')}}" placeholder="截至日">
                                            <span class="input-group-btn">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </span>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="room-content" style="max-width: 1200px;margin: 0 auto;overflow-y: auto;">
                    <div class="box-body">
                        @if ($rooms->isNotEmpty())
                            @foreach($rooms as $room)
                                <div class="col-md-6 room">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style="overflow: hidden">
                                            <h3 class="panel-title col-md-5 room-title ">
                                                {{$room->title}}
                                                <span style="font-size: 12px;">({{$room->type->title}})</span>
                                            </h3>
                                            <div class="room-remark col-md-7">
                                                <span style="display: block;width:100%;min-height: 24px;" ondblclick="showEditInput(this)">{{$room->remark}}</span>
                                                <input type="text" name="remark" data-id="{{$room->id}}" onblur="editRemark(this)"
                                                       class="form-control" value="{{$room->remark}}">
                                            </div>
                                        </div>
                                        <div class="panel-body" style="padding:5px;">
                                            @foreach($room->records as $record)
                                                <div class="@if($room->person_number > 1 || count($room->records) > 1) col-md-6 @endif person-card-container" style="padding: 3px;">
                                                    <div class="person-card">
                                                        <div class="person-title">
                                                            <div class="person-name">
                                                                {{$record->person->name}}
                                                                <span style="font-size:12px">
                                                                    ({{$record->person->gender}}@if($record->person->education !== \App\Models\Person::EDUCATION_UNKNOWN)，{{\App\Models\Person::$educationMap[$record->person->education]}}@endif)
                                                                </span>
                                                            </div>
                                                            <div class="person-type">
                                                                {{$record->type->title}}
                                                            </div>
                                                        </div>
                                                        <div class="person-detail">
                                                            <div class="col-md-6" style="padding: 0;">
                                                                <p>{{$record->person->department}}</p>
                                                                <p>{{$record->person->phone_number}}</p>
                                                            </div>
                                                            <div class="col-md-6" style="padding: 0;text-align:right;">
                                                                <p>进公寓:{{$record->person->entered_at}}</p>
                                                                <p>本房间:{{$record->record_at}}</p>
                                                            </div>
                                                        </div>
                                                        @showContractDetail($record)
                                                        <div class="person-detail">
                                                            <p>
                                                                @hasContract($record)
                                                                劳动合同:{{$record->person->contract_start}} — {{$record->person->contract_end}}
                                                                @endif
                                                            </p>
                                                            <p>
                                                                @hasRentContract($record)
                                                                房间租期:{{$record->start_at}} — {{$record->end_at}}
                                                                @endif
                                                            </p>
                                                        </div>
                                                        @endif
                                                        <div class="person-detail">
                                                            <p><strong>身份证号:</strong>{{$record->person->identify}}</p>
                                                            <p><strong>退休日期(参考)：</strong>{{$record->person->retired_at}}</p>
                                                            <p class="remark"><strong>备注：</strong>{{$record->person->remark}}</p>
                                                        </div>
                                                        <div class="person-actions">
                                                            @if (Admin::user()->can('lives.quit'))
                                                                <button data-url="{{route('lives.quit', ['id' => $record->id])}}" class="btn btn-warning btn-xs quit">退房</button>
                                                            @endif
                                                            @if (Admin::user()->can('lives.move'))
                                                                <a href="{{route('lives.change', ['id' => $record->id])}}" class="btn btn-success btn-xs loading">调房</a>
                                                            @endif
                                                            @if(Admin::user()->can('lives.edit'))
                                                                <a href="{{route('lives.edit', ['id' => $record->id])}}" class="btn btn-success btn-xs loading">修改</a>
                                                            @endif
                                                            @if(Admin::user()->can('lives.renew'))
                                                                @hasRentContract($record)
                                                                    <a href="{{route('lives.prolong', ['id' => $record->id])}}" class="btn btn-success btn-xs loading">续签</a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if (count($room->records) < $room->person_number)
                                                @for ($i = 0; $i < $room->person_number - count($room->records); $i++)
                                                    <div class="@if($room->person_number > 1) col-md-6 @endif person-card-container" style="padding: 3px;">
                                                        <div class="person-card">
                                                            <div class="person-add">
                                                                <a href="{{route('lives.create', ['room_id'=>$room->id])}}">
                                                                    <i class="fa fa-plus avatar-uploader-icon"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endfor
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('lives.scripts')
    <script>
        // resize 房间内容区域
        let resizeContent = function () {
            let documentHeight = document.documentElement.clientHeight;
            let contentHeaderHeight = document.getElementsByClassName('main-header')[0].offsetHeight;
            let topNavHeight = document.getElementsByClassName('navbar-static-top')[0].offsetHeight;
            let boxHeaderHeight = document.getElementsByClassName('box-header')[0].offsetHeight;

            let h = documentHeight - contentHeaderHeight - topNavHeight - boxHeaderHeight - 44;
            document.getElementById('room-content').style.height = h + "px";
        };
        resizeContent();
        window.onresize = resizeContent;

        function showEditInput(span) {
            let input = $(span).siblings('input');
            input.show();
            $(span).hide();
        }

        function editRemark(input) {
            let oldValue = $(input).siblings('span').text();
            let newValue = $(input).val();
            let id = input.dataset.id;
            if (oldValue !== newValue) {
                let id = input.dataset.id;
                $.ajax({
                    method: 'post',
                    url: "{{route('rooms.remark')}}",
                    data: {
                        _method:'put',
                        _token:LA.token,
                        id:id,
                        remark:newValue,
                    },
                    success: function (data) {
                        if (typeof data === 'object') {
                            if (data.status) {
                                $(input).siblings('span').text(newValue);
                            }
                        }
                    }
                });
            }
            $(input).hide();
            $(input).siblings('span').show();
        }

        $(function(){
            // 我也不知道怎么实现的。。
            $('#select-building-btn').click(function (e) {
                $(this).parent().addClass('open');
                $('.building').each(function () {
                    if ($(this).html() === '{{ request('building') }}') {
                        $(this).siblings('.dropdown-menu').find('.buildings').each(function () {
                            if ($(this).html() === '{{ request('building') }}') {
                                $(this).trigger('click');
                            }
                        });
                        $(this).trigger('click');
                        $(this).parents('li').addClass('open');
                    }
                });
                e.preventDefault();
                return false;
            });

            $('.dropdown-submenu > a[tabindex=0]').click(function (e) {
                $(this).css('background', '#d2d6de');
                $(this).parent().siblings('.dropdown-submenu').removeClass('open').find('a').css('background', '#fff');
                $(this).parents('li').addClass('open');
                setTimeout(function () {

                }, 500);
                e.preventDefault();
                return false;
            });

            // 退房
            $('.quit').unbind('click').click(function() {
                let url = $(this).data('url');
                swal({
                        title: "确定要退房吗?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "确定",
                        closeOnConfirm: true,
                        cancelButtonText: "取消"
                    },
                    function(){
                        $.ajax({
                            method: 'post',
                            url: url,
                            data: {
                                _method:'put',
                                _token:LA.token,
                            },
                            success: function (data) {
                                $.pjax.reload('#pjax-container');
                                if (typeof data === 'object') {
                                    if (data.status) {
                                        swal(data.message, '', 'success');
                                    } else {
                                        swal(data.message, '', 'error');
                                    }
                                }
                            }
                        });
                    });
            });

            // 按钮添加loading提示
            $('a.loading').click(function () {
                $(this).attr('disabled', 'disabled').html('loading...')
            })
        });
    </script>
@endpush



