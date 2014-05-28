<?php

namespace Scor\FrontendBundle\Controller;

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
}
