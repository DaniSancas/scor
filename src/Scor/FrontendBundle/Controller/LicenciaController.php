<?php

namespace Scor\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * @Cache(expires="+3 days", maxage="259200", smaxage="259200", public="true")
 */
class LicenciaController extends Controller
{
    /**
     * Acción que muestra la página de carnets de conducir
     *
     * @Template()
     */
    public function conducirAction()
    {
        return array();
    }

    /**
     * Acción que muestra la página de permiso de armas
     *
     * @Template()
     */
    public function armasAction()
    {
        return array();
    }

    /**
     * Acción que muestra la página de licencias para seguridad privada
     *
     * @Template()
     */
    public function seguridadAction()
    {
        return array();
    }

    /**
     * Acción que muestra la página de licencias para animales pot. peligrosos
     *
     * @Template()
     */
    public function animalesAction()
    {
        return array();
    }

    /**
     * Acción que muestra la página de licencias para náutica
     *
     * @Template()
     */
    public function nauticaAction()
    {
        return array();
    }

    /**
     * Acción que muestra la página de licencias para gruas
     *
     * @Template()
     */
    public function gruasAction()
    {
        return array();
    }

}
