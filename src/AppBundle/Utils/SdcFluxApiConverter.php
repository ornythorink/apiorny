<?php
namespace AppBundle\Utils;
use Symfony\Component\DomCrawler\Crawler;


class SdcFluxApiConverter
{
	private $flux;
	private $itemsArray = array();
	
	public function setFlux($flux)
	{
		$this->flux = $flux;
	}
	
	public function convertFlux()
	{
		$crawler = new Crawler($this->flux);
		$filter = $crawler->filter('items > offer');

		if (iterator_count($filter) > 1) 
		{
		    foreach ($filter as $key => $content)
		    {
				//var_dump($crawler->filter('imageList > image[available="true"] > sourceURL')->last()->text());
		        $crawler = new Crawler($content);
 				$datetime = new \DateTime('NOW');
                $item['apiid']              =  ( $crawler->filter('name')->count() >= 1)  ?  $crawler->filter('offer')->attr('id') : null;
                $item['price']				=  ( $crawler->filter('offer > basePrice')->count() >= 1)    ? $crawler->filter('offer > basePrice')->text() : null;
                $item['name']				=  ( $crawler->filter('name')->count() >= 1)     ?  utf8_decode( $crawler->filter('name')->text() ): null;
                $item['status']				=  "Ok";
                $item['oldPrice']			=  ( $crawler->filter('offer > originalPrice')->count() >= 1) ? $crawler->filter('offer > originalPrice')->text() : null;
                $item['currency']			=  ( $crawler->filter('offer > basePrice')->count() >= 1) ? $crawler->filter('offer > basePrice')->attr('currency') : null;
                $item['shortDescription'] 	=  ( $crawler->filter('description')->count() >= 1) ? utf8_decode($crawler->filter('description')->text() ): null ;

				$item['image']			    =  ( $crawler->filter('imageList > image[available="true"]')->count() >= 1) ? $crawler->filter('imageList > image[available="true"]')->text() : null ;

				if($item['image'] === null){
					$item['image']		    =  ( $crawler->filter('imageList > image[available="true"] > sourceURL')->count() >= 1) ? $crawler->filter('imageList > image[available="true"]  > sourceURL')->text() : null ;
				}

                $item['brand'] 				=  ( $crawler->filter('manufacturer')->count() >= 1) ? $crawler->filter('manufacturer')->text() : null ;
                $item['url'] 				=  ( $crawler->filter('offer > offerURL')->count() >= 1) ? $crawler->filter('offer > offerURL')->text() : null ;
                $item['delay']				=  ( $crawler->filter('deliveryTime')->count() >= 1) ? $crawler->filter('deliveryTime')->text() : null ;
                $item['specialPrice']	 	=  ( $crawler->filter('specialPrice')->count() >= 1) ? $crawler->filter('specialPrice')->text() : null ;
                $item['shipping']			=  ( $crawler->filter('deliveryTime')->count() >= 1) ? $crawler->filter('deliveryTime')->text() : null ;
                $item['shippingPrice'] 		=  ( $crawler->filter('shippingCost')->count() >= 1) ? $crawler->filter('shippingCost')->text() : null ;
                $item['store'] 				=  ( $crawler->filter('store')->count() >= 1) ? $crawler->filter('store > name')->text() : null ;
                $item['program']            =  ( $crawler->filter('store')->count() >= 1) ? $crawler->filter('store > name')->text() : null ;
                $item['merchantCategory'] 	=  ( $crawler->filter('categoryId')->count() >= 1) ? $crawler->filter('categoryId')->text() : null ;
                $item['createdAt'] 			=  $datetime->format('Y-m-d');
                $item['lastUpdate'] 		=  $datetime->format('Y-m-d');
                $item['sourceId']           =  'SDC';
				$item['logostore']          =  ( $crawler->filter('store')->count() >= 1) ? $crawler->filter('store > logo >sourceURL')->text() : null ;;
                $item['source_type']        =  'API';
                $item['ean']                =  null;
				$item["offers"] = $item;

				$this->itemsArray[$key] = $item;

		    }
		}		
	}
	
	public function getItemsArray()
	{

		return $this->itemsArray;
	}

}

