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
        $time = date('Y-m-d H:i:s');

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

                let div = document.createElement('div');
                
                let table = document.createElement('table');
                $(table).addClass('table').addClass('table-print');
                let titleTr = document.createElement('tr');
                let title = ['报修人', '地点', '报修内容', '报修时间', '电话', '审核意见'];
                for (i = 0; i < title.length; i++) {
                    let th = document.createElement('th');
                    $(th).html(title[i]);
                    titleTr.appendChild(th);
                }
                
                let contentTr = document.createElement('tr');
                
                let nameTd = document.createElement('td');
                $(nameTd).html(name);
                contentTr.appendChild(nameTd);
                
                let locationTd = document.createElement('td');
                $(locationTd).html(location);
                contentTr.appendChild(locationTd);
                
                let contentTd = document.createElement('td');
                $(contentTd).html(content);
                contentTr.appendChild(contentTd);
                
                let createTd = document.createElement('td');
                $(createTd).html(created);
                contentTr.appendChild(createTd);
                
                let phoneTd = document.createElement('td');
                $(phoneTd).html(phone);
                contentTr.appendChild(phoneTd);
                
                let remarkTd = document.createElement('td');
                $(remarkTd).html(remark);
                contentTr.appendChild(remarkTd);
                
                table.appendChild(titleTr);
                table.appendChild(contentTr);
                
                let prepend = document.createElement('p');
                $(prepend).addClass('print-title');
                $(prepend).html('维修申请单');
                
                let append = document.createElement('div');
                $(append).addClass('pull-right').addClass('print-append');
                $(append).html('打印时间：{$time}<br><br>维修完工验收：');
                
                div.appendChild(prepend);
                div.appendChild(table);
                div.appendChild(append);
                
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

        return <<<HTML
            <button data-id="{$this->id}" 
                data-name="{$repair->name}" 
                data-location="{$repair->location}"
                data-content="{$repair->content}"
                data-created="{$repair->created_at}"
                data-phone="{$repair->phone_number}"
                data-remark="{$repair->review_remark}" 
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
