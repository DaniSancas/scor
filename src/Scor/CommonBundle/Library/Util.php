<?php

namespace Scor\CommonBundle\Library;

class Util
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
} 