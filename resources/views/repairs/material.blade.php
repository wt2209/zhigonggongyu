<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">项目总金额</h3>
    </div>
    <div class="box-body">
        <table class="table no-border">
            <tr>
                <th>用工：{{$typesTotal}} 元 </th>
                <th>用料：{{$itemsTotal}} 元</th>
                <th>总计：{{$itemsTotal + $typesTotal}} 元</th>
            </tr>
        </table>
    </div>
</div>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">用工</h3>
    </div>
    <div class="box-body">
        <table class="table table-hover" id="type-table">
            <tbody>
            @foreach($oldTypes as $k => $oldType)
                <tr>
                    <td>
                        {{ $oldType->type->title }}
                    </td>
                    <td style="width: 120px;">
                        单价：{{ $oldType->price }}
                    </td>
                    <td style="width: 100px;">
                        用量：{{ $oldType->total }}
                    </td>
                    <td>
                        {{$oldType->remark}}
                    </td>
                    <td width="110">
                        总价：{{$oldType->price * $oldType->total}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">用料</h3>
    </div>
    <div class="box-body">
        <table class="table table-hover" id="item-table">
            <tbody>
            @foreach($oldItems as $k => $oldItem)
                <tr>
                    <td>
                        {{$oldItem->item->name }}
                    </td>
                    <td>
                        {{$oldItem->item->feature }}
                    </td>
                    <td style="width: 120px;">
                        单价：{{$oldItem->price}}
                    </td>
                    <td style="width: 100px;">
                       用量：{{$oldItem->total}}
                    </td>
                    <td>
                        {{$oldItem->remark}}
                    </td>
                    <td width="110">
                        总价：{{$oldItem->price * $oldItem->total}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
