<?php

namespace App\web_checkout;

use App\Models\Order;

class WebCheckout
{

    public static function createSession()
    {
        $responseCurl = CreateSession::makeRequest();
        $response = $responseCurl['response'];
        $error = $responseCurl['error'];
        $responseDecode = '';
        if (!$error)
        {
            $responseDecode = $response;
            if ($responseDecode->status->status == 'OK')
            {
                return ['processUrl'=>$responseDecode->processUrl, 'requestId'=>$responseDecode->requestId];
            }
        }else{
            return $error;
        }
    }

    public static function checkSession(Order $order)
    {
        $requestId = $order->session_id;
        if(!$requestId)
        {
            return false;
        }
        $responseCurl = CheckSession::makeRequest($requestId);
        $response = $responseCurl['response'];
        $error = $responseCurl['error'];
        return ['response'=>$response, 'error'=>$error];
        
    }

    public static function makeTransactionOperation($internalReference, $action)
    {
        $responseTransaction = TransactionOperations::makeRequest($internalReference, $action);
        $response = $responseTransaction['response'];
        $error = $responseTransaction['error'];
        return ['response'=>$response, 'error'=>$error];
    }

    public static function validateOrderStatus($numeroTarjeta)
    {
        $orderStatus = '';
        $paymentStatus = '';
        switch (preg_replace(["/\t/","/\s/"],'',$numeroTarjeta)) {
            case '4005580000000040':
            case '4215440000000001':
            case '5907120000000009':
            case '6372000000000007':
                $paymentStatus = env('REJECTED');
                $orderStatus = Order::REJECTED;
                break;
            case '5424000000000015':
            case '5406251000000008':
            case '370000000000002':
            case '36018623456787':
            case '8130010000000000':
            case '4111111111111111':
            case '4007000000027':
                $paymentStatus = env('APPROVED');
                $orderStatus = Order::PAYED;
                break;
            case '4212121212121214':
                $paymentStatus = env("PENDING_VALIDATION");
                $orderStatus = Order::PAYED;
                break;
            case '4666666666666669':
                sleep(185);
                $paymentStatus = env("PENDING_PROCESS");
                $orderStatus = Order::PAYED;
                break;
        }
        return ['orderStatus'=>$orderStatus, 'paymentStatus'=>$paymentStatus];
    }
}