<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home.index');
    $router->get('search', 'HomeController@search')->name('home.search');

    $router->put('rooms/remark', 'RoomController@updateRemark')->name('rooms.remark');
    $router->resource('rooms', 'RoomController')
        ->only(['index', 'edit', 'update']);

    $router->resource('people', 'PersonController')->only(['index']);
    $router->get('people/by_identify', 'PersonController@byIdentify')->name('people.identify');

    $router->resource('records', 'RecordController')->only(['index']);

    $router->patch('types/disable', 'TypeController@disable')->name('types.disable');
    $router->patch('types/enable', 'TypeController@enable')->name('types.enable');
    $router->resource('types', 'TypeController')
        ->only(['index', 'edit', 'create', 'store', 'update']);

    $router->get('type_histories', 'TypeHistoryController@index')
        ->name('type_histories.index');

    $router->get('lives', 'LiveController@index')->name('lives.index');
    $router->get('lives/{id}/edit','LiveController@edit')->name('lives.edit');
    $router->put('lives/{id}', 'LiveController@update')->name('lives.update');
    $router->get('lives/create', 'LiveController@create')->name('lives.create');
    $router->post('lives', 'LiveController@store')->name('lives.store');
    $router->get('lives/{id}/change', 'LiveController@change')->name('lives.change');
    $router->get('lives/{id}/prolong', 'LiveController@prolong')->name('lives.prolong');
    $router->put('lives/{id}/move', 'LiveController@move')->name('lives.move');
    $router->put('lives/{id}/quit', 'LiveController@quit')->name('lives.quit');
    $router->put('lives/{id}/renew', 'LiveController@renew')->name('lives.renew');

    $router->get('renewals', 'RenewalController@index')->name('renewals.index');

    $router->get('notices/notice', 'NoticeController@notice')->name('notices.notice');
    $router->get('notices/notice-number', 'NoticeController@noticeNumber')->name('notices.notice-number');
    $router->get('notices/repair-number', 'NoticeController@repairNumber')->name('notices.repair-number');

    $router->resource('repair_items', 'RepairItemController')
        ->only(['index', 'edit', 'update', 'create', 'store']);

    $router->resource('repair_types', 'RepairTypeController')
        ->only(['index', 'edit', 'update', 'create', 'store']);

    $router->get('repairs/create', 'RepairController@create')->name('repairs.create');
    // 已报修记录
    $router->get('repairs.history', "RepairController@history")->name('repairs.history');
    $router->get('repairs/{id}/show', 'RepairController@show')->name('repairs.show');
    $router->get('repairs/{id}/edit', 'RepairController@edit')->name('repairs.edit');
    // 不使用(put)repairs/{id}格式的路由，为了避免与 (put)repairs/review等路由冲突，防止报No query results for model错误
    // 也可对id进行正则限制解决此问题
    $router->put('repairs/{id}/update', 'RepairController@update')->name('repairs.update');
    $router->post('repairs', 'RepairController@store')->name('repairs.store');
    $router->get('repairs/unreviewed', 'RepairController@unreviewed')->name('repairs.unreviewed');
    $router->get('repairs/unpassed', 'RepairController@unpassed')->name('repairs.unpassed');
    $router->get('repairs/unprinted', 'RepairController@unprinted')->name('repairs.unprinted');
    $router->get('repairs/unfinished', 'RepairController@unfinished')->name('repairs.unfinished');
    $router->get('repairs/finished', 'RepairController@finished')->name('repairs.finished');
    $router->get('repairs/{id}/review', 'RepairController@reviewPage')->name('repairs.review-page');
    $router->put('repairs/review', 'RepairController@review')->name('repairs.review');
    $router->get('repairs/{id}/finish', 'RepairController@finishPage')->name('repairs.finish-page');
    $router->put('repairs/finish', 'RepairController@finish')->name('repairs.finish');
    $router->put('repairs/batch_review', 'RepairController@batchReview')->name('repairs.batch-review');
    $router->put('repairs/batch_finish', 'RepairController@batchFinish')->name('repairs.batch-finish');
    $router->put('repairs/{id}/print', 'RepairController@print')->name('repairs.print');
    $router->get('repairs/{id}/material', 'RepairController@materialPage')->name('repairs.material-page');
    $router->post('repairs/material', 'RepairController@material')->name('repairs.material');
    $router->get('repairs/{id}/show', 'RepairController@show')->name('repairs.show');
    $router->delete('repairs/{id}', 'RepairController@destroy')->name('repairs.destroy');

    $router->get('bills', 'BillController@index')->name('bills.index');
    $router->get('bills/{id}/edit', 'BillController@edit')->name('bills.edit');
    $router->get('bills/{id}/show', 'BillController@show')->name('bills.show');
    $router->get('bills/create', 'BillController@create')->name('bills.create');
    $router->post('bills', 'BillController@store')->name('bills.store');
    // 加上id格式，防止冲突
    $router->put('bills/{id}', 'BillController@update')->where('id', '[0-9]+')->name('bills.update');
    $router->delete('bills/{id}', 'BillController@destroy')->name('bills.destroy');
    $router->get('bills/import', 'BillController@importPage')->name('bills.import-page');
    $router->get('bills/import/error', 'BillController@importErrorPage')->name('bills.import-error');
    $router->post('bills/import', 'BillController@import')->name('bills.import');
    $router->get('bills/charge', 'BillController@chargePage')->name('bills.charge-page');
    $router->put('bills/charge', 'BillController@charge')->name('bills.charge');
    $router->get('bills/statistics', 'BillController@statistics')->name('bills.statistics');

    $router->patch('bill_types/disable', 'BillTypeController@disable')->name('bill_types.disable');
    $router->patch('bill_types/enable', 'BillTypeController@enable')->name('bill_types.enable');
    $router->resource('bill_types', 'BillTypeController')
        ->only(['index', 'edit', 'create', 'store', 'update']);
});
