<div class="box box-info" style="display: none;">
    <div class="box-header with-border">
        <h3 class="box-title">
            历史维修记录
            <span style="font-size: 12px;">(最多显示10项)</span>
        </h3>
    </div>
    <div class="box-body" id="repair-items"></div>
    <script>
        $(function (){
            let location = $('#location');
            location.blur(function () {
                if (location.val() !== '') {
                    $.ajax({
                        method: 'get',
                        url: '{{route('repairs.history')}}',
                        data: {
                            location:location.val(),
                        },
                        success: function (res) {
                            if (typeof res === 'object' && res.status) {
                                $('#repair-items').html("");
                                if (res.data.length > 0) {
                                    for (d of res.data) {
                                        let div = document.createElement('div');
                                        div.className = 'repair-item';
                                        let str = '<p>报修项目：'+(d.content || "")+'</p>';
                                        str += '<p>报修人：'+(d.name || "")+'</p>';
                                        str += '<p>报修时间：'+(d.created_at || "")+'</p>';
                                        str += '<p>完工时间：'+(d.finished_at || "")+'</p>';
                                        div.innerHTML = str;
                                        document.getElementById('repair-items').appendChild(div);
                                    }
                                    $('#repair-items').show().parent().show();
                                } else {
                                    $('#repair-items').parent().hide();
                                }
                            }
                        }
                    });
                }
            })
        })
    </script>
</div>