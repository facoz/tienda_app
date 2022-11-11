<?php

namespace App\web_checkout;

class CheckSession extends Commons
{
    public function completeAuthParameters()
    {
        return self::authorization()."}";
    }

    public function obtainRequestUrl($requestId)
    {
        return "https://stoplight.io/mocks/placetopay-api/webcheckout-docs/10862976/api/session/$requestId";
        // return "https://dev.placetopay.com/redirection/api/session/$requestId";
    }

    public static function makeRequest($requestId)
    {
        return self::createCurlIni(self::obtainRequestUrl($requestId), self::completeAuthParameters());
    }
}