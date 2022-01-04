<div class="statistics">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">统计结果</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if (count($billsStatistics) > 0)
                        <table class="table table-striped">
                            @foreach ($billsStatistics as $title => $totalCost)
                                <tr>
                                    <td>{{ $title }}</td>
                                    <td>
                                        ￥
                                        @if ($totalCost > 0)
                                            {{ $totalCost }}
                                        @else
                                            <span class="label label-danger" style="display:inline-block;">{{ $totalCost }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-bold">合计</td>
                                <td class="text-bold">
                                    ￥
                                    @if ($totalCost > 0)
                                        {{ array_sum($billsStatistics) }}
                                    @else
                                        <span class="label label-danger" style="display:inline-block;">{{ array_sum($billsStatistics) }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @else
                        <p>无</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <form action="{{ route('bills.statistics') }}" method="get" pjax-container>
                <div class="box box-default">
                    <div class="box-body statistics-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">筛选条件</h3>
                            <span>（按住 Ctrl 可多选）</span>
                        </div>
                        <div class="form-group select-box col-md-3 col-xs-6">
                            <span>年</span><br>
                            <select class="form-control" name="years[]" multiple>
                                <option value="0" @if(in_array(0, request('years')??[])) selected @endif >全部</option>
                                @for ($i = 2013; $i <= date('Y'); $i++)
                                    <option value="{{$i}}" @if(in_array($i, request('years')??[])) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group select-box col-md-3 col-xs-6">
                            <span>月</span><br>
                            <select class="form-control" name="months[]" multiple>
                                <option value="0" @if(in_array(0, request('months')??[])) selected @endif >全部</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{$i}}" @if(in_array($i, request('months')??[])) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group select-box col-md-3 col-xs-6">
                            <span>日</span><br>
                            <select class="form-control" name="days[]" multiple>
                                <option value="0" @if(in_array(0, request('days')??[])) selected @endif >全部</option>
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{$i}}" @if(in_array($i, request('days')??[])) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group select-box col-md-3 col-xs-6">
                            <span>类型</span><br>
                            <select class="form-control" name="type_ids[]" multiple >
                                <option value="0" @if(in_array(0, request('type_ids')??[])) selected @endif >全部</option>
                                @foreach($billTypes as $id => $title)
                                    <option value="{{ $id }}" @if(in_array($id, request('type_ids')??[])) selected @endif>{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group input-box col-md-12 col-xs-12">
                            <div class="col-md-3 input-box-list">
                                <input type="text" class="form-control" value="{{request('location')}}" name="location" placeholder="房间号/位置">
                            </div>
                            <div class="col-md-3 input-box-list">
                                <input type="text" class="form-control" value="{{request('name')}}" name="name" placeholder="姓名">
                            </div>
                            <div class="col-md-6 input-box-list">
                                <div style="padding: 6px;">
                                    <label>
                                        <input type="radio" value="2" name="turn_in"  checked> 全部
                                    </label>
                                    &nbsp;&nbsp;
                                    <label>
                                        <input type="radio" value="true" name="turn_in" @if(request('turn_in') === 'true') checked @endif> 上交
                                    </label>
                                    &nbsp;&nbsp;
                                    <label>
                                        <input type="radio" value="false" name="turn_in" @if(request('turn_in') === 'false') checked @endif> 物业留
                                    </label>
                                </div>

                            </div>
                        </div>
                        <div class="form-group input-box col-md-12 col-xs-12" style="padding-left: 6px;">
                            <label class="radio-inline charge-mode" style="margin-right: 12px;">
                                <input type="radio" name="charge_mode" value="" @if(!request('charge_mode')) checked @endif>全部
                            </label>
                            @foreach(config('charge.mode') as $mode)
                                <label class="radio-inline charge-mode" style="margin-right: 12px;">
                                    <input type="radio" name="charge_mode" value="{{$mode}}" @if(request('charge_mode') === $mode) checked @endif>{{$mode}}
                                </label>
                            @endforeach
                        </div>
                        <div class="form-group input-box col-md-12 col-xs-12">
                            <div style="padding: 6px;" class="col-md-6 col-xs-6">
                                <label>
                                    <input type="radio" value="false" checked name="is_refund" @if(request('is_refund') === 'false') checked @endif> 缴费
                                </label>
                                &nbsp;&nbsp;
                                <label>
                                    <input type="radio" value="true" name="is_refund" @if(request('is_refund') === 'true') checked @endif> 退费
                                </label>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <button class="btn btn-success pull-right">统计</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
