<?php

namespace App\Admin\Extensions\Buttons;

use Encore\Admin\Admin;

class DeleteButton
{
    protected $id;
    protected $url;

    public function __construct($id, $routeName)
    {
        $this->id = $id;
        $this->url = route($routeName, ['id' => $this->id]);
    }

    public function script()
    {
        $deleteConfirm = trans('admin.delete_confirm');
        $confirm = trans('admin.confirm');
        $cancel = trans('admin.cancel');

        return <<<SCRIPT
            $('.grid-row-delete').unbind('click').click(function() {
                var id = $(this).data('id');
                var url = $(this).data('url');
            
                swal({
                  title: "$deleteConfirm",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "$confirm",
                  closeOnConfirm: true,
                  cancelButtonText: "$cancel"
                },
                function(){
                    $.ajax({
                        method: 'post',
                        url: url,
                        data: {
                            _method:'delete',
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
SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return <<<HTML
            <a href="javascript:void(0);" data-id="{$this->id}" data-url="{$this->url}"
                class="grid-row-delete btn btn-xs btn-danger">
                删除
            </a>
HTML;
    }

    public function __toString()
    {
        return $this->render();
    }
}