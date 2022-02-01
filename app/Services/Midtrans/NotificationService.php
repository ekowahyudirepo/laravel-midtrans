<?php

namespace App\Services\Midtrans;

use Midtrans\Notification;

class NotificationService extends Midtrans
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getResponse()
    {

        $res = new Notification();

        $result = $res->getResponse();

        return (object)$result;
    }
}
