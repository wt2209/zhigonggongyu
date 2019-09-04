<?php
namespace App\Observers;


use App\Models\Bill;

class BillObserver
{
    public function updating(Bill $bill)
    {
        if ($bill->is_refund) {
            $bill->cost = -1 * abs($bill->cost);
        } else {
            $bill->cost = abs($bill->cost);
        }
    }
}