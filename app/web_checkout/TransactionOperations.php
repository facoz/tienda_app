<?php

namespace App\web_checkout;

class TransactionOperations extends Commons
{
    public function completeAuthParameters($internalReference, $action)
    {
        if($action){
            return self::authorization().",\"internalReference\": $internalReference,\n  \"action\": \"$action\"\n}";
        }
        else
        {
            return self::authorization().",\"internalReference\": $internalReference\n}";
        }
    }

    public function obtainRequestUrl($action)
    {
        if($action)
        {
            return "https://stoplight.io/mocks/placetopay-api/api-services-docs/4963267/gateway/transaction";
            // return "https://dev.placetopay.com/redirection/api/session/$requestId";
        }
        else
        {
            return "https://stoplight.io/mocks/placetopay-api/api-services-docs/4963267/gateway/query";
        }
    }

    public static function makeRequest($internalReference, $action='')
    {
        return self::createCurlIni(self::obtainRequestUrl($action), self::completeAuthParameters($internalReference, $action));
    }
}