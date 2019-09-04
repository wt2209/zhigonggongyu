<style>
    .btn-toolbar{
        width: 300px;
        margin: 10px 0 0 15px;
        display: inline-block;
        float: left;
    }
    .btn-group a{
        position: relative;
    }
    .btn-group a .label{
        position: absolute;
        top: -7px;
        right: 3px;
        text-align: center;
        font-size: 9px;
        padding: 2px 3px;
        line-height: .9;
    }
</style>
<div class="btn-toolbar" role="toolbar" style="margin-top:14px;">
    <div class="btn-group" role="group" >
        <a href="{{route('lives.index')}}" pjax-container type="button" class="btn btn-success btn-xs">
            在住信息
        </a>
    </div>
    <div class="btn-group" role="group" >
        <a href="{{route('repairs.create')}}" type="button" pjax-container class="btn btn-warning btn-xs">
            报修
        </a>
        <a href="{{route('repairs.unreviewed')}}" type="button" pjax-container class="btn btn-warning btn-xs">
            审核
            <span class="label label-danger" id="unreviewed-number"></span>
        </a>
        <a href="{{route('repairs.unprinted')}}" type="button" pjax-container class="btn btn-warning btn-xs">
            打印
            <span class="label label-danger" id="unprinted-number"></span>
        </a>
    </div>
    <div class="btn-group" role="group" >
        <a href="{{ route('bills.index') }}" type="button" pjax-container class="btn btn-info btn-xs">
            明细
        </a>
        <a href="{{ route('bills.statistics', ['years[]' => date('Y'), 'months[]' => date('m'), 'days[]' => date('d')]) }}" type="button" pjax-container class="btn btn-info btn-xs">
            统计
        </a>
        <a href="{{ route('bills.charge-page') }}" type="button" pjax-container class="btn btn-info btn-xs">
            缴费
        </a>
    </div>
    @if(Admin::user()->inRoles(['repair-review-notice', 'repair-print-notice']))
        <audio src="{{asset('sound/notice.wav')}}" id="notice-sound"></audio>
    @endif
    <script>
        $(function () {
            let getRepairNumber = function(){
                let unreviewed = $('#unreviewed-number');
                let unprinted = $('#unprinted-number');
                if (unreviewed.html() == 0) {
                    unreviewed.hide();
                }
                if (unprinted.html() == 0) {
                    unprinted.hide();
                }
                $.get('{{route('notices.repair-number')}}', '', function (data) {
                    if (data.status === 1) {
                        unreviewed.html(data.unreviewed);
                        unprinted.html(data.unprinted);
                        data.unreviewed > 0 ? unreviewed.show() : unreviewed.hide();
                        data.unprinted > 0 ? unprinted.show() : unprinted.hide();

                        let sound = $("#notice-sound");
                        @if (Admin::user()->isRole('repair-review-notice'))
                            if (data.unreviewed > 0 && sound.length > 0) {
                                document.getElementById('notice-sound').play();
                            }
                        @endif
                        @if (Admin::user()->isRole('repair-print-notice'))
                            if (data.unprinted > 0 && sound.length > 0) {
                                document.getElementById('notice-sound').play();
                            }
                        @endif

                    } else {
                        unreviewed.hide();
                        unprinted.hide();
                    }
                }, 'json')
            };
            setInterval(getRepairNumber, 5 * 60 * 999);
            getRepairNumber()
        })
    </script>
</div>