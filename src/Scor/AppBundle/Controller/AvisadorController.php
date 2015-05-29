<?php

namespace Scor\AppBundle\Controller;

use Scor\AppBundle\Entity\Caducidad;
use Scor\AppBundle\Form\Type\CaducidadType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AvisadorController
 * @package Scor\AppBundle\Controller
 *
 * @Route("/avisador-caducidad")
 */
class AvisadorController extends Controller
{
    /**
     * Acción que muestra la página de inserción de licencias para el aviso de su caducidad.
     *
     * @Route("/registrar", name="registrar_caducidad")
     * @Method(methods={"GET", "POST"})
     * @Template()
     */
    public function registrarAction()
    {
        $form = $this->createForm(new CaducidadType(), new Caducidad());

        $request = $this->get('request');

        if ($request->isMethod('POST'))
        {
            $form->submit($request);

            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $caducidad = $form->getData();

                $em->persist($caducidad);
                $em->flush();

                $request->getSession()->getFlashBag()->add('ok', 'Se ha registrado la fecha de caducidad de su licencia/permiso. Le avisaremos cuando se acerque la fecha de renovación.');

                return $this->redirect($this->generateUrl('registrar_caducidad'));
            }
        } // @codeCoverageIgnore

        return array('form' => $form->createView(), 'avisadorEmail' => $this->container->getParameter('avisador_email'));
    }

    /**
     * Acción llamada desde un enlace desde el email de aviso, para aquellos que no quieran seguir recibiendo alertas.
     *
     * Si la id y el email enviados coinciden, se actualizará la caducidad para que no mande más avisos.
     *
     * @Template()
     * @Route("/unsuscribe", name="desactivar_caducidad")
     * @Method(methods={"GET"})
     */
    public function desactivarAction(Request $request)
    {
        $id = $request->get('id');
        $email = $request->get('email');

        $em = $this->getDoctrine()->getManager();
        $caducidad = $em->getRepository('AppBundle:Caducidad')->find(array('id' => $id, 'email' => $email));

        if($caducidad)
        {
            $caducidad->setMandaAviso(false);
            $em->flush();

            $request->getSession()->getFlashBag()->add('ok', 'Se ha desactivado con éxito el recordatorio de renovación. No se enviarán más emails vinculados a la caducidad de este permiso o licencia.');
        }else{
            $request->getSession()->getFlashBag()->add('error', 'No se ha podido desactivar el recordatorio de renovación. Inténtelo de nuevo. Si el problema persiste contacte con los administradores. Disculpe las molestias.');
        }

        return array();
    }
}
