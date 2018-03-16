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
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     *
     */
    private $name;

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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedat()
    {
        return $this->createdat;
    }

    /**
     * @param \DateTime $createdat
     */
    public function setCreatedat($createdat)
    {
        $this->createdat = $createdat;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateat()
    {
        return $this->updateat;
    }

    /**
     * @param \DateTime $updateat
     */
    public function setUpdateat($updateat)
    {
        $this->updateat = $updateat;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }


}

