<?php

namespace Scor\CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class CaducidadType extends AbstractType
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
                'required' => false
            ))
            ->add('fecha', 'text', array(
                'label' => 'Fecha caducidad',
                'attr' => array(
                    'placeholder' => 'dd/mm/aaaa'
                )
            ))
            ->add('licenciaPermiso', 'choice', array(
                'choices' => PedirCitaType::getLicenciasYPermisos(),
                'label' => 'Licencia/permiso'
            ))
            ->add('email', 'email', array(
                'trim' => true,
                'attr' => array(
                    'placeholder' => 'Especifique su email...'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Scor\CommonBundle\Entity\Caducidad'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'avisador_caducidad';
    }
}
