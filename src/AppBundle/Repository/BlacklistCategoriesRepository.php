<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * BlacklistCategoriesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BlacklistCategoriesRepository extends EntityRepository
{
    public function loadBlacklist($locale)
    {
        $query = $this->_em->createQuery(
            "
                SELECT
                  c1
                FROM AppBundle\Entity\BlacklistCategories c1

                WHERE c1.locale = :locale
            "
        );

        $query->setParameter('locale', $locale);
        $results = $query->getResult();

        return $results;
    }
}