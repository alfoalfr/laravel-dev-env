<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{

    public function postPayment($service)
    {
        if (array_key_exists($service, config('payment.services'))){
            $serviceClassName = config('payment.services')[$service];
            $serviceClass = new $serviceClassName();
            return $serviceClass->postPayment(request());
        }
        //TODO: error handling
        return 'error';
    }

    public function getPaymentStatus($service)
    {
        if (array_key_exists($service, config('payment.services'))){
            $serviceClassName = config('payment.services')[$service];
            $serviceClass = new $serviceClassName();
            return $serviceClass->getPaymentStatus(request());
        }
        //TODO: error handling
        return 'error';
    }

    public function getPaymentCancel($service)
    {
        if (array_key_exists($service, config('payment.services'))){
            $serviceClassName = config('payment.services')[$service];
            $serviceClass = new $serviceClassName();
            return $serviceClass->getPaymentCancel(request());
        }
        //TODO: error handling
        return 'cancel';
    }
}
