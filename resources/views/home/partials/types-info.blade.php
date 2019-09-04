<div class="col-md-5">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">类型</h3>
        </div>
        <div class="box-body">
            <div id="pie-chart" style="width: 100%;height:268px;"></div>
        </div>
    </div>
</div>
<div class="col-md-7">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">明细</h3>
        </div>
        <div class="box-body" style="padding:6px;min-height: 288px;">
            @foreach($types as $type)
                <div class="col-md-2" style="padding:2px;">
                    <div class="small-box" style="margin:0px;background-color: #00c0ef">
                        <div class="inner" style="padding:5px;">
                            <p class="inner-title" title="{{ $type->title }}">{{ $type->title }}</p>
                            <p class="inner-text">总房：{{ $type->rooms_count }}</p>
                            <p class="inner-text">已用：{{ $type->rooms_used_count }}</p>
                            <p class="inner-text">人数：{{ $type->people_count }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('home.scripts')
<script>
    $(function () {
        let pieChartDiv = document.getElementById('pie-chart');
        let pieChart = echarts.init(pieChartDiv);
        let pieChartOption = {
            animation: false,
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:[]
            },
            series: [
                {
                    name:'房间总数',
                    type:'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '30',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data:[]
                }
            ]
        };

        @foreach ($types as $type)
            pieChartOption.legend.data.push('{{$type->title}}');
            pieChartOption.series[0].data.push({
                value: {{$type->rooms_count}},
                name: '{{$type->title}}'
            });
        @endforeach

        pieChart.setOption(pieChartOption);
    })
</script>
@endpush