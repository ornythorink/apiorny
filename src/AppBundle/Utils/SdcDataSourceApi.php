<?php

namespace AppBundle\Utils;

use GuzzleHttp\Client;


class SdcDataSourceApi
{
    private $api;

    private static $_request = '/publisher/3.0/rest/GeneralSearch';
    private $agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36
                        (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36";
    private static $_apiKey = "6f8d716b-658d-401a-9f1c-6c37705138fb";
    private static $_trackingId = "8059727";


    public function __construct()
    {
        $this->api = new Client();

        return $this;
    }

    public function getProductFlux($term, $locale, $ip = "127.0.0.1", $agent = null, $page = 1, $number = 60)
    {
        if ($agent === null) {
            $agent = $this->agent;
        }
        //96602
        $params = array(
            "apiKey" => self::$_apiKey,
            "trackingId" => self::$_trackingId,
            "categoryId" => 96602,
            "keyword" => $term,
            "numItems" => $number,
            "pageNumber" => $page,
            "numOffersPerProduct" => 5,
            "showProductSpecs" => "true",
            "visitorUserAgent" => $agent,
            "visitorIPAddress" => $ip,
            "showProductsWithoutOffers" => "true",
            "showProductSpecs" => "true"

        ); //183693068

        $xml = $this->searchProducts($params);

        return $xml;
    }

    public function searchProducts($parameters)
    {

        $uri = 'http://api.ebaycommercenetwork.com' . self::$_request;


        $query = http_build_query($parameters, '', '&');

        if (strlen($uri) > 0) {
            $uri .= '?' . $query;
        }

        $response = $this->api->get($uri);

        $body = $response->getBody()->getContents();

        return $body;
    }
}
