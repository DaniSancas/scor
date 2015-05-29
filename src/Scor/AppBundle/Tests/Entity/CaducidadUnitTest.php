<?php

namespace Scor\AppBundle\Tests\Entity;

use Scor\AppBundle\Entity\Caducidad;
use Scor\AppBundle\Library\Util;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CaducidadUnitTest extends WebTestCase
{
    private $caducidad = null;

    public function testCaducidad()
    {
        $this->caducidad = new Caducidad();

        $this->assertNotNull($this->caducidad);
        $this->assertInstanceOf('Scor\AppBundle\Entity\Caducidad', $this->caducidad);

        $this->caducidad->setNombre('Nombre');
        $this->assertEquals($this->caducidad->getNombre(), 'Nombre');

        $this->caducidad->setApellidos('Apellidos');
        $this->assertEquals($this->caducidad->getApellidos(), 'Apellidos');

        $this->caducidad->setEmail('email@email.com');
        $this->assertEquals($this->caducidad->getEmail(), 'email@email.com');

        $this->caducidad->setFecha(new \DateTime('+1 day'));
        $this->assertEquals($this->caducidad->getFecha(), new \DateTime('+1 day'));

        $arrayLicenciasPermisos = Util::getLicenciasYPermisos();
        $this->caducidad->setLicenciaPermiso('perros');
        $this->assertEquals($arrayLicenciasPermisos[$this->caducidad->getLicenciaPermiso()], Util::getLicenciaOPermiso('perros'));

        $this->caducidad->setMandaAviso(false);
        $this->assertEquals($this->caducidad->getMandaAviso(), false);
    }
}
