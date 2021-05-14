<div class="col-md-12">
    <div class="box box-info">
        <div class="box-body">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>类型</th>
                        <th>房间总数</th>
                        <th>已用数量</th>
                        <th>空房间数</th>
                        <th>在住人数</th>
                    </tr>
                </thead>
                @foreach($types as $type)
                    @if($type->rooms_count > 0 && $type->people_count > 0)
                    <tr>
                        <td>{{$type->title}}</td>
                        <td>{{$type->rooms_count}}</td>
                        <td>{{$type->rooms_used_count}}</td>
                        <td>{{$type->rooms_count - $type->rooms_used_count}}</td>
                        <td>{{$type->people_count}}</td>
                    </tr>
                    @endif
                @endforeach
                <tr>
                    <th>总计</th>
                    <th>
                        {{array_reduce($types->toArray(), function($sum, $type){
                            return $sum + $type['rooms_count'];
                        }, 0)}}
                    </th>
                    <th>
                        {{array_reduce($types->toArray(), function($sum, $type){
                            return $sum + $type['rooms_used_count'];
                        }, 0)}}
                    </th>
                    <th>
                        {{array_reduce($types->toArray(), function($sum, $type){
                            return $sum + $type['rooms_count'] - $type['rooms_used_count'];
                        }, 0)}}
                    </th>
                    <th>
                        {{array_reduce($types->toArray(), function($sum, $type){
                            return $sum + $type['people_count'];
                        }, 0)}}
                    </th>
                </tr>
            </table>
        </div>
    </div>
</div>