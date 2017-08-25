<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Feedcsv
 *
 * @ORM\Table(name="feedcsv")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeedcsvRepository")
 *
 */
class Feedcsv
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
     * @var integer
     *
     * @ORM\Column(name="id_store_api", type="integer", nullable=false)
     */
    private $idStoreApi;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=3, nullable=false)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=2, nullable=false)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="feed", type="text", nullable=false)
     */
    private $feed;

    /**
     * @var string
     *
     * @ORM\Column(name="flagbatched", type="string", length=100, nullable=false)
     */
    private $flagbatched;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="string", length=1, nullable=false)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="broken", type="string", length=1, nullable=false)
     */
    private $broken;

    /**
     * @var string
     *
     * @ORM\Column(name="sitename", type="string", length=255, nullable=false)
     */
    private $sitename;

    /**
     * @var string
     *
     * @ORM\Column(name="siteslug", type="string", length=255, nullable=false)
     */
    private $siteslug;

    /**
     * @var string
     *
     * @ORM\Column(name="siteurl", type="text", nullable=false)
     */
    private $siteurl;

    /**
     * @var string
     *
     * @ORM\Column(name="logostore", type="string", length=255, nullable=false)
     */
    private $logostore;

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
     * @return int
     */
    public function getIdStoreApi()
    {
        return $this->idStoreApi;
    }

    /**
     * @param int $idStoreApi
     */
    public function setIdStoreApi($idStoreApi)
    {
        $this->idStoreApi = $idStoreApi;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
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
     * @return string
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @param string $feed
     */
    public function setFeed($feed)
    {
        $this->feed = $feed;
    }

    /**
     * @return string
     */
    public function getFlagbatched()
    {
        return $this->flagbatched;
    }

    /**
     * @param string $flagbatched
     */
    public function setFlagbatched($flagbatched)
    {
        $this->flagbatched = $flagbatched;
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param string $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getBroken()
    {
        return $this->broken;
    }

    /**
     * @param string $broken
     */
    public function setBroken($broken)
    {
        $this->broken = $broken;
    }

    /**
     * @return string
     */
    public function getSitename()
    {
        return $this->sitename;
    }

    /**
     * @param string $sitename
     */
    public function setSitename($sitename)
    {
        $this->sitename = $sitename;
    }

    /**
     * @return string
     */
    public function getSiteslug()
    {
        return $this->siteslug;
    }

    /**
     * @param string $siteslug
     */
    public function setSiteslug($siteslug)
    {
        $this->siteslug = $siteslug;
    }

    /**
     * @return string
     */
    public function getSiteurl()
    {
        return $this->siteurl;
    }

    /**
     * @param string $siteurl
     */
    public function setSiteurl($siteurl)
    {
        $this->siteurl = $siteurl;
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



}

