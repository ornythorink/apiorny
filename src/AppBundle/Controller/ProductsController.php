<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class ProductsController extends Controller
{
    /**
     * @Route("{locale}/product/full/{id}", name="productbyId")
     */
    public function getByIdAction(Request $request, $locale, $id)
    {
        // @todo $locale nonn utilisée
        $productsRepository = $this->getDoctrine()->getRepository('AppBundle:Products');
        $product = $productsRepository->getArrayById($locale, $id);


//        echo '<pre>';
//        var_dump($formatted);
//        echo '</pre>';
//        exit;

        return new JsonResponse($product);
    }


    /**
     * @Route("{locale}/product/{id}", name="urlproductbyId")
     */
    public function getUrlByIdAction(Request $request, $locale, $id)
    {
        // @todo utiliser plutôt la fonction du dessus qu'une autre url

        // @todo $locale nonn utilisée
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
     * @Route("{locale}/product/slug/{slug}/genre/{genre}", name="productbyslug")
     */
    public function bySlugAction(Request $request, $locale, $slug, $genre)
    {
        // @todo try if category does not exists
        $category = $this->getDoctrine()->getRepository('AppBundle:Categories')->findOneBy(array('categoryslug' => $slug, 'locale' => $locale));

        $term = $category->getTag();

        $productsRepository = $this->getDoctrine()->getRepository('AppBundle:Products');
        $product = $productsRepository->searchSelectedProduct($term, $locale, $genre);

        $formatted['products'] = $product;

        // @todo getOne sur le résultat de $lead
        /*$lead = $productsRepository->getLeadProducts($product[0]["name"], $locale);
        $relevance = $lead[0]['Relevance'];
        $threshold = ($relevance * 0.9);

        $offers = $productsRepository->searchLinkedProducts($product[0]["name"], $locale, $threshold);
        if (count($offers) > 1) {
            array_unshift($offers, $product[0]);
           $product[0]["offers"] = $offers;

        } else {
           $product[0]["offers"][] = $product[0];
        }*/
        $product[0]["offers"][] = $product[0];
        $formatted['products'] = $product;


//        var_dump($formatted);exit;

        return new JsonResponse($formatted);
    }

    /**
     * @Route("{locale}/hits", name="hits")
     * @
     */
    public function getMostHited(Request $request, $locale)
    {
        $productsRepository = $this->getDoctrine()->getRepository('AppBundle:Products');

        $product = $productsRepository->getMostHited($locale);

        foreach ($product as $item) {
            $formatted['products'][] = $item;
        }

        return new JsonResponse($formatted);
    }


    /**
     * @Route("{locale}/search/", name="pbysearch")
     * @
     */
    public function bySearchAction(Request $request, $locale)
    {
        $query = $request->query->get('query');
        $genre = $request->query->get('genre');
        // @todo try if category does not exists

        $productsRepository = $this->getDoctrine()->getRepository('AppBundle:Products');

        $product = $productsRepository->searchProducts($query , $locale);
        $formatted = array();
        foreach ($product as $item) {
            if ($item['brand'] !== null && $item['brand'] != "") {
                $brands[] = ucwords(strtolower($item['brand']));
            }
            $item["offers"][] = $item;
            $formatted['products'][] = $item;
        }

        return new JsonResponse($formatted);
    }

}