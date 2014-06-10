<?php

namespace Scor\FrontendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LicenciaControllerTest extends WebTestCase
{
    public function testConducir()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'carnet-conducir');
    }

    public function testArmas()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'permiso-armas');
    }

    public function testSeguridad()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'seguridad-privada');
    }

    public function testAnimales()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'animales-peligrosos');
    }

    public function testNautica()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/nautica');
    }

    public function testGruas()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/gruas');
    }

}
