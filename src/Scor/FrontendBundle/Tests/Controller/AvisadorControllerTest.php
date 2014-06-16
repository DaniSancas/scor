<?php

namespace Scor\FrontendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AvisadorControllerTest extends WebTestCase
{
    public function testRegistrar()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/registrar');
    }

    public function testAvisar()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/avisar');
    }

}
