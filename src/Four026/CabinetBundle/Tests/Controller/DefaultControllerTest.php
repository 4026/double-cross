<?php

namespace Four026\CabinetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testLoginPage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertTrue($crawler->filter('html:contains("Login")')->count() > 0);
    }
}
