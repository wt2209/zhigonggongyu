@extends('admin::index')

@section('content')
    <section class="content-header">
        <h1>
            维修详情
            <small></small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">详情</h3>
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
                    <form action="{{route('repairs.material')}}" method="post" accept-charset="UTF-8" class="form-horizontal" >
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
                        <div class="box-body table-responsive no-padding">
                            用工：
                            <table class="table table-hover" id="type-table">
                                <tbody>
                                @if ($oldTypes = old('types', $oldTypes))
                                    @foreach($oldTypes as $k => $oldType)
                                        <tr>
                                            <td style="line-height: 35px;">
                                                {{ $oldType['display'] }}
                                                <input type="hidden" name="types[{{$k}}][display]" value="{{ $oldType['display'] }}">
                                                <input type="hidden" name="types[{{$k}}][id]" value="{{$oldType['id']}}">
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
                        <div class="box-body table-responsive no-padding">
                            用料：
                            <table class="table table-hover" id="item-table">
                                <tbody>
                                @if ($oldItems = old('items', $oldItems))
                                    @foreach($oldItems as $k => $oldItem)
                                        <tr>
                                            <td style="line-height: 35px;">
                                                {{$oldItem['display'] }}
                                                <input type="hidden" name="items[{{$k}}][display]" value="{{ $oldItem['display'] }}">
                                                <input type="hidden" name="items[{{$k}}][id]" value="{{$oldItem['id']}}">
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
        </div>
    </section>

    <script>
        $(function () {
            // 双击添加
            let index = {{count($oldTypes) + count($oldItems)}}; //计数器，双击一次加1
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