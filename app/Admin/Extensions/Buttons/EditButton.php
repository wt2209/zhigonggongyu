<?php

namespace App\Admin\Extensions\Buttons;

class EditButton
{
    protected $id;
    protected $url;

    public function __construct($id, $routeName)
    {
        $this->id = $id;
        $this->url = route($routeName, ['id' => $this->id]);
    }

    protected function render()
    {
        return <<<EOT
            <a href="{$this->url}" class="btn btn-warning btn-xs">
                修改
            </a>
EOT;
    }

    public function __toString()
    {
        return $this->render();
    }
}