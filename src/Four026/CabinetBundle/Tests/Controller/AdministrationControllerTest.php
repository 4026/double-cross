<?php

namespace Four026\CabinetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdministrationControllerTest extends WebTestCase
{
    public function testSignupform()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/createDocument');

        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }
}
