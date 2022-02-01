<?php

namespace App\Services\Midtrans;

use Midtrans\Notification;

class NotificationService extends Midtrans
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getStatus()
    {

        $result = new Notification();

        return $result;
    }
}
