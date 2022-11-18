<?php

namespace App\web_checkout;

class TransactionOperations extends Commons
{
    public function completeAuthParameters($internalReference, $action)
    {
        return self::authorization().",\"internalReference\": $internalReference,\n  \"action\": \"$action\"\n}";   
    }

    public function obtainRequestUrl()
    {
        return "https://stoplight.io/mocks/placetopay-api/api-services-docs/4963267/gateway/transaction";
        // return "https://dev.placetopay.com/redirection/api/session/$requestId";
    }

    public static function makeRequest($internalReference, $action)
    {
        return self::createCurlIni(self::obtainRequestUrl(), self::completeAuthParameters($internalReference, $action));
    }
}