<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class ProductsController extends Controller
{
    /**
     * @Route("{locale}/products/{id}", name="productbyId")
     */
    public function indexAction(Request $request, $locale, $id)
    {
        $productsRepository = $this->getDoctrine()->getRepository('AppBundle:Products');
        $product = $productsRepository->findOneById($id);

        $formatted = ['url' =>$product->getUrl()];

//        echo '<pre>';
//        var_dump($formatted);
//        echo '</pre>';
//        exit;
        return new JsonResponse($formatted);
    }

    /**
     * @Route("{locale}/product/slug/{slug}/id/{id}", name="productbyslug")
     */
    public function bySlugAction(Request $request, $locale, $slug, $id)
    {
        // @todo try if category does not exists
        $category = $this->getDoctrine()->getRepository('AppBundle:Categories')->findOneBy(array('categoryslug' => $slug, 'locale' => $locale) );

        $term = $category->getTag();

        $productsRepository = $this->getDoctrine()->getRepository('AppBundle:Products');
        $products = $productsRepository->searchSelectedProduct($term, $locale, $id);

        $formatted = [];

        foreach ($products as $item) {
            // @todo getOne sur le résultat de $lead
            $lead = $productsRepository->getLeadProducts($item['name'], $locale);
            $relevance =  $lead[0]['Relevance'];
            $threshold = $relevance - ($relevance* 0.1 );

            $offers = $productsRepository->searchLinkedProducts($item['name'], $locale, $threshold);
            if(count($offers) > 1)
            {
                $item["offers"] = $offers;
            } else {
                $item["offers"][] = $item;
            }
            $formatted[] = $item;
        }
//        echo '<pre>';
//        var_dump($formatted);exit;

        return new JsonResponse($formatted);
    }


}