<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Table(name="price")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PriceRepository")
 */
class Price
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="normal", type="integer")
     */
    private $normal;

    /**
     * @var int
     *
     * @ORM\Column(name="child", type="integer")
     */
    private $child;

    /**
     * @var int
     *
     * @ORM\Column(name="senior", type="integer")
     */
    private $senior;

    /**
     * @var int
     *
     * @ORM\Column(name="free", type="integer")
     */
    private $free;

    /**
     * @var int
     *
     * @ORM\Column(name="reducedPrice", type="integer")
     */
    private $reducedPrice;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set normal
     *
     * @param integer $normal
     *
     * @return Price
     */
    public function setNormal($normal)
    {
        $this->normal = $normal;

        return $this;
    }

    /**
     * Get normal
     *
     * @return int
     */
    public function getNormal()
    {
        return $this->normal;
    }

    /**
     * Set child
     *
     * @param integer $child
     *
     * @return Price
     */
    public function setChild($child)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * Get child
     *
     * @return int
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Set senior
     *
     * @param integer $senior
     *
     * @return Price
     */
    public function setSenior($senior)
    {
        $this->senior = $senior;

        return $this;
    }

    /**
     * Get senior
     *
     * @return int
     */
    public function getSenior()
    {
        return $this->senior;
    }

    /**
     * Set free
     *
     * @param integer $free
     *
     * @return Price
     */
    public function setFree($free)
    {
        $this->free = $free;

        return $this;
    }

    /**
     * Get free
     *
     * @return int
     */
    public function getFree()
    {
        return $this->free;
    }

    /**
     * Set reducedPrice
     *
     * @param integer $reducedPrice
     *
     * @return Price
     */
    public function setReducedPrice($reducedPrice)
    {
        $this->reducedPrice = $reducedPrice;

        return $this;
    }

    /**
     * Get reducedPrice
     *
     * @return int
     */
    public function getReducedPrice()
    {
        return $this->reducedPrice;
    }
}

