<?php

namespace Four026\CabinetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserDeskControllerTest extends WebTestCase
{
    public function testDeskMain()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/desk');
    }

}
