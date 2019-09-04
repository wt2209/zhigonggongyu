<?php

namespace App\Admin\Extensions\Tools\Bills;

use Encore\Admin\Grid\Tools\AbstractTool;

class ImportBills extends AbstractTool
{
    public function render()
    {
        $route = route('bills.import-page');

        return <<<EOT
            <a href="{$route}" class="btn btn-sm btn-success" style="margin-left:3px;">
                <i class="fa fa-upload"></i>&nbsp;&nbsp;批量导入
            </a>
EOT;
    }
}