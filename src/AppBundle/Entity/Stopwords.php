<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stopwords
 *
 * @ORM\Table(name="stopwords" )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StopwordsRepository")
 */

class Stopwords
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
     * @ORM\Column(name="stopword", type="string", nullable=false)
     *
     */
    private $stopword;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", nullable=false)
     *
     */
    private $category;

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
    public function getStopword()
    {
        return $this->stopword;
    }

    /**
     * @param string $stopword
     */
    public function setStopword($stopword)
    {
        $this->stopword = $stopword;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }


}