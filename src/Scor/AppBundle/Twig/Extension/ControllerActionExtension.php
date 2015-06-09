<?php

namespace Scor\AppBundle\Twig\Extension;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Extensión de Twig que permite obtener el nombre del Controller y Action en una vista de Twig.
 *
 * El nombre del Controller/Action será devuelto en minúsculas. P.e: 'default' o 'index'
 *
 */
class ControllerActionExtension extends \Twig_Extension
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * Constructor
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack = null)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Get Request
     *
     * @return null|\Symfony\Component\HttpFoundation\Request
     */
    private function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * Init Twig Environment
     *
     * @param \Twig_Environment $environment
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Return declared functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'getControllerName' => new \Twig_Function_Method($this, 'getControllerName'),
            'getActionName' => new \Twig_Function_Method($this, 'getActionName'),
        );
    }

    /**
     * Get current controller name
     */
    public function getControllerName()
    {
        $request = $this->getRequest();

        if(null !== $request)
        {
            $pattern = "#Controller\\\([a-zA-Z]*)Controller#";
            $matches = array();
            preg_match($pattern, $request->get('_controller'), $matches);

            return (isset($matches[1])) ? strtolower($matches[1]) : '';
        }

        return null;
    }

    /**
     * Get current action name
     */
    public function getActionName()
    {
        $request = $this->getRequest();

        if(null !== $request)
        {
            $pattern = "#::([a-zA-Z]*)Action#";
            $matches = array();
            preg_match($pattern, $request->get('_controller'), $matches);

            return (isset($matches[1])) ? strtolower($matches[1]) : '';
        }

        return null;
    }

    /**
     * Get Twig Extension Name
     *
     * @return string
     */
    public function getName()
    {
        return 'scor_controller_action_twig_extension';
    }
}

?>
