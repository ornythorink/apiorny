<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlacklistCategories
 *
 * @ORM\Table(name="blacklist_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BlacklistCategoriesRepository")
 */
class BlacklistCategories
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     *
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updateAt", type="datetime", nullable=true)
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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
     * @return \Pending
     */
    public function getPending()
    {
        return $this->pending;
    }

    /**
     * @param \Pending $pending
     */
    public function setPending($pending)
    {
        $this->pending = $pending;
    }


}

