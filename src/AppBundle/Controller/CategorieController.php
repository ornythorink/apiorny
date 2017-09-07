<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Utils\SdcFluxApiConverter;
use AppBundle\Utils\SdcDataSourceApi;
use AppBundle\Utils\TddDataSourceApi;
use AppBundle\Utils\TddFluxApiConverter;

class CategorieController extends Controller
{

    /**
     * @Route("{locale}/category/root", name="rootcategories")
     */
    public function getRootCategoriesAction($locale)
    {
        // @todo passer la locale à findRootCategories
        $categories = $this->getDoctrine()->getRepository('AppBundle:Categories')->findRootCategories();


        $formatted = [];
        foreach ($categories as $category) {
            $formatted[] = [
                'id' => $category->getId(),
                'name_categorie' => $category->getNameCategorie(),
                'categoryslug' => $category->getCategorySlug(),
            ];
        }
        return new JsonResponse($formatted);
    }

    /**
     * @Route("{locale}/category/sub/{slug}", name="subcat")
     */
    public function getSubCategoriesAction($locale, $slug)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Categories')->findSubCategories($locale ,$slug);


        $formatted = [];
        foreach ($categories as $category) {
            $formatted[] = [
                'id' => $category->getId(),
                'name_categorie' => $category->getNameCategorie(),
                'categoryslug' => $category->getCategorySlug(),
            ];
        }
        return new JsonResponse($formatted);
    }

    /**
     * @Route("{locale}/category/sdc/{slug}", name="apisdccategory")
     */
    public function getProductsByCategory($locale, $slug)
    {
        // @todo try if category does not exists
        $category = $this->getDoctrine()->getRepository('AppBundle:Categories')->findOneBy(array('categoryslug' => $slug, 'locale' => $locale) );

        $term = $category->getTerm();

        $productsRepository = $this->getDoctrine()->getRepository('AppBundle:Products');
        $products = $productsRepository->searchProducts($term, $locale);

        $formatted = [];
        $brands = [];

        foreach ($products as $item) {
            $brands[] = $item['brand'];
            $item["offers"][] = $item;
            $formatted['products'][] = $item;
        }

        $formatted['metadata']['brands'] = array_unique($brands);

        return new JsonResponse($formatted);
    }

    /**
     * @Route("{locale}/search/{slug}", name="search")
     */
    public function searchProducts($locale, $slug)
    {
        // @todo try if category does not exists

        $productsRepository = $this->getDoctrine()->getRepository('AppBundle:Products');
        $products = $productsRepository->searchProducts($slug, $locale);

        $formatted = [];
        $brands = [];

        foreach ($products as $item) {
            $brands[] = $item['brand'];
            $item["offers"][] = $item;
            $formatted['products'][] = $item;
        }

        $formatted['metadata']['brands'] = array_unique($brands);

        return new JsonResponse($formatted);
    }


    /**
     * @Route("{locale}/linked/{id}", name="linked")
     */
    public function getLinkedOffers($locale, $id)
    {
        $productsRepository = $this->getDoctrine()->getRepository('AppBundle:Products');
        $product = $productsRepository->getArrayById($locale,$id);
        //var_dump($product);exit;
        $lead = $productsRepository->getLeadProducts($product[0]['name'], $locale);
        $relevance =  $lead[0]['Relevance'];
        $threshold = $relevance - ($relevance* 0.1 );

        $offers = $productsRepository->searchLinkedProducts($product[0]['name'], $locale, $threshold);

           if(count($offers) > 1)
           {
               $product["offers"] = $product + $offers;
           } else {
               $product["offers"] = $product;
           }
        return new JsonResponse(array($product));
    }


    /**
     * @Route("{locale}/category/sdc/{slug}/{ip}/{agent}", name="apisdc")
     */
    public function getApiSdcByCategoryAction($locale, $slug, $ip, $agent)
    {
        // @todo maintenir fallback même quand la db n'est pas dispo ?
        $useragent = base64_decode($agent);
        $datasource = new SdcDataSourceApi();

        // @todo try if category does not exists
        $category = $this->getDoctrine()->getRepository('AppBundle:Categories')->findOneBy(array('categoryslug' => $slug) );
        $term = $category->getTerm();

        $flux = $datasource->getProductFlux($term, $locale, $ip, $useragent );

        $converter = new SdcFluxApiConverter();
        $converter->setFlux($flux);
        $converter->convertFlux();
        $converted =$converter->getItemsArray();

        $formatted = [];
        foreach ($converted as $item) {
            $item["offers"] = $item;
            $formatted[] = $item;
        }
        return new JsonResponse($formatted);
    }

    /**
     * @Route("{locale}/category/tdd/{slug}", name="apitdd")
     */
    public function getApiTddByCategoryAction($locale, $slug)
    {

        $datasource = new TddDataSourceApi();

        // @todo try if category does not exists
        $category = $this->getDoctrine()->getRepository('AppBundle:Categories')->findOneBy(array('categoryslug' => $slug) );
        $term = $category->getTerm();

        $flux = $datasource->getProductFlux($term, $locale);

        $converter = new TddFluxApiConverter();
        $converter->setFlux($flux);
        $converter->convertFlux();
        $converted =$converter->getItemsArray();

        $formatted = [];
        foreach ($converted as $item) {
            //$item["offers"] = $item;
            $formatted[] = $item;
        }


        return new JsonResponse($formatted);
    }
}
