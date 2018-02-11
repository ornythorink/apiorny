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

        $formatted = ['url' => $product->getUrl()];

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
        $category = $this->getDoctrine()->getRepository('AppBundle:Categories')->findOneBy(array('categoryslug' => $slug, 'locale' => $locale));

        $term = $category->getTag();

        $productsRepository = $this->getDoctrine()->getRepository('AppBundle:Products');
        $product = $productsRepository->searchSelectedProduct($term, $locale, $id);

        $formatted = [];
        // @todo getOne sur le résultat de $lead
        //var_dump($product);exit;
        $lead = $productsRepository->getLeadProducts($product[0]["name"], $locale);
        $relevance = $lead[0]['Relevance'];
        $threshold = ($relevance * 0.9);

        $offers = $productsRepository->searchLinkedProducts($product[0]["name"], $locale, $threshold);
        if (count($offers) > 1) {
            array_unshift($offers, $product[0]);
            $product[0]["offers"] = $offers;

        } else {
            $product[0]["offers"][] = $product[0];
        }
        $formatted['products'] = $product;


//        var_dump($formatted);exit;

        return new JsonResponse($formatted);
    }


}