<?php

namespace Scor\CommonBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CaducidadRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CaducidadRepository extends EntityRepository
{
    /**
     * Devuelve todas aquellas licencias que caduquen en 1..3 meses
     *
     * @param int $meses
     * @return array Caducidades
     */
    public function findCaducidadByMeses($meses = 1)
    {
        $q = $this->getEntityManager()->createQuery('SELECT c FROM CommonBundle:Caducidad c WHERE c.fecha >= :inicio AND c.fecha < :fin');

        switch($meses)
        {
            case 3:
                $inicio = '+2 month';
                $fin = '+3 month';
                break;
            case 2:
                $inicio = '+1 month';
                $fin = '+2 month';
                break;
            default:
                $inicio = 'now';
                $fin = '+1 month';
                break;
        }

        $q->setParameter('inicio', new \DateTime($inicio));
        $q->setParameter('fin', new \DateTime($fin));

        return $q->getResult();
    }
}
