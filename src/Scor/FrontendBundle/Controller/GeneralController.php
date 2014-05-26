<?php

namespace Scor\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GeneralController extends Controller
{
    /**
     * Acción que muestra la página de inicio.
     *
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
