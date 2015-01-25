<?php

namespace Four026\CabinetBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testSignupform()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
    }

}
