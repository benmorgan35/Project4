<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commande;
use AppBundle\Form\CommandeType;
use AppBundle\Entity\Ticket;
use AppBundle\Form\TicketType;
use AppBundle\Entity\Price;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BilletterieController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

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
     */
    public function coordonneesAction(Commande $commande, Request $request)
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $ticket->setCommande($commande);
            $em->persist($ticket);
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
     */
    public function recapitulatifAction(Commande $commande)
    {

    }
}