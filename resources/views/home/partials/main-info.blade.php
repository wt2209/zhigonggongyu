<div class="col-md-6">
    <div class="main-info-box" style="border-left: 12px solid #00c0ef">
        <div class="info-box-content" style="margin:0">
            <span class="info-box-text">当前总人数</span>
            <span class="info-box-number">{{ $types->sum('people_count') }}</span>
        </div>
        <!-- /.info-box-content -->
    </div>
</div>
<div class="col-md-6">
    <div class="main-info-box" style="border-left: 12px solid #00c0ef">
        <div class="info-box-content" style="margin:0">
            <span class="info-box-text">已用房间数</span>
            <span class="info-box-number">{{ $types->sum('rooms_used_count') }}</span>
        </div>
        <!-- /.info-box-content -->
    </div>
</div>
<div class="col-md-6">
    <div class="main-info-box" style="border-left: 12px solid #f39c12">
        <div class="info-box-content" style="margin:0">
            <span class="info-box-text">本月维修量</span>
            <span class="info-box-number">{{ count($currentMonthRepairs) }}</span>
        </div>
        <!-- /.info-box-content -->
    </div>
</div>
<div class="col-md-6">
    <div class="main-info-box" style="border-left: 12px solid #f39c12">
        <div class="info-box-content" style="margin:0">
            <span class="info-box-text">30天内当日完工率</span>
            <span class="info-box-number">{{$finishedRate}}%</span>
        </div>
        <!-- /.info-box-content -->
    </div>
</div>
<div class="col-md-6">
    <div class="main-info-box" style="border-left: 12px solid #dd4b39">
        <div class="info-box-content" style="margin:0">
            <span class="info-box-text">本月已收费用</span>
            <span class="info-box-number">{{ $currentMonthCosts }}</span>
        </div>
        <!-- /.info-box-content -->
    </div>
</div>