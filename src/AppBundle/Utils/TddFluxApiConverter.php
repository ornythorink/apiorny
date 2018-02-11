<?php
namespace AppBundle\Utils;

use Symfony\Component\DomCrawler\Crawler;
use AppBundle\Entity\Products;

class TddFluxApiConverter
{
    private $flux;
    private $itemsArray;

    public function setFlux($flux)
    {
        $this->flux = $flux;
    }

    public function convertFlux()
    {
        #un autre moyen de setter le $xpath
        $xpath = "productItems";

        # on recupère le contenu du flux
        $crawler = new Crawler($this->flux);

        // apply css selector filter
        $filter = $crawler->filter('products > product');

        if (iterator_count($filter) > 1) {
            // iterate over filter results
            foreach ($filter as $key => $content) {
                // create crawler instance for result
                $crawler = new Crawler($content);
                // extract the values needed
                $datetime = new \DateTime('NOW');

                $item['price'] = ($crawler->filter('totalPrice')->count() >= 1) ? $crawler->filter('totalPrice')->text() : null;
                $item['name'] = ($crawler->filter('name')->count() >= 1) ? $crawler->filter('name')->text() : null;
                $item['status'] = "Ok";
                $item['oldPrice'] = ($crawler->filter('originalPrice')->count() >= 1) ? $crawler->filter('originalPrice')->text() : null;
                $item['currency'] = ($crawler->filter('price')->count() >= 1) ? $crawler->filter('price')->attr('currency') : null;
                $item['shortDescription'] = ($crawler->filter('shortDescription')->count() >= 1) ? $crawler->filter('shortDescription')->text() : null;
                $item['tinyImage'] = ($crawler->filter('product > productImage')->count() >= 1) ? $crawler->filter('product > productImage')->text() : null;
                $item['brand'] = ($crawler->filter('program')->count() >= 1) ? $crawler->filter('program')->text() : null;
                $item['url'] = ($crawler->filter('offers > offer > productUrl')->count() >= 1) ? $crawler->filter('offers > offer > productUrl')->text() : null;
                $item['delay'] = ($crawler->filter('deliveryTime')->count() >= 1) ? $crawler->filter('deliveryTime')->text() : null;
                $item['specialPrice'] = ($crawler->filter('specialPrice')->count() >= 1) ? $crawler->filter('specialPrice')->text() : null;
                $item['shipping'] = ($crawler->filter('deliveryTime')->count() >= 1) ? $crawler->filter('deliveryTime')->text() : null;
                $item['shippingPrice'] = ($crawler->filter('shippingCost')->count() >= 1) ? $crawler->filter('shippingCost')->text() : null;
                $item['store'] = ($crawler->filter('manufacturer')->count() >= 1) ? $crawler->filter('manufacturer')->text() : null;
                $item['program'] = ($crawler->filter('programname')->count() >= 1) ? $crawler->filter('programname')->text() : null;
                $item['merchantCategory'] = ($crawler->filter('category')->count() >= 1) ? strtolower($crawler->filter('category')->attr('name')) : null;
                $item['createdAt'] = $datetime->format('Y-m-d');
                $item['lastUpdate'] = $datetime->format('Y-m-d');
                $item['source'] = 'TDD';
                $item['logostore'] = null;
                $item['source_type'] = 'API';

                /*echo('<pre>');

                $crawler->filter('offers > offer')->each(
                    function (Crawler $nodeCrawler)
                    {
                        $discount = $nodeCrawler->filter('programName')->text();
                        var_dump($discount);

                    });
                echo('</pre>');*/

                //$offer =
                # logostore programLogo
                #programid
                # 	idMerchantProduct
                # ppc / ppv
                //$this->createItem($key, $item);
                $this->itemsArray[$key] = $item;
            }
        }
    }

    public function getItemsArray()
    {
        return $this->itemsArray;
    }
}

