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
            $order->payment_status = env('PENDIENTE');
            $order->save();
        }
        return view('pay', compact('order'));
    }

    public function checkOrderStatus(Request $request, $order)
    {
        $order = Order::find($order);
        $session = WebCheckout::checkSession($order, $request->numero_tarjeta);
        if(!isset($session['ERROR']))
        {
            if($session['orderStatus'] && $session['paymentStatus'])
            {
                $order->payment_status = $session['paymentStatus'];
                $order->status = $session['orderStatus']; 
                $order->save();
            }
            else{
                return "Hubo un error con el medio de pago"; //return with error-exception
            }
        }
        return  redirect()->route('order.view', $order);
    }

    public function makeAction(Request $request, Order $order)
    {
        $objUpdateDate = new DateTime($order->updated_at);
        $objDateTimeNow = new Datetime('now');
        $dateDiff = $objDateTimeNow->diff($objUpdateDate);
        return $dateDiff->format("%I");
        if ($dateDiff >=3)
        {
            
        }

    }
}
