<?php

namespace Scor\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LicenciaControllerTest extends WebTestCase
{
    public function testConducir()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/licencias-permisos/carnet-conducir');

        $this->assertTrue($crawler->filter('h1:contains("Carnet de conducir")')->count() > 0);
    }

    public function testArmas()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/licencias-permisos/permiso-armas');

        $this->assertTrue($crawler->filter('h1:contains("Permiso de armas")')->count() > 0);
    }

    public function testSeguridad()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/licencias-permisos/seguridad-privada');

        $this->assertTrue($crawler->filter('h1:contains("Seguridad privada")')->count() > 0);
    }

    public function testAnimales()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/licencias-permisos/animales-peligrosos');

        $this->assertTrue($crawler->filter('h1:contains("Animales peligrosos")')->count() > 0);
    }

    public function testNautica()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/licencias-permisos/nautica');

        $this->assertTrue($crawler->filter('h1:contains("Náutica")')->count() > 0);
    }

    public function testGruas()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/licencias-permisos/gruas');

        $this->assertTrue($crawler->filter('h1:contains("Grúas")')->count() > 0);
    }

}
