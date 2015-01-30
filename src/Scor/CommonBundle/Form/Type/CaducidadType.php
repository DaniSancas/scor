<?php

namespace Scor\CommonBundle\Form\Type;

use Scor\CommonBundle\Library\Util;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Scor\CommonBundle\Form\DataTransformer\DateToSpanishDateTransformer;

class CaducidadType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateToSpanishDateTransformer = new DateToSpanishDateTransformer();

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
            ->add(
                $builder->create('fecha', 'text', array(
                'label' => 'Fecha caducidad',
                'invalid_message' => 'Debe especificar la fecha de caducidad con formato dd/mm/aaaa',
                'attr' => array(
                    'placeholder' => 'dd/mm/aaaa'
                ))
            )->addViewTransformer($dateToSpanishDateTransformer))
            ->add('licenciaPermiso', 'choice', array(
                'choices' => Util::getLicenciasYPermisos(),
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
