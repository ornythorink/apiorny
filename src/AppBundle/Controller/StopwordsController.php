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
    public function getStopwordsByCategoryAction($locale, $slug)
    {
        $finder = $this->get('fos_elastica.finder.app.products');
//        $results = $finder->find('talons');
//
//        echo "<pre>";
//        var_dump($results);exit;
        $boolQuery = new \Elastica\Query\BoolQuery();
        $fieldQuery = new \Elastica\Query\Match();
        $fieldQuery->setFieldQuery('name', 'talons');
        $fieldQuery->setFieldParam('name', 'analyzer', 'my_analyzer');
        $boolQuery->addShould($fieldQuery);
        $data = $finder->find($boolQuery);
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
