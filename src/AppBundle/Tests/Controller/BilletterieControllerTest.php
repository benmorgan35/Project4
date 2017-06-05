<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BilletterieControllerTest extends WebTestCase
{


    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return array(
            array('/fr/'),
            array('/coordonnees/'),
            array('/recapitulatif/'),
            array('/validation/'),
        );
    }

    public function testIndex()
    {
        //retourne un client qui ressemble au navigateur
        $client = static::createClient();
        //retourne un objet Crawler utilisé pour sélectionner des éléments dans la réponse, cliquer sur les liens et soumettre des formulaires.
        $crawler = $client->request('GET', '/fr/');
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("jour")')->count()
        );
    }

    public function testTitre()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/');
        $this->assertGreaterThan(
            0,
            $crawler->filter('h1:contains("Billetterie")')->count()
        );
    }

    public function testRedirection()
    {
        $client = static::createClient();
        $client->request('GET', '/fr/coordonnees/');
        $this->assertTrue(
            $client->getResponse()->isRedirect('/fr/'));
    }

    public function testForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/');
        $buttonCrawlerNode = $crawler->selectButton('Poursuivre');
        $form = $buttonCrawlerNode->form();
        $client->submit($form);
        $this->assertEquals(
            $client->getResponse()->getStatusCode(), 200);

    }




}
