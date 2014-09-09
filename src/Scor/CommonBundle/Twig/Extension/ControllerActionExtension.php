<?php

namespace Scor\CommonBundle\Twig\Extension;

use Symfony\Component\HttpFoundation\Request;

/**
 * Extensión de Twig que permite obtener el nombre del Controller y Action en una vista de Twig.
 * 
 * El nombre del Controller/Action será devuelto en minúsculas. P.e: 'default' o 'index'
 * 
 */
class ControllerActionExtension extends \Twig_Extension
{
    /**
         * @var Request
         */
    protected $request;

   /**
        * @var \Twig_Environment
        */
    protected $environment;
    
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'get_controller_name' => new \Twig_Function_Method($this, 'getControllerName'),
            'get_action_name' => new \Twig_Function_Method($this, 'getActionName'),
        );
    }

    /**
        * Get current controller name
        */
    public function getControllerName()
    {
        if(null !== $this->request)
        {
            $pattern = "#Controller\\\([a-zA-Z]*)Controller#";
            $matches = array();
            preg_match($pattern, $this->request->get('_controller'), $matches);

            return (isset($matches[1])) ? strtolower($matches[1]) : '';
        }
        
    }

    /**
        * Get current action name
        */
    public function getActionName()
    {
        if(null !== $this->request)
        {
            $pattern = "#::([a-zA-Z]*)Action#";
            $matches = array();
            preg_match($pattern, $this->request->get('_controller'), $matches);

            return (isset($matches[1])) ? strtolower($matches[1]) : '';
        }
    }

    public function getName()
    {
        return 'amcb_controller_action_twig_extension';
    }
}

?>
