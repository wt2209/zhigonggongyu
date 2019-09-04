@extends('admin::index')

@section('content')
    <section class="content-header">
        <h1>
            材料
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">编辑</h3>
                        <div class="box-tools">
                            <div class="btn-group pull-right" style="margin-right: 10px">
                                <a href="{{route('repairs.finished')}}" class="btn btn-sm btn-default">
                                    <i class="fa fa-list"></i>
                                    &nbsp;列表
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('repairs.material')}}" method="post" accept-charset="UTF-8" class="form-horizontal" pjax-container>
                        {{ csrf_field() }}
                        <input type="hidden" name="_previous_" value="{{\URL::previous() ?: ''}}">
                        <input type="hidden" name="id" value="{{$repair->id}}">
                        @if(count($errors->messages()) > 0)
                            <div class="has-error" style="padding-left: 10px;">
                            @foreach(array_slice($errors->messages(), 0, 1) as $message)
                                <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message[0]}}</label><br/>
                            @endforeach
                            </div>
                        @endif
                        <div class="box-body">
                            用工：
                            <table class="table table-hover" id="type-table">
                                <tbody>
                                @if ($oldTypes = old('types', $oldTypes))
                                    @foreach($oldTypes as $k => $oldType)
                                        <tr>
                                            <td style="line-height: 35px;">
                                                {{ $oldType['display'] }}
                                                <input type="hidden" name="types[{{$k}}][display]" value="{{ $oldType['display'] }}">
                                                <input type="hidden" name="types[{{$k}}][id]" value="{{$oldType['repair_type_id']}}">
                                            </td>
                                            <td style="width: 100px;">
                                                <input type="text" class="form-control" value="{{$oldType['total']}}" name="types[{{$k}}][total]" placeholder="用量">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" value="{{$oldType['remark']}}" name="types[{{$k}}][remark]" placeholder="备注">
                                            </td>
                                            <td style="line-height: 35px;padding-left: 4px;">
                                                <a href="javascript:void(0);" class="btn btn-xs btn-danger remove-table-row">
                                                    删除
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="box-body">
                            用料：
                            <table class="table table-hover" id="item-table">
                                <tbody>
                                @if ($oldItems = old('items', $oldItems))
                                    @foreach($oldItems as $k => $oldItem)
                                    <tr>
                                        <td style="line-height: 35px;">
                                            {{$oldItem['display'] }}
                                            <input type="hidden" name="items[{{$k}}][display]" value="{{ $oldItem['display'] }}">
                                            <input type="hidden" name="items[{{$k}}][id]" value="{{$oldItem['repair_item_id']}}">
                                        </td>
                                        <td style="width: 100px;">
                                            <input type="text" class="form-control" value="{{$oldItem['total']}}" name="items[{{$k}}][total]" placeholder="用量">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="{{$oldItem['remark']}}" name="items[{{$k}}][remark]" placeholder="备注">
                                        </td>
                                        <td style="line-height: 35px;padding-left: 4px;">
                                            <a href="javascript:void(0);" class="btn btn-xs btn-danger remove-table-row">
                                                删除
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <div class="btn-group pull-right">
                                    <button type="submit" class="btn btn-info pull-right"
                                            data-loading-text="<i class='fa fa-spinner fa-spin '></i> 保存">保存
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">工种</h3>
                    </div>
                    <div class="box-body">
                        <label for="types" style="display: none;"></label>
                        <span class="info-container">
                            <span class="info">总共 {{count($types)}} 项</span>
                            <button type="button" class="btn show-all pull-right btn-default btn-xs">显示全部</button>
                        </span>
                        <input class="filter form-control" type="text" placeholder="过滤">
                        <select multiple="multiple" id="types" data-table="type-table" class="form-control" style="height: 100px;margin-top: 2px;">
                            @foreach($types as $type)
                                <option value="{{$type->id}}">{{$type->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">材料</h3>
                    </div>
                    <div class="box-body">
                        <label for="items" style="display: none;"></label>
                        <span class="info-container">
                            <span class="info">总共 {{count($items)}} 项</span>
                            <button type="button" class="show-all btn pull-right btn-default btn-xs">显示全部</button>
                        </span>
                        <input class="filter form-control" value="" type="text" placeholder="过滤">

                        <select multiple="multiple" id="items" data-table="item-table" class="form-control" style="height: 200px;margin-top: 2px;">
                            @foreach($items as $item)
                                <option value="{{$item->id}}">{{$item->display}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(function () {
            // 双击添加
            let index = {{max(array_keys($oldTypes->toArray()?:[1])) + max(array_keys($oldItems->toArray()?:[1])) + 2}}; // 保证与已有值不冲突。计数器，双击一次加1
        // let index = 1000;
            $('option').dblclick(function(){
                let table = $(this).parent().data('table');
                let tr = document.createElement('tr');
                let title = $(this).parent().attr('id');
                let str = `
                     <td style="line-height: 35px;">
                        ` + $(this).html() + `
                        <input type="hidden" name="` + title + `[`+index+`][display]" value="` + $(this).html() + `">
                        <input type="hidden" name="` + title + `[`+index+`][id]" value="` + $(this).val() + `">
                    </td>
                    <td style="width: 100px;">
                        <input type="text" class="form-control" name="` + title + `[`+index+`][total]" placeholder="用量">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="`+ title +`[`+index+`][remark]" placeholder="备注">
                    </td>
                    <td style="line-height: 35px;padding-left: 4px;">
                        <a href="javascript:void(0);" class="btn btn-xs btn-danger remove-table-row">
                            删除
                        </a>
                    </td>
                `;
                $(tr).html(str);
                index++;
                document.querySelector('#' + table + ' tbody').appendChild(tr);
            });

            // 动态绑定
            $("table").delegate(".remove-table-row", "click", function(){
                $(this).parents('tr').remove();
            });

            // 过滤
            let opts = [];
            opts['items'] = $('#items option').map(function () {
                return [[this.value, $(this).text()]];
            });
            opts['types'] = $('#types option').map(function () {
                return [[this.value, $(this).text()]];
            });
            $('.filter').keyup(function () {
                let rxp = new RegExp($(this).val(), 'i');
                let optList = $(this).siblings('select').empty();
                opts[optList.attr('id')].each(function () {
                    if (rxp.test(this[1])) {
                        optList.append($('<option/>').attr('value', this[0]).text(this[1]));
                    }
                });
            });
            $('.show-all').click(function () {
                $(this).parent().siblings('.filter').val('');
                let optList = $(this).parent().siblings('select');
                optList.empty();
                opts[optList.attr('id')].each(function () {
                    optList.append($('<option/>').attr('value', this[0]).text(this[1]));
                });
            })
        });
    </script>
@endsection