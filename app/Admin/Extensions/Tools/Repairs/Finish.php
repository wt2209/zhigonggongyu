<?php
/**
 * Created by PhpStorm.
 * User: WT
 * Date: 2018/9/10
 * Time: 14:42
 */

namespace App\Admin\Extensions\Tools\Repairs;

use Encore\Admin\Grid\Tools\BatchAction;

class Finish extends BatchAction
{
    public function __construct()
    {
    }

    public function script()
    {
        $route = route('repairs.batch-finish');
        return <<<EOT
$('{$this->getElementClass()}').on('click', function() {
    $.ajax({
        method: 'post',
        url: '{$route}',
        data: {
            _token:LA.token,
            _method: 'PUT',
            ids: selectedRows()
        },
        success: function (data) {
            if (data.status == 1) {
                $.pjax.reload('#pjax-container');
                toastr.success('操作成功');
            } else {
                toastr.error('操作失败');
            }
        }
    });
});
EOT;
    }
}