<?php

namespace AppBundle\Utils;
use GuzzleHttp\Client;


class TddDataSourceApi
{
    private $api;

    public function __construct()
    {
        $this->api = new Client();

        return $this;
    }

    public function getProductFlux($term)
    {
        $xml = '';

        # request factory qui set les parames optionels
        $query      = $term;
        $currency = 'EUR';
        $limit = 10;
        $groupOffersByProduct = true;
        $page = 1;

        $xml = $this->searchProducts($query, $currency, $limit,
            $groupOffersByProduct, $page);

        return $xml;
    }

    public function searchProducts($query, $currency, $limit,
                                   $groupOffersByProduct, $page)
    {

        $uri = 'http://api.tradedoubler.com/1.0/products.xml;q='. $query. ';limit=50;groupOffersByProduct=true?token=6098565E2B8545539C646815330B5579497A9789';

        $parameter['q']          = $query;
        $parameter['currency'] = $currency;
        $parameter['page']       = $page;
        $parameter['limit']       = $limit;
        $parameter['groupOffersByProduct']  = $groupOffersByProduct;

        $query = http_build_query($parameter, '', '&');

        if ( strlen($query) > 0 )
        {
            //$uri .= '?' . $query;
        }

        $agent = $_SERVER['HTTP_USER_AGENT'] ;
        $host = $_SERVER['HTTP_HOST'] ;

        $response = $this->api->get($uri);

        $body = $response->getBody()->getContents();

        return $body;
    }



}
