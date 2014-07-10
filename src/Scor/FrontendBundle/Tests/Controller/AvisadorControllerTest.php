<?php

namespace Scor\FrontendBundle\Tests\Controller;

use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AvisadorControllerTest extends WebTestCase
{
    public function testRegistrar()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/avisador-caducidad/registrar');
        $this->assertTrue($crawler->filter('form.form-horizontal.no-readonly > div#avisador_caducidad')->count() > 0);

        // Recuperamos el formulario y lo rellenamos
        $remitente = 'email@email.com';
        $fecha = '17/08/2014';

        $form = $crawler->selectButton('Registrar')->form(array(), 'POST');
        $form['avisador_caducidad[nombre]'] = 'Nombre';
        $form['avisador_caducidad[apellidos]'] = 'Ape Llidos';
        $form['avisador_caducidad[fecha]'] = $fecha;
        $form['avisador_caducidad[licenciaPermiso]'] = 'perros';
        $form['avisador_caducidad[email]'] = $remitente;

        $client->enableProfiler(); // Necesario para evaluar SwiftMailer EN LA PRÓXIMA PETICIÓN
        $crawler = $client->submit($form); // Al hacer submit, el Profiler se activa
        try{
            $errorContent = $crawler->filter('ul li.alert.alert-danger')->html();
        }catch(\InvalidArgumentException $e){
            $errorContent = "";
        }
        $this->assertCount(0, $crawler->filter('ul li.alert.alert-danger'), $errorContent); // Comprobamos que no haya errores

        // Se general el flash de OK
        $this->assertArrayHasKey('ok', $client->getContainer()->get('session')->getBag('flashes')->all());
    }

    public function testSilenciar()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/avisador-caducidad/unsuscribe?id=10&email=email@email.com');

        // Se general el flash de OK
        $this->assertArrayHasKey('ok', $client->getContainer()->get('session')->getBag('flashes')->all());

        $crawler = $client->request('GET', '/avisador-caducidad/unsuscribe?id=999999&email=not-existing@email.com');

        // Se general el flash de ERROR
        $this->assertArrayHasKey('error', $client->getContainer()->get('session')->getBag('flashes')->all());

    }

}
