<?php

namespace Scor\FrontendBundle\Controller;

use Scor\CommonBundle\Entity\Caducidad;
use Scor\CommonBundle\Form\CaducidadType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AvisadorController extends Controller
{
    /**
     * Acción que muestra la página de inserción de licencias para el aviso de su caducidad.
     *
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
        }

        return array('form' => $form->createView(), 'avisadorEmail' => $this->container->getParameter('avisador_email'));
    }

    /**
     * Acción llamada desde un CronJob, que recorre todas las licencias a punto de caducar:
     *
     * * Licencias cuya caducidad es en 3 meses o menos.
     * * Se utilizarán 3 plantillas diferentes a la hora de enviar los emails, dependiendo de su proximidad a la caducidad.
     *
     */
    public function avisarAction()
    {
        //TODO quizá no sea necesaria esta acción
    }


    /**
     * Acción llamada desde un enlace desde el email de aviso, para aquellos que no quieran seguir recibiendo alertas.
     */
    public function silenciarAction()
    {

    }
}
