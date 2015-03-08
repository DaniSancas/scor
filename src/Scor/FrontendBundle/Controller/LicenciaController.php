<?php

namespace Scor\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * Class LicenciaController
 * @package Scor\FrontendBundle\Controller
 *
 * @Cache(expires="+3 days", maxage="259200", smaxage="259200", public="true")
 * @Route("/licencias-permisos")
 */
class LicenciaController extends Controller
{
    /**
     * Acción que muestra la página de carnets de conducir
     *
     * @Route("/carnet-conducir", name="conducir")
     * @Method(methods={"GET"})
     * @Template()
     */
    public function conducirAction()
    {
        return array();
    }

    /**
     * Acción que muestra la página de permiso de armas
     *
     * @Route("/permiso-armas", name="armas")
     * @Method(methods={"GET"})
     * @Template()
     */
    public function armasAction()
    {
        return array();
    }

    /**
     * Acción que muestra la página de licencias para seguridad privada
     *
     * @Route("/seguridad-privada", name="seguridad")
     * @Method(methods={"GET"})
     * @Template()
     */
    public function seguridadAction()
    {
        return array();
    }

    /**
     * Acción que muestra la página de licencias para animales pot. peligrosos
     *
     * @Route("/animales-peligrosos", name="animales")
     * @Method(methods={"GET"})
     * @Template()
     */
    public function animalesAction()
    {
        return array();
    }

    /**
     * Acción que muestra la página de licencias para náutica
     *
     * @Route("/nautica", name="nautica")
     * @Method(methods={"GET"})
     * @Template()
     */
    public function nauticaAction()
    {
        return array();
    }

    /**
     * Acción que muestra la página de licencias para gruas
     *
     * @Route("/gruas", name="gruas")
     * @Method(methods={"GET"})
     * @Template()
     */
    public function gruasAction()
    {
        return array();
    }

}
