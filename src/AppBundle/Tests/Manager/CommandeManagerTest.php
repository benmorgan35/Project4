<?php

namespace AppBundle\Tests\Manager;


use AppBundle\Entity\Commande;
use AppBundle\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class CommandeManagerTest extends WebTestCase
{

    public function testSetCommandeTotal()
    {
        $client = self::createClient();
        $commandeManager = $client-> getContainer()->get('app.commande.manager');

        $commande = new Commande();
        $commande->setDateVisit(new \DateTime('2017-05-10'));
        $commande->setTicketType(true);

        $ticket1 = new Ticket();
        $ticket2 = new Ticket();

        $ticket1->setBirthDate(new \DateTime('2009-10-10'));
        $ticket1->setReducedPrice(false);
        $ticket2->setBirthDate(new \DateTime('2000-10-10'));
        $ticket2->setReducedPrice(false);

        $commande->addTicket($ticket1)->addTicket($ticket2);
        $commandeManager->setCommandeTotal($commande);

        $this->assertEquals(24, $commande->getTotal());
    }

    public function testSetTicketPrice()
    {
        $client = self::createClient();
        $commandeManager = $client-> getContainer()->get('app.commande.manager');
        $commande = new Commande();
        $ticket = new Ticket();
        $commande->setDateVisit(new \DateTime('2017-05-10'));
        $commande->addTicket($ticket);
        $ticket->setBirthDate(new \DateTime('2009-10-10'));
        $ticket->setReducedPrice(false);
        $commandeManager->setTicketPrice($ticket);
        $this->assertEquals(8, $ticket->getPrice());
    }

    public function testAdaptTickets()
    {
        $client = self::createClient();
        $commandeManager = $client-> getContainer()->get('app.commande.manager');
        $commande = new Commande();
        $commande->setTicketsNumber(6);
        $commandeManager->adaptTickets($commande);
        $this->assertEquals(6, count($commande->getTickets()));
    }

    public function testSetCode()
    {
        $client = self::createClient();
        $commandeManager = $client-> getContainer()->get('app.commande.manager');
        $commande = new Commande();
        $commande->setEmail('pierre.dupond@gmail.com');
        $commande->setDate(new \DateTime('2017-06-05'));
        $commandeManager->setCode($commande);
        $this->assertEquals('ML-20170605-pierre.dupond', $commande->getCode());
    }

    public function testGetCommande()
    {
        $client = self::createClient();
        $commandeManager = $client-> getContainer()->get('app.commande.manager');
        $commande = new Commande();
        $commande->setDateVisit(new \DateTime('2017-05-10'));
        $commande->setTicketType(true);
        $commande->setEmail('pierre.dupond@gmail.com');
        $commandeManager->getCommande();
        $this->assertNotNull($commande);
    }

    public function testSaveCommande()
    {
        $client = self::createClient();
        $commandeManager = $client-> getContainer()->get('app.commande.manager');
        $commande = new Commande();
        $commande->setDateVisit(new \DateTime('2017-05-10'));
        $commande->setTicketType(true);
        $commande->setEmail('pierre.dupond@gmail.com');
        $commandeManager->saveCommande($commande);
        $this->assertNotNull($commande);
    }

}

