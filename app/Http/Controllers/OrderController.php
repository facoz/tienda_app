<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePostRequest;
use App\web_checkout\WebCheckout;
use DateTime;

class OrderController extends Controller
{
    public function index()
    {
        return view('create');
    }

    public function saveCustomerOrder(CreatePostRequest $request)
    {
        $order = Order::create($request->all());
        return  redirect()->route('order.view', $order);
    }

    public function viewDetailedOrder(Order $order)
    {
        return view('pay', compact('order'));
    }

    public function viewAllOrders()
    {
        $orders = Order::get();
        return view('viewAll', compact('orders'));
    }

    public function createCustomerSession(Request $request, $order)
    {
        $order = Order::find($order);
        $session = WebCheckout::createSession();
        if (isset($session['requestId']))
        {
            $order->session_id = $session['requestId'];
            $order->url = $session['processUrl'];
            $order->payment_status = env('PENDING');
            $order->save();
        }
        return view('pay', compact('order'));
    }

    public function checkOrderStatus(Request $request, $order)
    {
        $request->validate([
            'numero_tarjeta'=> 'required'
        ]);
        $order = Order::find($order);
        $session = WebCheckout::checkSession($order);
        $response = $session['response'];
        $error = $session['error'];
        if (!$error)
        {
           self::saveSessionCheckingResponse($order, $response, $request->numero_tarjeta);
        }
        else{
            return "Hubo un error con el medio de pago"; 
        }
        return  redirect()->route('order.view', $order);
    }

    public function makeTransactionOperation(Request $request, Order $order)
    {
        $isAction = $request->has('action');
        $action = $isAction ? $request->action : false;
        $transactionResponse = WebCheckout::makeTransactionOperation($order->internal_reference, $action);
        $response = $transactionResponse['response'];
        $error = $transactionResponse['error'];
        if(!$error)
        {
            $statusResponse = $response->status->status ?: false;
            if($statusResponse){
                // $newStatus = strtoupper($action)."($statusResponse)";
                $paymentStatus = $action ? strtoupper($action) : $statusResponse;
                $order->status = Order::PAYED;
                $order->payment_status = $paymentStatus;
                $order->save();
            }
        }
        return  redirect()->route('order.view', $order);
    }

    public static function saveSessionCheckingResponse($order, $response, $numeroTarjeta ='')
    {
        $internalReference = '';
        $reference = '';
        $oroderStatusValue = [];
        $timeStart = microtime(true); 
        $oroderStatusValue = WebCheckout::validateOrderStatus($numeroTarjeta);
        $timeEnd = microtime(true);
        $executionTime = ($timeEnd - $timeStart);
        if ($response->status->status)
        {
            if($response->payment)
            {
                $objPaymentResponse = $response->payment[0];
                if(isset($objPaymentResponse))
                {
                    $internalReference =  $objPaymentResponse->internalReference ?: '';
                    $reference = $objPaymentResponse->reference ?: '';
                }
            }
            if(isset($oroderStatusValue['orderStatus']) && isset($oroderStatusValue['paymentStatus']))
            {
                if($executionTime > 180)
                {
                    $order->status = Order::PAYED;
                    $order->payment_status = env("PENDING_PROCESS");
                }
                else
                {
                    $order->status = $oroderStatusValue['orderStatus'];
                    $order->payment_status = $oroderStatusValue['paymentStatus'];
                }
                $order->internal_reference = $internalReference;
                $order->reference = $reference;
                $order->save();
            }
        }
        return true;
    }
}
