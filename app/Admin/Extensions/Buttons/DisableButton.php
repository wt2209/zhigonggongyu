<?php

namespace App\Admin\Extensions\Buttons;

use Encore\Admin\Admin;

class DisableButton
{
    protected $id;
    protected $url;

    public function __construct($id, $routeName)
    {
        $this->id = $id;
        $this->url = route($routeName);
    }

    public function script()
    {
        return <<<SCRIPT
            $('.grid-row-disable').unbind('click').click(function() {
                var id = $(this).data('id');
                var url = $(this).data('url');

                $.ajax({
                    method: 'post',
                    url: url,
                    data: {
                        _method:'patch',
                        id: id,
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

        return <<<HTML
            <a href="javascript:void(0);" data-id="{$this->id}" data-url="{$this->url}"
                class="grid-row-disable btn btn-xs btn-danger">
                禁用
            </a>
HTML;
    }

    public function __toString()
    {
        return $this->render();
    }
}