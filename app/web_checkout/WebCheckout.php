<?php

namespace App\web_checkout;

use App\Models\Order;

class WebCheckout
{

    public static function createSession()
    {
        $curl = CreateSession::makeRequest();
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response_decode = '';
        if (!$err)
        {
            $response_decode = json_decode($response);
            if ($response_decode->status->status == 'OK')
            {
                return ['processUrl'=>$response_decode->processUrl, 'requestId'=>$response_decode->requestId];
            }
        }else{
            return $err;
        }
    }

    public static function checkSession(Order $order, $numeroTarjeta)
    {
        $timeStart = microtime(true); 
        self::validateOrderStatus($numeroTarjeta);
        $timeEnd = microtime(true);
        $executionTime = ($timeEnd - $timeStart);
        if($executionTime > 180)
        {
            return ['orderStatus'=>Order::PAYED, 'paymentStatus'=> env("PROCESANDO")];
        }
        $requestId = $order->session_id;
        if(!$requestId)
        {
            return false;
        }
        $curl = CheckSession::makeRequest($requestId);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response_decode = '';
        if (!$err)
        {
            $response_decode = json_decode($response);
            if ($response_decode->status->status)
            {
                return self::validateOrderStatus($numeroTarjeta);
            }
        }else{
            return $err;
        }
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
                $paymentStatus = env('RECHAZADO');
                $orderStatus = Order::REJECTED;
                break;
            case '5424000000000015':
            case '5406251000000008':
            case '370000000000002':
            case '36018623456787':
            case '8130010000000000':
                $paymentStatus = env('APROBADO');
                $orderStatus = Order::PAYED;
                break;
            case '4212121212121214':
                $paymentStatus = env("ESPERANDO");
                $orderStatus = Order::PAYED;
                break;
            case '4666666666666669':
                sleep(185);
                $paymentStatus = env("PROCESANDO");
                $orderStatus = Order::PAYED;
                break;
        }
        return ['orderStatus'=>$orderStatus, 'paymentStatus'=>$paymentStatus];
    }
}