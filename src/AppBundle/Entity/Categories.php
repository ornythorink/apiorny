<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Categories
 *
 * @ORM\Table(name="categories", uniqueConstraints={@ORM\UniqueConstraint(name="categoryslug2_idx", columns={"categoryslug"})}, indexes={@ORM\Index(name="parent_idx", columns={"id_parent"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoriesRepository")
 */
class Categories
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
     * @ORM\Column(name="name_categorie", type="string", length=255, nullable=false)
     */
    private $nameCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=255, nullable=true)
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(name="term", type="string", length=255, nullable=true)
     */
    private $term;

    /**
     * @var string
     *
     * @ORM\Column(name="categoryslug", type="string", length=255, nullable=false)
     */
    private $categoryslug;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $actif;

    /**
     * @var integer
     *
     * @ORM\Column(name="order", type="integer", nullable=false)
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_parent", type="smallint", nullable=false)
     */
    private $idParent;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=2, nullable=false)
     */
    private $locale;


    /**
     * @var integer
     *
     * @ORM\Column(name="filter", type="integer", nullable=false)
     */
    private $filter;


    public function __construct()
    {
        $this->filter = new ArrayCollection();
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
    public function getNameCategorie()
    {
        return $this->nameCategorie;
    }

    /**
     * @param string $nameCategorie
     */
    public function setNameCategorie($nameCategorie)
    {
        $this->nameCategorie = $nameCategorie;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return string
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @param string $term
     */
    public function setTerm($term)
    {
        $this->term = $term;
    }

    /**
     * @return string
     */
    public function getCategoryslug()
    {
        return $this->categoryslug;
    }

    /**
     * @param string $categoryslug
     */
    public function setCategoryslug($categoryslug)
    {
        $this->categoryslug = $categoryslug;
    }

    /**
     * @return boolean
     */
    public function isActif()
    {
        return $this->actif;
    }

    /**
     * @param boolean $actif
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getIdParent()
    {
        return $this->idParent;
    }

    /**
     * @param int $idParent
     */
    public function setIdParent($idParent)
    {
        $this->idParent = $idParent;
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

    /**
     * @param $filter
     */
    public function add(FilterCategories $filterCategories)
    {
        $filterCategories->setCategory($this);

        // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
        if (!$this->filter->contains($filterCategories)) {
            $this->filter->add($filterCategories);
        }
    }

    /**
     * @return ArrayCollection $filter
     */
    public function getDepartements()
    {
        return $this->filter;
    }

}

