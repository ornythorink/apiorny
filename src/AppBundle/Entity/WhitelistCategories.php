<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WhitelistCategories
 *
 * @ORM\Table(name="whitelist_categories"))
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WhiteListCategoriesRepository")
 */
class WhitelistCategories
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=false)
     */
    private $createdat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updateAt", type="datetime", nullable=false)
     */
    private $updateat;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", nullable=false)
     *
     */
    private $locale;
}

