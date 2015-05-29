<?php

namespace Scor\AppBundle\Tests\Entity;

use Scor\AppBundle\Entity\Caducidad;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CaducidadRepositoryFunctionalTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    /**
     * Test realizado el 3 de julio de 2014.
     *
     * Tests sujetos a diferentes resultados dependiendo de la fecha.
     */
    public function testFindCaducidadByMeses()
    {
        // 1 mes
        $caducidades = $this->em
                ->getRepository('AppBundle:Caducidad')
                ->findCaducidadByMeses(1);

        $this->assertCount(1, $caducidades, count($caducidades));

        $cad = $caducidades[0];
        $this->assertEquals(5, $cad->getId());

        // 2 meses
        $caducidades = $this->em
            ->getRepository('AppBundle:Caducidad')
            ->findCaducidadByMeses(2);

        $this->assertGreaterThan(0, $caducidades, count($caducidades));

        /* Solo hay uno y tiene el aviso desactivado
        $cad = $caducidades[0];
        $this->assertEquals(2, $cad->getId());*/

        // 3 meses
        $caducidades = $this->em
            ->getRepository('AppBundle:Caducidad')
            ->findCaducidadByMeses(3);

        $this->assertCount(1, $caducidades, count($caducidades));

        $cad = $caducidades[0];
        $this->assertEquals(4, $cad->getId());
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }
}
