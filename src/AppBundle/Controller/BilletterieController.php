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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BilletterieController extends Controller
{

    /**
     * @Route("/{_locale}/", name="homepage")
     * @Method({"GET", "POST"})
     * requirements={"_locale": "en|fr"}
     */
    public function indexAction(Request $request)
    {
        $commande = $this->get('app.commande.manager')->createCommande();
        $locale = $request->getLocale();
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
     * @Route("/{_locale}/coordonnees/", name="coordonnees")
     * @Method({"GET", "POST"})
     * requirements={"_locale": "en|fr"}
     */
    public function coordonneesAction(Request $request)
    {

        $commande = $this->get('app.commande.manager')->getCommande();

        if ($commande === false)
        {
            return $this->redirectToRoute('homepage');
        }

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
     * @Route("/{_locale}/recapitulatif/", name="recapitulatif")
     * @Method({"GET"})
     * requirements={"_locale": "en|fr"}
     */
    public function recapitulatifAction(Request $request)
    {
        $commande = $this->get('app.commande.manager')->getCommande();
        $this->get('app.commande.manager')->setCommandeTotal($commande);

        if ($commande === false)
        {
            return $this->redirectToRoute('homepage');
        }


        if ($commande->getTotal() == 0)
        {
            $this->addFlash(
                'error',
                'Nous sommes désolés, vous ne pouvez commander de billets pour des enfants de moins de quatre ans non accompagnés. Veuillez modifier votre commande.'
            );
            return $this->redirectToRoute('homepage', array('commande' => $commande));
        }

        $this->get('app.commande.manager')->saveCommande($commande);
        return $this->render('::recapitulatif.html.twig', array('commande' => $commande));
    }



    /**
     * @Route("/bankDetails/{_locale}", name="bankDetails")
     * @Method({"POST"})
     * requirements={"_locale": "en|fr"}
     */
    public function bankDetailsAction(Request $request)
    {
        $commande = $this->get('app.commande.manager')->getCommande();

        \Stripe\Stripe::setApiKey($this->getParameter('stripe_private_key'));

        // Get the credit card details submitted by the form
        $token = $request->request->get('stripeToken');

        $total = $commande->getTotal();

        // Create a charge: this will charge the user's card
        try {
            \Stripe\Charge::create(array(
                "amount" => $total * 100, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe"
            ));

            $this->get('app.commande.manager')->setCode($commande);
            $this->get('app.commande.manager')->flushAndRemoveSession($commande);

            $message = \Swift_Message::newInstance()
                ->setSubject('Votre commande - Billetterie du Louvre')
                ->setFrom(array('ben.guiriec@gmail.com' => 'Musée du Louvre'))
                ->setTo($commande->getEmail())
                ->setBody(
                    $this->renderView('Emails/mailCommande.html.twig', array('commande' => $commande)), 'text/html')
            ;
            $this->get('mailer')->send($message);

            return $this->redirectToRoute('validation', array('code' => $commande->getCode()));

        } catch (\Stripe\Error\Card $e) {

            $this->addFlash(
                'error',
                'Nous sommes désolés, votre paiement a échoué. Veuillez recommencer cette étape.'
            );
            return $this->redirectToRoute('recapitulatif',array('commande' => $commande) );
            // Le paiement a échoué
        }
    }


    /**
     * @Route("/{_locale}/validation/{code}", name="validation")
     * @Method({"GET"})
     * requirements={"_locale": "en|fr"}
     */
    public function validation(Commande $commande)
    {
        return $this->render("::validation.html.twig", array('commande' => $commande));
    }


    /**
     * Change the locale for the current user
     *
     * @param String $language
     * @return array
     *
     * @Route("/{language}", name="setLocale")
     */
    public function setLocaleAction(Request $request, $language = null)
    {
        if($language != null)
        {
            // La locale en session
            $this->get('session')->set('_locale', $language);
        }

        return $this->redirectToRoute('homepage');
    }


}