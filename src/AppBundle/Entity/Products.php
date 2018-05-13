<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Products
 *
 * @ORM\Table(name="products", uniqueConstraints={@ORM\UniqueConstraint(name="short_url", columns={"short_url"}), @ORM\UniqueConstraint(name="offerid", columns={"id_api"})}, indexes={@ORM\Index(name="status_idx", columns={"status"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductsRepository")
 *
 */
class Products
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
     * @ORM\Column(name="id_api", type="string", length=255, nullable=true)
     */
    private $idApi;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="promo", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $promo;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text", length=65535, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="short_url",type="string", length=255, nullable=false)
     */
    private $short_url;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=255, nullable=false)
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="logostore", type="string", length=255, nullable=true)
     */
    private $logostore;

    /**
     * @var string
     *
     * @ORM\Column(name="program", type="string", length=255, nullable=true)
     */
    private $program;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255, nullable=true)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text", nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="bigimage", type="text", nullable=true)
     */
    private $bigimage;

    /**
     * @var string
     *
     * @ORM\Column(name="source_id", type="string", length=255, nullable=false)
     */
    private $sourceId;

    /**
     * @var string
     *
     * @ORM\Column(name="source_type", type="string", length=255, nullable=false)
     */
    private $sourceType;

    /**
     * @var string
     *
     * @ORM\Column(name="actif", type="string", length=1, nullable=true)
     */
    private $actif;

    /**
     * @var string
     *
     * @ORM\Column(name="hit", type="integer", length=3, nullable=true)
     */
    private $hit;


    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=2, nullable=false)
     */
    private $locale;

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
     * @ORM\Column(name="ean", type="string", length=255, nullable=false)
     */
    private $ean;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="category_merchant", type="text", length=65535, nullable=true)
     */
    private $categoryMerchant;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", length=65535, nullable=true)
     */
    private $name;

    private $offers;

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
    public function getIdApi()
    {
        return $this->idApi;
    }

    /**
     * @param string $idApi
     */
    public function setIdApi($idApi)
    {
        $this->idApi = $idApi;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * @param string $promo
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getLogostore()
    {
        return $this->logostore;
    }

    /**
     * @param string $logostore
     */
    public function setLogostore($logostore)
    {
        $this->logostore = $logostore;
    }

    /**
     * @return string
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * @param string $program
     */
    public function setProgram($program)
    {
        $this->program = $program;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getBigimage()
    {
        return $this->bigimage;
    }

    /**
     * @param string $bigimage
     */
    public function setBigimage($bigimage)
    {
        $this->bigimage = $bigimage;
    }

    /**
     * @return string
     */
    public function getHit()
    {
        return $this->hit;
    }

    /**
     * @param string $hit
     */
    public function setHit($hit)
    {
        $this->hit = $hit;
    }

    /**
     * @return string
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * @param string $sourceId
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
    }

    /**
     * @return string
     */
    public function getSourceType()
    {
        return $this->sourceType;
    }

    /**
     * @param string $sourceType
     */
    public function setSourceType($sourceType)
    {
        $this->sourceType = $sourceType;
    }

    /**
     * @return string
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param string $actif
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
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
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * @param string $ean
     */
    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCategoryMerchant()
    {
        return $this->categoryMerchant;
    }

    /**
     * @param string $categoryMerchant
     */
    public function setCategoryMerchant($categoryMerchant)
    {
        $this->categoryMerchant = $categoryMerchant;
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
     * @return string
     */
    public function getShortUrl()
    {
        return $this->short_url;
    }

    /**
     * @param string $short_url
     */
    public function setShortUrl($short_url)
    {
        $this->short_url = $short_url;
    }


    /**
     * @return string
     */
    public function offer()
    {
        return $this->offers;
    }

    /**
     * @param string $name
     */
    public function setOffer($offers)
    {
        $this->offers = $offers;
    }

}

