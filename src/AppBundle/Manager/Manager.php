<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Commande;
use AppBundle\Entity\Ticket;
use AppBundle\Form\CommandeType;
use AppBundle\Form\CommandeTicketsType;
use AppBundle\Form\TicketType;
use AppBundle\Entity\Price;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class Manager extends Controller
{

    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

/*
    public function getTicketPrice(Request $request, Commande $commande, Ticket $ticket, $ticketsNumber)
    {
        $age = date_diff(new \DateTime(), $ticket->getBirthDate());
        $ticketPrice = $this->em->getRepository('AppBundle:Price')->find(1);

        $reducedPrice = $ticket->getReducedPrice();
        $ticketType = $commande->getTicketType();

        if ($ticketType == false)
        {
            return $price / 2;
        }


        switch ($price)
        {
            case ($age >= 12 AND $age < 60):
                $ticketPrice = $price->getNormal;
                break;

            case ($age >= 4 AND $age < 12):
                $ticketPrice = $price->getChild;
                break;

            case ($age >= 60):
                $ticketPrice = $price->getSenior;
                break;

            case ($age < 4):
                $ticketPrice = $price->getFree;
                break;

            case ($reducedPrice == true):
                $ticketPrice = $price->getReducedPrice;
        }

        return $ticketPrice;

    }
*/
}