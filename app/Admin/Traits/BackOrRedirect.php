<?php
namespace App\Admin\Traits;

use Illuminate\Support\Facades\Input;

trait BackOrRedirect
{
    protected function backOrRedirectTo($routeName, $message = '')
    {
        $message = $message ?: trans('admin.update_succeeded');
        admin_toastr($message);
        $url = Input::get('_previous_') ?: route($routeName);
        return redirect($url);
    }
}