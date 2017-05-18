<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Commande", inversedBy="tickets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $commande;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, minMessage="Nom invalide.")
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Prénom invalide.")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="nationality", type="string", length=255)
     * @Assert\Country()
     */
    private $nationality;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthDate", type="date")
     * @Assert\Date()
     */
    private $birthDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="reducedPrice", type="boolean")
     */
    private $reducedPrice;



    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="tarif", type="string", length=255)
     */
    private $tarif;


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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Ticket
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Ticket
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Ticket
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set reducedPrice
     *
     * @param boolean $reducedPrice
     *
     * @return Ticket
     */
    public function setReducedPrice($reducedPrice)
    {
        $this->reducedPrice = $reducedPrice;

        return $this;
    }

    /**
     * Get reducedPrice
     *
     * @return bool
     */
    public function getReducedPrice()
    {
        return $this->reducedPrice;
    }

    /**
     * Set nationality
     *
     * @param string $nationality
     *
     * @return Ticket
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set tarif
     *
     * @param string $tarif
     *
     * @return Ticket
     */
    public function setTarif($tarif)
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return string
     */
    public function getTarif()
    {
        return $this->tarif;
    }





    /**
     * Set commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return Ticket
     */
    public function setCommande(Commande $commande)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \AppBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }
}
