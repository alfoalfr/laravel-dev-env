<?php

namespace App;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use stdClass;

class PaymentPaypal implements PaymentInterface
{

    private $_api_context;

    public function __construct()
    {
        // setup PayPal api context
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function postPayment($request)
    {
        $requestItems = $request->json('items');
        $requestTransactionDescription = $request->json('transactionDescription');
        $requestCallback = $request->json('callback');

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $items = [];
        $totalPrice = 0;


        foreach ($requestItems as $item){
            $item = (object)($item);
            $savedProduct = Product::find($item->id);
           if ($savedProduct !== null){
               $quantity = isset($item->quantity) === true ? $item->quantity : '1';

               $tempItem = new Item();
               $tempItem->setName($savedProduct->name)
               ->setCurrency(config('payment.currency', 'BRL'))
                   ->setQuantity($quantity)
                   ->setPrice($savedProduct->price);

               array_push($items, $tempItem);

               $totalPrice+=floatval($tempItem->price)*$quantity;
           }
        }

        // add items to list
        $item_list = new ItemList();
        $item_list->setItems($items);

        $amount = new Amount();
        $amount->setCurrency(config('payment.currency', 'BRL'))
            ->setTotal(floatval($totalPrice));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list);
        if (isset($requestTransactionDescription) && $requestTransactionDescription != null){
            $transaction->setDescription($requestTransactionDescription);
        }else{
            $transaction->setDescription(config('payment.globalTransactionDescription', ''));
        }

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::action('PaymentController@getPaymentStatus', ['paypal']))
        ->setCancelUrl(URL::action('PaymentController@getPaymentCancel', ['paypal']));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (PayPalConnectionException $ex) {
            $result = new StdClass();
            $result->success = false;
            $result->message = 'internal-server-error';
            return view('payment.pay')->with([
                "result" => json_encode($result)
            ]);
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        Session::put('paypal_payment_id', $payment->getId());
        // add callback to the session
        if (isset($requestCallback)){
            Session::put('paymentCallback', $requestCallback);
        }

        if(isset($redirect_url)) {
            // redirect to paypal
            return json_encode(["url" => $redirect_url]);
        }

        $result = new StdClass();
        $result->success = false;
        $result->message = 'internal-server-error';
        return view('payment.pay')->with([
            "result" => json_encode($result)
        ]);
    }

    public function getPaymentStatus($request)
    {
        $result = new StdClass();

        // Get the payment ID before session clear
        $paymentId = Session::get('paypal_payment_id');
        $payerId = $request->get('PayerID');
        $token = $request->get('token');
        $callback = Session::has('paymentCallback') ? Session::get('paymentCallback') : '';

        // clear the session payment ID
        Session::forget('paypal_payment_id');

        if (empty($payerId) || empty($token)) {
            $result->success = false;
            $result->message = 'internal-server-error';
            return view('payment.pay')->with([
                "result" => json_encode($result)
            ]);
        }

        $payment = Payment::get($paymentId, $this->_api_context);

        // PaymentExecution object includes information necessary
        // to execute a PayPal account payment.
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        //Execute the payment
        $result = $payment->execute($execution, $this->_api_context);

        $result->paymentCallback = $callback;

        if ($result->getState() == 'approved') { // payment made
            $result->success = true;
        }else{
            $result->success = false;
        }

        return view('payment.pay')->with([
            "result" => $result
        ]);
    }

    public function getPaymentCancel($request)
    {
        $result = new stdClass();
        $result->param = 'avoid-callback';

        return view('payment.pay')->with([
            "result" => json_encode($result)
        ]);
    }
}
