<div class="col-md-12">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">楼栋使用情况</h3>
        </div>
        <div class="box-body">
            <div id="barChart" style="width: 100%;height: 200px;"></div>
        </div>
    </div>
</div>
@push('home.scripts')
<script>
    $(function () {
        let rooms = JSON.parse('{!! $rooms !!}');
        let barChart = echarts.init(document.getElementById('barChart'));
        let barChartOption = {
            animation: false,
            color: ['#D2D6DE', '#00a65a'],
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            grid: {
                left: '0',
                right: '0',
                bottom: '0',
                top: '10%',
                containLabel: true
            },
            legend: {
                data: ['空房', '已用']
            },
            calculable: true,
            xAxis: [
                {
                    type: 'category',
                    axisTick: {show: false},
                    data: rooms.buildings
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
                {
                    name: '空房',
                    type: 'bar',
                    barGap: 0,
                    data: rooms.empty
                },
                {
                    name: '已用',
                    type: 'bar',
                    data: rooms.used
                },
            ]
        };
        barChart.setOption(barChartOption);
    })
</script>
@endpush
