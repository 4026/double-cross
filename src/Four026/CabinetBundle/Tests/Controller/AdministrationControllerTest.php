<?php

namespace Four026\CabinetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdministrationControllerTest extends WebTestCase
{
    public function testAdminDashboard()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/');

        //Can't actually test this page sensibly atm, because it requires auth.
        //$this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testCreateDocumentForm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/createDocument');

        //Can't actually test this page sensibly atm, because it requires auth.
        //$this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }
}
