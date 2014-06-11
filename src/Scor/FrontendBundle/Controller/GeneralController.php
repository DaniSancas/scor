<?php

namespace Scor\FrontendBundle\Controller;

use Scor\CommonBundle\Form\PedirCitaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Scor\CommonBundle\Form\ContactoType;

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

    /**
     * Acción que muestra y procesa el formulario de pedir cita.
     *
     * @Template()
     */
    public function pedirCitaAction()
    {
        $form = $this->createForm(new PedirCitaType());

        $request = $this->get('request');

        if ($request->isMethod('POST'))
        {
            $form->submit($request);

            if ($form->isValid())
            {
                // Mensaje para la empresa
                $messageEmpresa = \Swift_Message::newInstance()
                    ->setSubject('['.$this->container->getParameter('dominio').'] Cita previa desde la web')
                    ->setFrom($form->get('email')->getData())
                    ->setTo($this->container->getParameter('contacto_email'))
                    ->setBody(
                        $this->renderView(
                            'FrontendBundle:General:emailPedirCita.txt.twig',
                            array(
                                'nombre' => $form->get('nombre')->getData(),
                                'apellidos' => $form->get('apellidos')->getData(),
                                'email' => $form->get('email')->getData(),
                                'telefono' => $form->get('telefono')->getData(),
                                'licencia_permiso' => PedirCitaType::getLicenciaOPermiso($form->get('licencias_permisos')->getData()),
                                'operacion' => PedirCitaType::getOperacion($form->get('operacion')->getData()),
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
                            'FrontendBundle:General:emailRegistroCitaCliente.txt.twig',
                            array(
                                'nombre' => $form->get('nombre')->getData(),
                                'apellidos' => $form->get('apellidos')->getData(),
                                'licencia_permiso' => PedirCitaType::getLicenciaOPermiso($form->get('licencias_permisos')->getData()),
                                'operacion' => PedirCitaType::getOperacion($form->get('operacion')->getData()),
                                'fecha' => $form->get('fecha')->getData(),
                                'hora' => $form->get('hora')->getData()
                            )
                        )
                    );

                $this->get('mailer')->send($messageCliente);

                $request->getSession()->getFlashBag()->add('ok', 'Se ha registrado su cita. Recuerde que contactaremos con usted para confirmarla.');

                return $this->redirect($this->generateUrl('pedir_cita'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * Acción que muestra y procesa el formulario de contacto.
     *
     * @Template()
     */
    public function contactoAction()
    {
        $form = $this->createForm(new ContactoType());

        $request = $this->get('request');

        if ($request->isMethod('POST'))
        {
            $form->submit($request);

            if ($form->isValid())
            {
                $message = \Swift_Message::newInstance()
                    ->setSubject('['.$this->container->getParameter('dominio').'] Consulta desde la web')
                    ->setFrom($form->get('email')->getData())
                    ->setTo($this->container->getParameter('contacto_email'))
                    ->setBody(
                        $this->renderView(
                            'FrontendBundle:General:emailContacto.txt.twig',
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

                $request->getSession()->getFlashBag()->add('ok', 'Se ha enviado su email. Gracias por contactar con nosotros.');

                return $this->redirect($this->generateUrl('contacto'));
            }
        }

        return array('form' => $form->createView(), 'contactoEmail' => $this->container->getParameter('contacto_email'));
    }

    /**
     * Acción que muestra la página de términos y condiciones de uso.
     *
     * @Template()
     */
    public function terminosCondicionesAction()
    {
        return array();
    }

    /**
     * Acción que muestra la página del sitemap.
     *
     * @Template()
     */
    public function sitemapAction()
    {
        return array();
    }
}
