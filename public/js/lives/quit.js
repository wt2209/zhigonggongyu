$('.quit').unbind('click').click(function() {
    let url = $(this).data('url');

    swal({
            title: "确定要退房吗?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            closeOnConfirm: true,
            cancelButtonText: "取消"
        },
        function(){
            $.ajax({
                method: 'post',
                url: url,
                data: {
                    _method:'put',
                    _token:LA.token,
                },
                success: function (data) {
                    $.pjax.reload('#pjax-container');
                    if (typeof data === 'object') {
                        if (data.status) {
                            swal(data.message, '', 'success');
                        } else {
                            swal(data.message, '', 'error');
                        }
                    }
                }
            });
        });
});