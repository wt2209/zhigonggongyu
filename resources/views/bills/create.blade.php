<div class="row">
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> 说明 </h4>
            输入的金额是负数时，就相当于是退费。保存时，系统会自动将退费项目的“缴费时间”填写为当前时间。
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">编辑</h3>
                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 10px">
                        <a href="{{route('bills.index')}}" class="btn btn-sm btn-default">
                            <i class="fa fa-list"></i>
                            &nbsp;明细
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form action="{{route('bills.store')}}" method="post" accept-charset="UTF-8" class="form-horizontal" pjax-container>
                {{ csrf_field() }}
                <input type="hidden" name="_previous_" value="{{\URL::previous() ?: ''}}">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="javascript:void(0);" class="btn btn-sm btn-success" id="create-table-row">
                                添加一行
                            </a>
                        </div>

                    </div>
                    @if(count($errors->messages()) > 0)
                        <div class="has-error" style="padding-left: 10px;">
                            @foreach(array_slice($errors->messages(), 0, 1) as $message)
                                <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{$message[0]}}</label><br/>
                            @endforeach
                        </div>
                    @endif
                    <table class="table table-hover" id="create-table">
                        <tbody>
                            @if (old('items') !== null)
                                @foreach (old('items') as $k => $item)
                                    <tr>
                                        <td width="150">
                                            <input type="text" class="form-control" value="{{$item['location']}}" name="items[{{$k}}][location]" placeholder="房间号/位置">
                                        </td>
                                        <td width="150">
                                            <input type="text" class="form-control" value="{{$item['name']}}" name="items[{{$k}}][name]" placeholder="姓名">
                                        </td>
                                        <td width="150">
                                            <input list="bill-type-list" type="text" class="form-control" value="{{$item['bill_type']}}" name="items[{{$k}}][bill_type]" placeholder="费用类型">
                                        </td>
                                        <td width="150">
                                            <input type="text" class="form-control" value="{{$item['cost']}}" name="items[{{$k}}][cost]" placeholder="金额">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="{{$item['explain']}}" name="items[{{$k}}][explain]" placeholder="费用说明">
                                        </td>
                                        <td style="line-height: 35px;padding-left: 4px;">
                                            <a href="javascript:void(0);" class="btn btn-xs btn-danger remove-table-row">
                                                删除
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td width="150">
                                        <input type="text" class="form-control" name="items[0][location]" placeholder="房间号/位置">
                                    </td>
                                    <td width="150">
                                        <input type="text" class="form-control" name="items[0][name]" placeholder="姓名">
                                    </td>
                                    <td width="150">
                                        <input list="bill-type-list" type="text" class="form-control" name="items[0][bill_type]" placeholder="费用类型">
                                    </td>
                                    <td width="150">
                                        <input type="text" class="form-control" name="items[0][cost]" placeholder="金额">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="items[0][explain]" placeholder="费用说明">
                                    </td>
                                    <td style="line-height: 35px;padding-left: 4px;">
                                        <a href="javascript:void(0);" class="btn btn-xs btn-danger remove-table-row">
                                            删除
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <datalist id="bill-type-list">
                        @foreach($billTypes as $billType)
                            <option>{{$billType}}</option>
                        @endforeach
                    </datalist>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="btn-group pull-right">
                            <button type="submit" class="btn btn-info pull-right"
                                    data-loading-text="<i class='fa fa-spinner fa-spin '></i> 保存">保存
                            </button>
                            <label class="pull-right" style="line-height: 36px;margin-right: 10px;">
                                <input type="checkbox" name="pay" @if (old('pay') === 'on') checked @endif> 录入时缴费
                            </label>
                        </div>
                    </div>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
    </div>
</div>
<script>
    function addRow(index) {
        let table = $('#create-table');
        let tr = document.createElement('tr');
        let str = `
            <td>
                <input type="text" class="form-control" name="items[${index}][location]" placeholder="房间号/位置">
            </td>
            <td>
                <input type="text" class="form-control" name="items[${index}][name]" placeholder="姓名">
            </td>
            <td>
                <input list="bill-type-list" type="text" class="form-control" name="items[${index}][bill_type]" placeholder="费用类型">
            </td>
            <td>
                <input type="text" class="form-control" name="items[${index}][cost]" placeholder="金额">
            </td>
            <td>
                <input type="text" class="form-control" name="items[${index}][explain]" placeholder="费用说明">
            </td>
            <td style="line-height: 35px;padding-left: 4px;">
                <a href="javascript:void(0);" class="btn btn-xs btn-danger remove-table-row">
                    删除
                </a>
            </td>
        `;
        $(tr).html(str);
        table.append(tr);
    }

    $(function () {
        let index = {{old('items') !== null ? max(array_keys(old('items'))) : 1}};
        $('#create-table-row').click(function () {
            addRow(index);
            index++;
        });

        // 点击清空
        $('input[list=bill-type-list]').focus(function () {
            $(this).val('')
        });

        // 动态绑定
        $("#create-table").delegate(".remove-table-row", "click", function(){
            $(this).parents('tr').remove();
        });
    });
</script>
