<?php

namespace App\Services;

class CurrencyConvert
{

    public function __construct()
    {
    }

    /**
     *
     * workaround HTTPS problems with file_get_contents
     *
     * @param $url
     * @return boolean|string
     */
    function curl_get_contents($url)
    {
        $data = FALSE;
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $data = curl_exec($ch);
            curl_close($ch);
        }
        return $data;
    }
    public function convertCurrency($amount, $from_currency, $to_currency)
    {
        $amount = $amount;
        $from = $from_currency;
        $to = $to_currency;
        $url = "http://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
        $data = $this->curl_get_contents($url);
        // $data = file_get_contents($url);
        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // $data = curl_exec($curl);
        // dd($data);
        dd(preg_match("/<span class=bld>(.*)<\/span>/", $data, $converted));
        return $converted[1];
    }

    function thmx_currency_convert($amount, $from_currency, $to_currency)
    {
        $from = $from_currency;
        $to = $to_currency;
        $url = "https://api.exchangerate-api.com/v4/latest/$from";
        $json = file_get_contents($url);
        $exp = json_decode($json);

        $convert = $exp->rates->$to;

        return $convert * $amount;
    }
}