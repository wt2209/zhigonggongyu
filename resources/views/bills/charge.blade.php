<div class="charge">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> 说明 </h4>
                缴费日期格式为：2018-9-28。若未填写缴费日期，或日期格式错误，将自动使用当前时间。
            </div>
        </div>
    </div>
    <form action="{{ route('bills.charge-page') }}" method="get" pjax-container>
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="box" style="border: none;margin-bottom: 10px;">
                    <div class="box-body">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" id="field-button" class="btn btn-primary dropdown-toggle"
                                        data-toggle="dropdown" aria-expanded="false">
                                    <span class="btn-text">
                                        @if (request('field'))
                                            {{request('field') === 'location' ? '房间号/位置' : '姓名'}}
                                        @else
                                            房间号/位置
                                        @endif
                                    </span>
                                    <span class="fa fa-caret-down" style="margin-left: 5px;"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" class="search-field" data-field="location">房间号/位置</a></li>
                                    <li><a href="#" class="search-field" data-field="name">姓名</a></li>
                                </ul>
                                <input type="hidden" name="field" value="{{ request('field') ?: 'location' }}">
                            </div>
                            <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}">
                            <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-primary btn-flat">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if (count($groupedBills) > 0)
        <div class="row">
            @foreach ($groupedBills as $location => $groupedBill)
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $location }}</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-striped">
                                @foreach ($groupedBill as $bill)
                                    <tr>
                                        <td>{{ $bill->name }}</td>
                                        <td>{{ $bill->type->title }}</td>
                                        <td>
                                            @if ($bill->cost > 0)
                                                {{ $bill->cost }}
                                            @else
                                                <span class="label label-danger" style="display:inline-block;">{{ $bill->cost }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $bill->explain }}</td>
                                        <td>
                                            <button data-id="{{ $bill->id }}" class="btn btn-xs btn-success charge-button pull-right"
                                                    style="margin-left: 10px;margin-top: 4px;">
                                                缴费
                                            </button>
                                            <div class="input-group input-group-sm pull-right">
                                                <input type="text" style="width:100px;" class="form-control payed_at" placeholder="缴费时间">
                                            </div>
                                            <div class="input-group input-group-sm pull-right" style="padding-top: 4px;">
                                                @foreach(config('charge.mode') as $mode)
                                                    <label class="radio-inline charge-mode" style="margin-right: 12px;">
                                                        <input type="radio" name="{{ $bill->id . 'mode'}}" value="{{$mode}}">{{$mode}}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="box-footer" style="">
                            <div class="pull-left col-md-6">
                                <p>
                                    费用总计：<span style="color: red;font-weight: bold;">{{ $groupedBill->sum('cost') }}</span> 元
                                </p>
                            </div>
                            <div class="pull-right col-md-6">
                                <button data-id="{{ implode(',', $groupedBill->pluck('id')->all()) }}" type="submit"
                                        class="btn btn-warning btn-sm charge-button pull-right"
                                        style="margin-left: 10px;">
                                    全部缴费
                                </button>
                                <div class="input-group input-group-sm pull-right">
                                    <input type="text" style="width:100px;" class="form-control payed_at" placeholder="缴费时间">
                                </div>
                                <div class="input-group input-group-sm pull-right" style="padding-top: 4px;">
                                    @foreach(config('charge.mode') as $mode)
                                        <label class="radio-inline charge-mode" style="margin-right: 12px;">
                                            <input type="radio" name="{{ $bill->id . 'mode'}}" value="{{$mode}}">{{$mode}}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <script>
        $(function () {
            // 选中后自动清空输入框
            $('input[name=keyword]').focus(function(){
                $(this).val('')
            })


            $('.search-field').click(function () {
                $('input[name=field]').val($(this).data('field'));
                $('#field-button').children('.btn-text').text($(this).text());
            });

            $('.charge-button').unbind('click').click(function() {
                let id = $(this).data('id');
                let payed_at = $(this).siblings('.input-group').find('input[type=text]').val();
                let charge_mode = $(this).siblings('.input-group').find('input[type=radio]:checked').val();

                swal({
                        title: "确认缴费？",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "确认",
                        closeOnConfirm: true,
                        cancelButtonText: "取消"
                    },
                    function(){
                        $.ajax({
                            method: 'post',
                            url: '{{ route("bills.charge") }}',
                            data: {
                                _method: 'PUT',
                                payed_at: payed_at,
                                charge_mode: charge_mode,
                                id: id,
                                _token:LA.token
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
        })
    </script>
</div>
