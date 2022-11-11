<?php

namespace App\web_checkout;

use Illuminate\Support\Facades\Facade;

class WebCheckout extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'webCheckout';
    }
}