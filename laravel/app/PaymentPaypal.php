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
        $data = (object)$request->all();

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $items = [];
        $totalPrice = 0;

       foreach (json_decode($data->items) as $item){
            $item = (object)($item);
            $savedProduct = Products::find($item->id);
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
        if (isset($data->transactionDescription) && $data->transactionDescription != null){
            $transaction->setDescription($data->transactionDescription);
        }else{
            $transaction->setDescription(config('payment.globalTransactionDescription', ''));
        }

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::action('PaymentController@getPaymentStatus', ['service', 'paypal'])) // Specify return URL
        ->setCancelUrl(URL::action('PaymentController@getPaymentCancel', ['service', 'paypal']));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (PayPalConnectionException $ex) {
            //TODO: error handling
            if (Config::get('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $err_data = json_decode($ex->getData(), true);
                dd($err_data);
                exit;
            } else {
                die('Some error occur, sorry for inconvenient');
            }
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        Session::put('paypal_payment_id', $payment->getId());

        if(isset($redirect_url)) {
            // redirect to paypal
            return redirect()->away($redirect_url);
        }

        //TODO: error handling
        return 'error';
    }

    public function getPaymentStatus($request)
    {
        // Get the payment ID before session clear
        $paymentId = Session::get('paypal_payment_id');
        $payerId = $request->get('PayerID');
        $token = $request->get('token');

        // clear the session payment ID
        Session::forget('paypal_payment_id');

        if (empty($payerId) || empty($token)) {
            //TODO: error handling
            return 'error - Payment failed';
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

        dd($result);

        if ($result->getState() == 'approved') { // payment made
            //TODO: success handling
            return 'success - Payment success';
        }
        //TODO: error handling
        return 'error - Payment failed';
    }

    public function getPaymentCancel($request)
    {
        //TODO: cancel handling
        return 'success - Payment success';
    }
}
