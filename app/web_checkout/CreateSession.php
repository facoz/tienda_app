<?php

namespace App\web_checkout;

class CreateSession extends Commons
{
    public function completeAuthParameters()
    {
        return self::authorization().",\"returnUrl\": \"https://dev.placetopay.com/redirection\"}";
    }

    public function obtainRequestUrl()
    {
        return "https://stoplight.io/mocks/placetopay-api/webcheckout-docs/10862976/api/session";
        // return "https://checkout-co.placetopay.dev/api/session";
    }

    public static function makeRequest()
    {
        return self::createCurlIni(self::obtainRequestUrl(), self::completeAuthParameters());
    }
}