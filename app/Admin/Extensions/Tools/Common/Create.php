<?php

namespace App\Admin\Extensions\Tools\Common;

use Encore\Admin\Grid\Tools\AbstractTool;

class Create extends AbstractTool
{
    protected $routeName;

    public function __construct($routeName)
    {
        $this->routeName = $routeName;
    }

    public function render()
    {
        $route = route($this->routeName);

        return <<<EOT
            <a href="{$route}" class="btn btn-sm btn-success" style="margin-left:3px;">
                <i class="fa fa-save"></i>&nbsp;&nbsp;新增
            </a>
EOT;
    }
}