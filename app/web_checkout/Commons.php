<?php

namespace App\web_checkout;

class Commons
{
    public static function authorization($login = '6dd490faf9cb87a9862245da41170ff2', $secretKey = '024h1IlD')
    {
        $nonce = random_bytes(16);
        $seed = date('c');
        $digest = base64_encode(hash('sha256', $nonce . $seed . $secretKey, true));
        $nonce64 = base64_encode($nonce);
        return "{\n  \"locale\": \"es_CO\",\n  \"auth\": {\n    \"login\": \"$login\",\n    \"tranKey\": \"$digest\",\n    \"nonce\": \"$nonce64\",\n    \"seed\": \"$seed\"\n  }";
    }

    public function createCurlIni($urlRequest, $postParameters)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $urlRequest,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postParameters,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);
        return $curl;
    }
}