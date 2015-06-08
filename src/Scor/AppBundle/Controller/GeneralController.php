<?php

namespace Scor\AppBundle\Controller;

use Scor\AppBundle\Form\Type\ContactoType;
use Scor\AppBundle\Form\Type\PedirCitaType;
use Scor\AppBundle\Library\Util;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GeneralController
 * @package Scor\AppBundle\Controller
 *
 * @Cache(expires="+3 days", maxage="259200", smaxage="259200", public="true")
 */
class GeneralController extends Controller
{
    /**
     * Acción que muestra la página de inicio.
     *
     * @Route("/", name="homepage")
     * @Method(methods={"GET"})
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Acción que muestra y procesa el formulario de pedir cita.
     *
     * Si se especifica en la URL qué licencia o permiso queremos seleccionar por defecto, permitimos su ejecución.
     *
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Cache(expires="-1 days", maxage="0", smaxage="0", public="true")
     * @Route("/pedir-cita", name="pedir_cita")
     * @Method(methods={"GET", "POST"})
     * @Template()
     */
    public function pedirCitaAction(Request $request)
    {
        $licenciaPermiso = $request->get('licencias_permisos');

        $arrayParams = (array_key_exists($licenciaPermiso, Util::getLicenciasYPermisos())) ? array('licencias_permisos' => $licenciaPermiso) : null;

        $form = $this->createForm(new PedirCitaType(), $arrayParams);

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);

            if ($form->isValid())
            {
                // Mensaje para la empresa
                $messageEmpresa = \Swift_Message::newInstance()
                    ->setSubject('['.$this->container->getParameter('dominio').'] Cita previa desde la web')
                    ->setFrom($form->get('email')->getData())
                    ->setTo($this->container->getParameter('contacto_email'))
                    ->setBody(
                        $this->renderView(
                            'AppBundle:General:emailPedirCita.txt.twig',
                            array(
                                'nombre' => $form->get('nombre')->getData(),
                                'apellidos' => $form->get('apellidos')->getData(),
                                'email' => $form->get('email')->getData(),
                                'telefono' => $form->get('telefono')->getData(),
                                'licencia_permiso' => Util::getLicenciaOPermiso($form->get('licencias_permisos')->getData()),
                                'operacion' => Util::getOperacion($form->get('operacion')->getData()),
                                'fecha' => $form->get('fecha')->getData(),
                                'hora' => $form->get('hora')->getData(),
                                'observaciones' => $form->get('observaciones')->getData()
                            )
                        )
                    );

                $this->get('mailer')->send($messageEmpresa);

                // Mensaje para el cliente
                $messageCliente = \Swift_Message::newInstance()
                    ->setSubject('Cita con '.$this->container->getParameter('scor_psico'))
                    ->setFrom($this->container->getParameter('contacto_email'))
                    ->setTo($form->get('email')->getData())
                    ->setBody(
                        $this->renderView(
                            'AppBundle:General:emailRegistroCitaCliente.txt.twig',
                            array(
                                'nombre' => $form->get('nombre')->getData(),
                                'apellidos' => $form->get('apellidos')->getData(),
                                'licencia_permiso' => Util::getLicenciaOPermiso($form->get('licencias_permisos')->getData()),
                                'operacion' => Util::getOperacion($form->get('operacion')->getData()),
                                'fecha' => $form->get('fecha')->getData(),
                                'hora' => $form->get('hora')->getData()
                            )
                        )
                    );

                $this->get('mailer')->send($messageCliente);

                $request->getSession()->getFlashBag()->add('ok', 'Se ha registrado su cita. Recuerde que contactaremos con usted para confirmarla.');

                return $this->redirect($this->generateUrl('pedir_cita'));
            }
        } // @codeCoverageIgnore

        return array('form' => $form->createView());
    }

    /**
     * Acción que muestra y procesa el formulario de contacto.
     *
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Cache(expires="-1 days", maxage="0", smaxage="0", public="true")
     * @Route("/contacto", name="contacto")
     * @Method(methods={"GET", "POST"})
     * @Template()
     *
     */
    public function contactoAction(Request $request)
    {
        $form = $this->createForm(new ContactoType());

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);

            if ($form->isValid())
            {
                // Mensaje para la empresa
                $message = \Swift_Message::newInstance()
                    ->setSubject('['.$this->container->getParameter('dominio').'] Consulta desde la web')
                    ->setFrom($form->get('email')->getData())
                    ->setTo($this->container->getParameter('contacto_email'))
                    ->setBody(
                        $this->renderView(
                            'AppBundle:General:emailContacto.txt.twig',
                            array(
                                'nombre' => $form->get('nombre')->getData(),
                                'apellidos' => $form->get('apellidos')->getData(),
                                'email' => $form->get('email')->getData(),
                                'telefono' => $form->get('telefono')->getData(),
                                'consulta' => $form->get('consulta')->getData()
                            )
                        )
                    );

                $this->get('mailer')->send($message);

                // Mensaje para el cliente
                $message = \Swift_Message::newInstance()
                    ->setSubject('Consulta a '.$this->container->getParameter('scor_psico'))
                    ->setFrom($this->container->getParameter('contacto_email'))
                    ->setTo($form->get('email')->getData())
                    ->setBody(
                        $this->renderView(
                            'AppBundle:General:emailRegistroContactoCliente.txt.twig',
                            array(
                                'nombre' => $form->get('nombre')->getData(),
                                'apellidos' => $form->get('apellidos')->getData()
                            )
                        )
                    );

                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add('ok', 'Se ha enviado su email. Gracias por contactar con nosotros.');

                return $this->redirect($this->generateUrl('contacto'));
            }
        } // @codeCoverageIgnore

        return array('form' => $form->createView());
    }

    /**
     * Acción que muestra la página de términos y condiciones de uso.
     *
     * @Route("/terminos-condiciones-uso", name="terminos_condiciones")
     * @Method(methods={"GET"})
     * @Template()
     */
    public function terminosCondicionesAction()
    {
        return array();
    }

    /**
     * Acción que muestra la página del sitemap.
     *
     * @Route("/sitemap", name="sitemap")
     * @Method(methods={"GET"})
     * @Template()
     */
    public function sitemapAction()
    {
        return array();
    }
}
