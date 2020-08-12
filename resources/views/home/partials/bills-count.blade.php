<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">
            今日已收费用：
            <span class="pull-right text-bold">￥ {{ array_sum($currentDayBillsStatistics) }}</span>
        </h3>

    </div>
    <div class="box-body" style="height:226px;">
        <div id="verticalBarChart" style="width: 100%;"></div>
       {{-- <ul class="repairing-list">
            @foreach ($currentDayBillsStatistics as $type => $costs)
                <li>
                    {{ $type }} ： ￥ {{ $costs }}
                </li>
            @endforeach
        </ul>--}}
    </div>
</div>

<script>

    $(function () {

        let verticalBarChartOption = {
            animation: false,
            color: ['#f39c12'],
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            grid: {
                left: '0',
                right: '5%',
                bottom: '0',
                top: '0',
                containLabel: true
            },
            xAxis: {
                type: 'value',
                boundaryGap: [0, 0.01]
            },
            yAxis: {
                type: 'category',
                data: []
            },
            series: [
                {
                    type: 'bar',
                    data: []
                }
            ]
        };
        @foreach ($currentDayBillsStatistics as $type => $costs)
            verticalBarChartOption.yAxis.data.push('{{$type}}');
            verticalBarChartOption.series[0].data.push({{$costs}});
        @endforeach

        // 如果有值才渲染
        if (verticalBarChartOption.series[0].data.length > 0) {
            let verticalBarChart = echarts.init(document.getElementById('verticalBarChart'));
            verticalBarChart.setOption(verticalBarChartOption);
        }
    });
    // app.title = '世界人口总量 - 条形图';



</script>
