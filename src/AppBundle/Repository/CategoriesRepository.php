<?php


namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoriesRepository extends EntityRepository
{


    public function findRootCategories()
    {
        $query = $this->_em->createQuery(
            "
                SELECT
                  c1
                FROM AppBundle\Entity\Categories c1

                WHERE c1.idParent = :parent
            "
        );

        $query->setParameter('parent', 0);
        $query->useQueryCache(true);

        $results = $query->getResult();

        return $results;
    }

    public function findSubCategories($locale, $slug)
    {
        $parent = $this->findOneBy(
            array(
            'locale' => $locale,
            'categoryslug' => $slug
            )
        );

        $query = $this->_em->createQuery(
            "
                SELECT
                  c1
                FROM AppBundle\Entity\Categories c1

                WHERE c1.idParent = :parent
            "
        );

        $query->setParameter('parent', $parent->getId());
        //$query->useQueryCache(true);

        $results = $query->getResult();

        return $results;
    }

    public function findRootCategoriesByChildSlug($categoryslug)
    {
        $query = $this->_em->createQuery(
            "
                SELECT
                c2
                FROM AppBundle\Entity\Categories c1
                INNER JOIN AppBundle\Entity\Categories c2 WITH c1.idParent = c2.id
                WHERE c1.categoryslug = :categoryslug
            "
        );

        $query->setParameter('categoryslug', $categoryslug);
        //$query->useQueryCache(true);

        $results = $query->getOneOrNullResult();

        return $results;
    }

    public function findChildCategories($idParent)
    {
        $query = $this->_em->createQuery(
            "
                SELECT
                  c1
                FROM AppBundle\Entity\Categories c1

                WHERE c1.idParent = :parent
            "
        );
        //$query->useQueryCache(true);
        $query->setParameter('parent', $idParent);
        $results = $query->getResult();

        return $results;
    }

    public function getBreadCrump($slug)
    {
        $query = $this->_em->createQuery(
            "
                SELECT
                  c1 , c2.nameCategorie AS parent
                FROM AppBundle\Entity\Categories c1
                INNER JOIN AppBundle\Entity\Categories c2 WITH c1.idParent = c2.id
                WHERE c1.categoryslug = :slug
            "
        );
        //$query->useQueryCache(true);
        $query->setParameter('slug', $slug);

        $results = $query->getResult();

        return $results;
    }

}