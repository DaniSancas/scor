<?php
namespace Scor\CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class PedirCitaType extends AbstractType
{
    /**
    * Array() de licencias y permisos
    *
    * @var array
    */
    private static $licenciasYPermisos = array(
        ''          => '-- Elija una licencia o permiso --',
        'conducir'  => 'Carnet de conducir',
        'perros'    => 'Animales peligrosos',
        'armas'     => 'Permiso de armas',
        'seguridad' => 'Seguridad privada',
        'gruas'     => 'Grúas',
        'nautica'   => 'Náutica',
    );

    /**
     * Array() de operaciones posibles para una cita
     *
     * @var array
     */
    private static $operaciones = array(
        'obtencion'     => 'Obtención',
        'renovacion'    => 'Renovación'
    );

    /**
    * Devuelve el array de licencias y permisos
    *
    * @return array
    */
    public static function getLicenciasYPermisos()
    {
        return self::$licenciasYPermisos;
    }

    /**
     * Devuelve el array de operaciones
     *
     * @return array
     */
    public static function getOperaciones()
    {
        return self::$operaciones;
    }

    /**
     * Dada una clave del array de licencias y permisos, devuelve su texto
     *
     * @param string $licenciaOPermiso
     * @return string|null
     */
    public static function getLicenciaOPermiso($licenciaOPermiso)
    {
        $array = self::getLicenciasYPermisos();

        return (array_key_exists($licenciaOPermiso, $array)) ? $array[$licenciaOPermiso] : null;
    }

    /**
     * Dada una clave del array de operaciones, devuelve su texto
     *
     * @param string $operacion
     * @return string|null
     */
    public static function getOperacion($operacion)
    {
        $array = self::getOperaciones();

        return (array_key_exists($operacion, $array)) ? $array[$operacion] : null;
    }


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
                'label' => 'Licencia o permiso',
                'choices' => $this->getLicenciasYPermisos()
            ))
            ->add('operacion', 'choice', array(
                'choices' => $this->getOperaciones(),
                'expanded' => true,
                'label' => 'Operación',
                'attr' => array(
                    'class' => 'no-asterisco'
                )
            ))
            ->add('fecha', 'text', array(
                'trim' => true
            ))
            ->add('hora', 'text', array(
                'trim' => true
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
        ;
    }
  
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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
                new Length(array(
                    'min' => 9,
                    'max' => 15,
                    'minMessage' => 'Nombre demasiado corto. Debe contener {{ limit }} caracteres como mínimo.',
                    'maxMessage' => 'Nombre demasiado largo. Debe contener {{ limit }} caracteres como máximo.'
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

    public function getName()
    {
        return 'pedir_cita';
    }
}
?>
