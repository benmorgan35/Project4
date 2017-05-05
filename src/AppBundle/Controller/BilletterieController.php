<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commande;
use AppBundle\Form\CommandeType;
use AppBundle\Form\CommandeTicketsType;
use AppBundle\Entity\Ticket;
use AppBundle\AppBundle\Manager\Manager;
use AppBundle\Form\TicketType;
use AppBundle\Entity\Price;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class BilletterieController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        /*
        $session = $request->getSession();
        $commande->$session->setCommande();
        */

        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
/*

*/
            return $this->redirectToRoute('coordonnees', array(
                'id' => $commande->getId()
            ));
        }

        return $this->render('::index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/coordonnees/{id}", name="coordonnees")
     * @Method({"GET", "POST"})
     */
    public function coordonneesAction(Commande $commande, Request $request)
    {
/*
        $session = $request->getSession();

*/

        $nb = $commande->getTicketsNumber();

        for ($i = 1; $i <= $nb; $i++)
        {

            $commande->addTicket(new Ticket());
            /*
            $ticketPrice = $this->getManager()->getTicketPrice();
            $ticket->setPrice($ticketPrice);
            */
        }




        $form = $this->createForm(CommandeTicketsType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();


            $em->flush();

            return $this->redirectToRoute('recapitulatif', array(
                'id' => $commande->getId(),

            ));
        }

        return $this->render('::coordonnees.html.twig', array(
            'commande' => $commande,
            'form' => $form->createView()
        ));



        return $this->render('::coordonnees.html.twig', array('commande' => $commande));
    }
    /**
     * @Route("/recapitulatif/{id}", name="recapitulatif")
     * @Method({"GET"})
     */
    public function recapitulatifAction(Commande $commande)
    {
        return $this->render('::recapitulatif.html.twig', array('commande' => $commande));
    }
}