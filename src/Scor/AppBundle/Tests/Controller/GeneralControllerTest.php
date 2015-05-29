<?php

namespace Scor\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GeneralControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertTrue($crawler->filter('h1:contains("«S.C.O.R.»")')->count() > 0);
    }

    public function testPedirCita()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/pedir-cita');
        $this->assertTrue($crawler->filter('form.form-horizontal.no-readonly > div#pedir_cita')->count() > 0);

        // Recuperamos el formulario y lo rellenamos
        $remitente = 'email@email.com';
        $fecha = 'Martes 15 de Julio de 2014';
        $hora = '17:30';

        $form = $crawler->selectButton('Enviar')->form(array(), 'POST');
        $form['pedir_cita[nombre]'] = 'Nombre';
        $form['pedir_cita[apellidos]'] = 'Ape Llidos';
        $form['pedir_cita[email]'] = $remitente;
        $form['pedir_cita[telefono]'] = '123456789';
        $form['pedir_cita[licencias_permisos]'] = 'perros';
        $form['pedir_cita[operacion]'] = 'renovacion';
        $form['pedir_cita[fecha]'] = $fecha;
        $form['pedir_cita[hora]'] = $hora;
        $form['pedir_cita[observaciones]'] = 'Algo...';
        $form['pedir_cita[aviso]']->tick();

        $client->enableProfiler(); // Necesario para evaluar SwiftMailer EN LA PRÓXIMA PETICIÓN
        $crawler = $client->submit($form); // Al hacer submit, el Profiler se activa
        $this->assertCount(0, $crawler->filter('ul li.alert.alert-danger')); // Comprobamos que no haya errores

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
        $this->assertEquals(2, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $messageUno = $collectedMessages[0];
        $messageDos = $collectedMessages[1];

        // Evaluación del primer mensaje
        $this->assertInstanceOf('Swift_Message', $messageUno);
        $this->assertEquals($remitente, key($messageUno->getFrom()));
        $this->assertContains('Petición de cita desde el formulario de citas de la web', $messageUno->getBody());

        // Evaluación del segundo mensaje
        $this->assertInstanceOf('Swift_Message', $messageDos);
        $this->assertEquals($remitente, key($messageDos->getTo()));
        $this->assertContains('Ha solicitado una cita para el '.$fecha.', a las '.$hora, $messageDos->getBody());

        // Se general el flash de OK
        $this->assertArrayHasKey('ok', $client->getContainer()->get('session')->getBag('flashes')->all());
    }

    public function testContacto()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contacto');
        $this->assertTrue($crawler->filter('form.form-horizontal > div#contacto')->count() > 0);

        // Recuperamos el formulario y lo rellenamos
        $remitente = 'email@email.com';

        $form = $crawler->selectButton('Enviar')->form(array(), 'POST');
        $form['contacto[nombre]'] = 'Nombre';
        $form['contacto[apellidos]'] = 'Ape Llidos';
        $form['contacto[email]'] = $remitente;
        $form['contacto[telefono]'] = '123456789';
        $form['contacto[consulta]'] = 'Algo en la consulta... Mínimo de 25 chars';

        $client->enableProfiler(); // Necesario para evaluar SwiftMailer EN LA PRÓXIMA PETICIÓN
        $crawler = $client->submit($form); // Al hacer submit, el Profiler se activa

        // Comprobamos que no haya errores
        // Descomentar el mensaje para tener una pista si hay un error
        try{
            $errorContent = $crawler->filter('ul li.alert.alert-danger')->html();
        }catch(\InvalidArgumentException $e){
            $errorContent = "";
        }
        $this->assertCount(0, $crawler->filter('ul li.alert.alert-danger'), $errorContent); // Comprobamos que no haya errores

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Evaluación del mensaje
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals($remitente, key($message->getFrom()));
        $this->assertContains('Consulta desde el formulario de contacto de la web', $message->getBody());

        // Se general el flash de OK
        $this->assertArrayHasKey('ok', $client->getContainer()->get('session')->getBag('flashes')->all());
    }

    public function testTerminosCondiciones()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terminos-condiciones-uso');
        $this->assertTrue($crawler->filter('h1:contains("Términos y condiciones de uso")')->count() > 0);
    }

    public function testSitemap()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sitemap');
        $this->assertTrue($crawler->filter('h1:contains("Mapa del sitio web")')->count() > 0);
    }
}
