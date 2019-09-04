<li>
    <a href="{{route('notices.notice')}}">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning" id="notice-number">0</span>
    </a>
</li>
<script>
    let getNoticeNumber = function(){
        let notice = $('#notice-number');
        if (notice.html() == 0) {
            notice.hide();
        }
        $.get('{{route('notices.notice-number')}}', '', function (data) {
            if (data.status === 1) {
                notice.html(data.count);
                if (data.count > 0){
                    notice.show();
                } else {
                    notice.hide();
                }
            } else {
                notice.hide();
            }
        }, 'json')
    }
    setInterval(getNoticeNumber, 5 * 60 * 1001);
    getNoticeNumber()
</script>
