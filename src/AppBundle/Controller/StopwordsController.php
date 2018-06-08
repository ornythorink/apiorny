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
