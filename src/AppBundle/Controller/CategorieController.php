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
    public function getRootCategoriesAction()
    {
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
     * @Route("{locale}/category/sdc/{slug}", name="apisdccategory")
     */
    public function getProductsByCategory($locale, $slug)
    {
        // @todo try if category does not exists
        $category = $this->getDoctrine()->getRepository('AppBundle:Categories')->findOneBy(array('categoryslug' => $slug, 'locale' => $locale) );

        $term = $category->getTag();

        $productsRepository = $this->getDoctrine()->getRepository('AppBundle:Products');
        $products = $productsRepository->searchProducts($term, $locale);

        $formatted = [];

        foreach ($products as $item) {
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
            return new JsonResponse($formatted);
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
        $term = $category->getTag();

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
        $term = $category->getTag();

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
