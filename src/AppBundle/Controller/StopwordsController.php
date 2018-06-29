<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class StopwordsController extends Controller
{
    /**
     * @Route("{locale}/stopwordsbyslug/{slug}", name="stopwordsbyidcategory")
     */
    public function getStopwordsByCategoryAction(Request $request, $locale, $slug)
    {
        $finder = $this->get('fos_elastica.finder.app.products');
//        $results = $finder->find('talons');
//
//        echo "<pre>";
//        var_dump($results);exit;
//
          $boolQuery = new \Elastica\Query\BoolQuery();
          $boolQuery->addShould(
                new \Elastica\Query\Term(array('name' => array('value' => 'escarpins', 'boost' => 3)))
            );


        $query = new \Elastica\Query\Filtered($boolQuery);
        $data = $finder->find($query, 100 );
        echo "<pre>";
        var_dump($data);exit;

        $stopwords = $this->getDoctrine()
            ->getRepository('AppBundle:Stopwords')
            ->findByGeneralOrCategorySlug($locale,$slug);


        $formatted = [];
        foreach ($stopwords as $stopword) {
            $formatted[] = [
                'stopword' => $stopword['stopword'],
            ];
        }
        return new JsonResponse($formatted);
    }

}
