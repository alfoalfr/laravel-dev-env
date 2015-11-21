<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use stdClass;

class PaymentController extends Controller
{

    public function postPayment($service)
    {
        if (array_key_exists($service, config('payment.services'))){
            $serviceClassName = config('payment.services')[$service];
            $serviceClass = new $serviceClassName();
            return $serviceClass->postPayment(request());
        }

        $result = new StdClass();
        $result->success = false;
        $result->message = 'internal-server-error';
        return view('payment.pay')->with([
            "result" => json_encode($result)
        ]);
    }

    public function getPaymentStatus($service)
    {
        if (array_key_exists($service, config('payment.services'))){
            $serviceClassName = config('payment.services')[$service];
            $serviceClass = new $serviceClassName();
            return $serviceClass->getPaymentStatus(request());
        }

        $result = new StdClass();
        $result->success = false;
        $result->message = 'internal-server-error';
        return view('payment.pay')->with([
            "result" => json_encode($result)
        ]);
    }

    public function getPaymentCancel($service)
    {
        if (array_key_exists($service, config('payment.services'))){
            $serviceClassName = config('payment.services')[$service];
            $serviceClass = new $serviceClassName();
            return $serviceClass->getPaymentCancel(request());
        }

        $result = new StdClass();
        $result->success = false;
        $result->message = 'internal-server-error';
        return view('payment.pay')->with([
            "result" => json_encode($result)
        ]);
    }
}
