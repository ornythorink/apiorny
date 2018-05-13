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

    public function getBreadCrump($locale, $slug)
    {
        $sql = <<<EOL
                SELECT
                c1.*
                FROM categories c1
                WHERE c1.categoryslug = :slug
EOL;

        $breadcrump = [];
        $params['slug'] = $slug;
        $stmt = $this->_em->getConnection()->prepare($sql);
        $stmt->execute($params);

        $results = $stmt->fetch();

        if($results['id_parent'] != '0'){

            $sql = <<<EOL
                SELECT
                c1.*
                FROM categories c1
                WHERE c1.id = :id
EOL;

            $param['id'] = $results['id_parent'];

            $stmt = $this->_em->getConnection()->prepare($sql);
            $stmt->execute($param);
            $result = $stmt->fetch();
            $breadcrump[] = array('name_category' => $results['tag'],
                                   'categoryslug' => $results['categoryslug'] );

            $breadcrump[] = array('name_category' => $result['tag'],
                                    'categoryslug' => $result['categoryslug'] );

            $breadcrump[] = array('name_category' => 'Chaussures et bottes',
                'categoryslug' => '/' );

        } else {
            $breadcrump[] = array('name_category' => $results['tag'],
                'categoryslug' => $results['categoryslug'] );
            $breadcrump[] = array('name_category' => 'Chaussures et bottes',
                'categoryslug' => '/' );
        }
        return array_reverse($breadcrump);
    }

}