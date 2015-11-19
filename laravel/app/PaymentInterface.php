<?php

namespace App;


interface PaymentInterface
{
    public function postPayment($request);

    public function getPaymentStatus($request);

    public function getPaymentCancel($request);
}
