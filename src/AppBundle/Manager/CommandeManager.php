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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CommandeManager extends Controller
{

    protected $em;

    public function __construct(EntityManagerInterface $em, Session $session)
    {
        $this->em = $em;
        $this->session = $session;

    }

    /* Calcul du total de la commande */
    public function setCommandeTotal(Commande $commande, CommandeTicketsType $commandeTicketsType)
    {
        $commande = $ticket->getCommande();

        $total = 0;

        foreach ($this->tickets as $ticket)
        {
            $total += $ticket->setTicketPrice()->getPrice();
        }

        return $total;
    }


    /**
     * @param Ticket $ticket
     * @return int
     */
    public function setTicketPrice(Ticket $ticket)
    {
        $commande = $ticket->getCommande();

        $age = date_diff($commande->getDateVisit(), $ticket->getBirthDate()) -> y;

       /* $reducedPrice = $ticket->getReducedPrice(); */
       /*  $ticketType = $commande->getTicketType();*/

        switch ($age)
        {
            case ($age >= 12 && $age < 60):
                $ticket->setPrice(16);
                break;

            case ($age >= 4 && $age < 12):
                $ticket->setPrice(8);
                break;

            case ($age >= 60):
                $ticket->setPrice(12);
                break;

            case ($age < 4):
                $ticket->setPrice(0);
                break;
        }

        if ($reducedPrice == true && $ticket->getPrice() >= 10)
        {
            $ticket->setPrice(10);
        }

        /*
        if ($ticketType() == false)
        {
            return $price / 2;
        }
        */

        return $price;

    }


    public function createCommande()
    {
        if ($this->session->has('commande'))
        {
            $commande =  $this ->session->get('commande');
        }
        else
        {
            $commande = new Commande();
            $this->session->set('commande', $commande);
        }

        return $commande;
    }

}