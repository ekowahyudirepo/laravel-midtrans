<?php

namespace App\Services\Midtrans;

use Midtrans\Transaction;

class StatusTransactionService extends Midtrans
{
    protected $order_id;

    public function __construct($order_id)
    {
        parent::__construct();

        $this->order_id = $order_id;
    }

    public function getStatus()
    {

        $result = Transaction::status($this->order_id);

        return (object)$result;
    }
}
