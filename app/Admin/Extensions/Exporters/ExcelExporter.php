<?php

namespace App\Admin\Extensions\Exporters;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExporter extends AbstractExporter
{
    protected $fileName = '文件下载';

    public function export()
    {
        $fileName = $this->fileName . '-' . date('Y-m-d');
        Excel::create($fileName, function($excel) {
            $excel->sheet($this->sheetName ?? 'Sheet1', function($sheet) {
                $sheet->rows($this->getExcelData());
            });
        })->export('xlsx');
    }
}