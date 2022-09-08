<?php

namespace App\Admin\Extensions\Buttons;

use App\Models\Repair;
use Encore\Admin\Admin;

class RepairPrintButton
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function script()
    {
        return <<<SCRIPT
            $('.grid-row-print').unbind('click').click(function() {
                var id = $(this).data('id');
                let name = $(this).data('name');
                let location = $(this).data('location');
                let content = $(this).data('content');
                let phone = $(this).data('phone');
                let created = $(this).data('created');
                let remark = $(this).data('remark');
                let url = $(this).data('url');
                let reviewed = $(this).data('reviewed');

                let div = document.createElement('div');
                
                let table = document.createElement('table');
                $(table).addClass('table').addClass('table-print');
 
                let tr1 = document.createElement('tr');
                $(tr1).html('<th width="100">房间/地点</th><td>' + location + '</td><th width="100">报修人</th><td width="100">' + name + '</td><th width="100">联系电话</th><td width="120">' + phone + '</td>')
                
                let tr2 = document.createElement('tr');
                $(tr2).html('<th>报修内容</th><td colspan="3">' + content + '</td><th>预约时间</th><td></td>')

                let tr3 = document.createElement('tr');
                $(tr3).html('<th>审核人</th><td colspan="3">管理室</td><th>审核时间</th><td style="padding-left:3px;padding-right:3px;">' + reviewed + '</td>')

                let tr4 = document.createElement('tr');
                $(tr4).html('<th>维修内容</th><td colspan="5"></td>')

                let tr5 = document.createElement('tr');
                $(tr5).html('<th>使用材料</th><td colspan="5"></td>')

                let tr6 = document.createElement('tr');
                $(tr6).html('<th>维修人员</th><td></td><th>完工时间</th><td></td><th>完工签字</th><td></td>')

                table.appendChild(tr1);
                table.appendChild(tr2);
                table.appendChild(tr3);
                table.appendChild(tr4);
                table.appendChild(tr5);
                table.appendChild(tr6);
                
                let prepend = document.createElement('p');
                $(prepend).addClass('print-title');
                $(prepend).html('设施、设备报修单');
                

                div.appendChild(prepend);
                div.appendChild(table);
               
                $(div).print();
            
            
                $.ajax({
                    method: 'post',
                    url: url,
                    data: {
                        _method:'PUT',
                        _token:LA.token,
                    },
                    success: function (data) {
                        $.pjax.reload('#pjax-container');
                    }
                });
            });
SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());
        $repair = Repair::findOrFail($this->id);
        $url = route('repairs.print', ['id' => $this->id]);

        $reviewed_at = substr($repair->reviewed_at, 0, 10);
        return <<<HTML
            <button data-id="{$this->id}" 
                data-name="{$repair->name}" 
                data-location="{$repair->location}"
                data-content="{$repair->content}"
                data-created="{$repair->created_at}"
                data-phone="{$repair->phone_number}"
                data-remark="{$repair->review_remark}"
                data-reviewed="{$reviewed_at}" 
                data-url="{$url}"
                style="margin-right: 4px;"
                class="grid-row-print btn btn-xs btn-success" >
                打印
            </button>
HTML;
    }

    public function __toString()
    {
        return $this->render();
    }
}
