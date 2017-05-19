<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commande;
use AppBundle\Form\CommandeType;
use AppBundle\Form\CommandeTicketsType;
use AppBundle\Entity\Ticket;
use AppBundle\Form\TicketType;
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

        $commande = $this->get('app.commande.manager')->createCommande();

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.commande.manager')->saveCommande($commande);

            return $this->redirectToRoute('coordonnees');
        }

        return $this->render('::index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/coordonnees", name="coordonnees")
     * @Method({"GET", "POST"})
     */
    public function coordonneesAction(Request $request)
    {

        $commande = $this->get('app.commande.manager')->getCommande();

        $this->get('app.commande.manager')->adaptTickets($commande);

        $form = $this->createForm(CommandeTicketsType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->get('app.commande.manager')->saveCommande($commande);
            return $this->redirectToRoute('recapitulatif', array('commande' => $commande));
        }

        return $this->render('::coordonnees.html.twig', array(
            'commande' => $commande,
            'form' => $form->createView()
        ));


        return $this->render('::coordonnees.html.twig', array('commande' => $commande));
    }

    /**
     * @Route("/recapitulatif", name="recapitulatif")
     * @Method({"GET"})
     */
    public function recapitulatifAction()
    {
        $commande = $this->get('app.commande.manager')->getCommande();
        $this->get('app.commande.manager')->setCommandeTotal($commande);
        $this->get('app.commande.manager')->saveCommande($commande);
        return $this->render('::recapitulatif.html.twig', array('commande' => $commande));
    }



    /**
     * @Route("/bankDetails", name="bankDetails")
     * @Method({"POST"})
     */
    public function bankDetailsAction(Request $request)
    {
        $commande = $this->get('app.commande.manager')->getCommande();
        $this->get('app.commande.manager')->saveCommande($commande);
        $this->get('app.commande.manager')->setCode($commande);


        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();


        \Stripe\Stripe::setApiKey("sk_test_2ctegkL1ZQXY1vuk23xi8C9Y");

        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];

        $total = $commande->getTotal();

        // Create a charge: this will charge the user's card
        try {
            \Stripe\Charge::create(array(
                "amount" => $total * 100, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe"
            ));


            $message = \Swift_Message::newInstance()
                ->setSubject('Votre commande - Billetterie du Louvre')
                ->setFrom(array('ben.guiriec@gmail.com' => 'Musée du Louvre'))
                ->setTo($commande->getEmail())
                ->setBody(
                    $this->renderView('Emails/mailCommande.html.twig', array('commande' => $commande)), 'text/html')
            ;
            $this->get('mailer')->send($message);

            return $this->redirectToRoute('validation', array('commande' => $commande));

        } catch (\Stripe\Error\Card $e) {

            $this->addFlash("error", "Votre paiement a échoué. Veuillez recommencer");
            return $this->redirectToRoute('recapitulatif',array('commande' => $commande) );
            // The card has been declined
        }
    }


    /**
     * @Route("/validation", name="validation")
     * @Method({"GET", "POST"})
     */
    public function validation()
    {
        $commande = $this->get('app.commande.manager')->getCommande();
        $this->get('app.commande.manager')->saveCommande($commande);
        //$commande->getCode();
        return $this->render("::validation.html.twig", array('commande' => $commande));
    }







    /**
     * @Route("/retour/{id}", name="retour")
     * @Method({"GET"})
     */
    public function retourAction(Commande $commande, Request $request)
    {
        /* $commande = $session->get('commande'); */





        return $this->redirectToRoute('coordonnees', array(
            'id' => $commande->getId()
        ));
    }

}