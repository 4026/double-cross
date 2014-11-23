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

    public function testListDocuments()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Fabien');

        $this->assertTrue($crawler->filter('html:contains("Fabien")')->count() > 0);
    }

    public function testRead()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Fabien/Read/LoremIpsum');

        $this->assertTrue($crawler->filter('html:contains("Lorem Ipsum")')->count() > 0);
    }
}
