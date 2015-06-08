<?php

namespace Scor\AppBundle\Form\Type;

use Scor\AppBundle\Library\Util;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Regex;

class PedirCitaType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array(
                'trim' => true,
                'attr' => array(
                    'placeholder' => 'Especifique su nombre...'
                )
            ))
            ->add('apellidos', 'text', array(
                'trim' => true,
                'required' => false
            ))
            ->add('email', 'email', array(
                'trim' => true,
                'attr' => array(
                    'placeholder' => 'Especifique su email...'
                )
            ))
            ->add('telefono', 'text', array(
                'trim' => true,
                'label' => 'Teléfono',
                'attr' => array(
                    'placeholder' => 'Especifique su teléfono...'
                )
            ))
            ->add('licencias_permisos', 'choice', array(
                'label' => 'Licencia/permiso',
                'choices' => Util::getLicenciasYPermisos()
            ))
            ->add('operacion', 'choice', array(
                'choices' => Util::getOperaciones(),
                'expanded' => true,
                'label' => 'Operación',
                'attr' => array(
                    'class' => 'no-asterisco'
                )
            ))
            ->add('fecha', 'text', array(
                'trim' => true,
                'attr' => array(
                    'placeholder' => 'Especifique una fecha...'
                )
            ))
            ->add('hora', 'text', array(
                'trim' => true,
                'attr' => array(
                    'placeholder' => 'Especifique una hora...'
                )
            ))
            ->add('observaciones', 'textarea', array(
                'required' => false
            ))
            ->add('aviso', 'checkbox', array(
                'label' => "La fecha y hora elegidas son orientativas. Nos pondremos en contacto con usted para confirmar la cita. Marque esta casilla si ha leído el aviso y está conforme.",
                'label_attr' => array(
                    'class' => 'no-asterisco'
                )
            ))
            ->add('enviar', 'submit', array(
                'attr' => array(
                    'class' => 'btn btn-lg btn-success'
                )
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $collectionConstraint = new Collection(array(
            'nombre' => array(
                new NotBlank(array('message' => 'Debe especificar su nombre.')),
                new Length(array(
                    'min' => 3, 
                    'max' => 50, 
                    'minMessage' => 'Nombre demasiado corto. Debe contener {{ limit }} caracteres como mínimo.',
                    'maxMessage' => 'Nombre demasiado largo. Debe contener {{ limit }} caracteres como máximo.'
                ))
            ),
            'apellidos' => array(),
            'telefono' => array(
                new NotBlank(array('message' => 'Debe especificar su teléfono.')),
                new Regex(array(
                    'pattern' => '/^\+?[0-9\s]+$/',
                    'message' => 'El teléfono solo puede contener números y espacios',)),
                new Length(array(
                    'min' => 9,
                    'max' => 18,
                    'minMessage' => 'Teléfono demasiado corto. Debe contener {{ limit }} caracteres como mínimo.',
                    'maxMessage' => 'Teléfono demasiado largo. Debe contener {{ limit }} caracteres como máximo.'
                ))
            ),
            'email' => array(
                new NotBlank(array('message' => 'Debe especificar su email.')),
                new Email(array('message' => 'Dirección de email inválida.'))
            ),
            'licencias_permisos' => array(
                new NotBlank(array('message' => 'Debe elegir una licencia o permiso que quiera obtener o renovar.'))
            ),
            'operacion' => array(
                new NotBlank(array('message' => 'Debe especificar si quiere obtener o renovar la licencia o permiso.'))
            ),
            'fecha' => array(
                new NotBlank(array('message' => 'Debe especificar una fecha para la cita.'))
            ),
            'hora' => array(
                new NotBlank(array('message' => 'Debe especificar una hora para la cita.'))
            ),
            'observaciones' => array(),
            'aviso' => array(
                new NotBlank(array('message' => 'Debe marcar el aviso como leído, expresando así su conformidad.'))
            ),
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pedir_cita';
    }
}
?>
