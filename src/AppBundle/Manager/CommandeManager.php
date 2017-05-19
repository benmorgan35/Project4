<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Commande;
use AppBundle\Entity\Ticket;
use AppBundle\Form\CommandeType;
use AppBundle\Form\CommandeTicketsType;
use AppBundle\Form\TicketType;
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

    //Calcul du total de la commande
    /**
     * @param Commande $commande
     * @return int
     */
    public function setCommandeTotal(Commande $commande)
    {

        $total = 0;

        foreach ($commande->getTickets() as $ticket)
        {
            $total += $this->setTicketPrice($ticket);
        }
        $commande->setTotal($total);
        return $total;
    }

    //Attribution du prix et tarif des billets
    /**
     * @param Ticket $ticket
     * @return int
     */
    public function setTicketPrice(Ticket $ticket)
    {
        $commande = $ticket->getCommande();
        $age = date_diff($commande->getDateVisit(), $ticket->getBirthDate()) -> y;
        $ticketType = $commande->getTicketType();

        switch ($age)
        {
            case ($age >= 12 && $age < 60):
                if ($ticketType == false){
                    $ticket->setPrice(8);
                }
                else{
                    $ticket->setPrice(16);
                }

                $ticket->setTarif('normal');
                break;

            case ($age >= 4 && $age < 12):
                if ($ticketType == false){
                    $ticket->setPrice(4);
                }
                else{
                    $ticket->setPrice(8);
                }
                $ticket->setTarif('enfant');
                break;

            case ($age >= 60):
                if ($ticketType == false){
                    $ticket->setPrice(6);
                }
                else{
                    $ticket->setPrice(12);
                }
                $ticket->setTarif('senior');
                break;

            case ($age < 4):
                $ticket->setPrice(0);
                $ticket->setTarif('gratuit');
                break;
        }

        if ($ticket->getReducedPrice() === true && $ticket->getPrice() >= 10)
        {
            $ticket->setPrice(10);
            $ticket->setTarif('réduit');
        }

        if ($ticket->getReducedPrice() === true && $ticketType == false && $ticket->getPrice() >= 5)
        {
            $ticket->setPrice(5);
            $ticket->setTarif('réduit');
        }

        return $ticket->getPrice();
    }

    // Création d'une nouvelle commande ou récupération d'une commande en cours dans une session
    /**
     * @return Commande|mixed
     */
    public function createCommande()
    {
        if ($this->session->has('commande'))
        {
            $commande = unserialize($this->session->get('commande'));
        }
        else
        {
            $commande = new Commande();
            $this->saveCommande($commande);
        }

        return $commande;
    }

    // conversion d'une commande en variable php.
    public function getCommande()
    {
        if ($this->session->has('commande'))
        {
            $commande = unserialize($this ->session->get('commande'));
        }
        else
        {
            return false;
        }

        return $commande;
    }


    //Linéarisation qui conserve le type de la variable originale pour stocker une commande.
    public function saveCommande($commande)
    {
        $this->session->set('commande', serialize($commande));
        return $commande;
    }

    //Adaptation du nombre de tickets au nombre précisé dans le formumaire après un retour et une modification du nombre.
    public function adaptTickets($commande)
    {
        $nb = $commande->getTicketsNumber();

        while ($nb != count($commande->getTickets()))
        {
            if ($nb > count($commande->getTickets()))
            {
                $commande->addTicket(new Ticket());
            }
            elseif ($nb < count($commande->getTickets()))
            {
                $commande->removeTicket($commande->getTickets()->last());
            }
        }

        return $commande;
    }

    // Attribution d'un code unique
    public function setCode(Commande $commande)
    {
        $email = $commande->getEmail();
        $date = $commande->getDate();
        $arobase ="@";

        $date = date('Ymd-His');
        $code = 0;

        $carMail = substr($email, 0, strpos($email, $arobase));
        $commande->setCode('ML-' . $date . '-' . $carMail);

        return $code;
    }


}