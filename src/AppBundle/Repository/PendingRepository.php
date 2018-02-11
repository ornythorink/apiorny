<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Cocur\Slugify\Slugify;
use AppBundle\Utils\Sources;


/**
 * PendingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PendingRepository extends EntityRepository
{

    public function getPaginatedCategoriesToValidate($page, $maxArticles, $locale)
    {
        $query = $this->_em->createQuery(
            "
                SELECT
                  p
                FROM AppBundle\Entity\Pending p
            "
        );

        return $query->getResult();

    }

    public function createOrReplacePending($pending, $source)
    {
        $statement = $this->_em->getConnection()->prepare('
                                INSERT INTO pending
                                SET
                                    id        = :slugified_source_category ,
                                    label     = :label ,
                                    createdAt =  NOW()
                                ON DUPLICATE KEY UPDATE
                                    createdAt  =  NOW() ');

        $statement->bindValue('slugified_source_category', $pending->getId());
        $statement->bindValue('label', $pending->getLabel());


        $statement->execute();

        return $statement;
    }
}
