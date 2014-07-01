<?php

namespace Scor\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Caducidad
 *
 * @ORM\Table(name="caducidad")
 * @ORM\Entity(repositoryClass="Scor\CommonBundle\Entity\CaducidadRepository")
 * @UniqueEntity(
 *     fields={"email", "fecha", "licenciaPermiso"},
 *     errorPath="licenciaPermiso",
 *     message="La caducidad de esta licencia ya está registrada bajo este email."
 * )
 */
class Caducidad
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message = "Debe especificar su nombre.")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=255, nullable=true)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message = "Debe especificar su email.")
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     * @Assert\NotBlank(message = "Debe especificar la fecha de caducidad con formato dd/mm/aaaa")
     * @Assert\Date()
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="licencia_permiso", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message = "Debe elegir la licencia o permiso que quiera renovar.")
     */
    private $licenciaPermiso;

    /**
     * @var boolean
     *
     * @ORM\Column(name="manda_aviso", type="boolean", nullable=false)
     * @Assert\NotNull()
     */
    private $mandaAviso;


    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        $this->mandaAviso = true;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Caducidad
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Caducidad
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Caducidad
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Caducidad
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set licenciaPermiso
     *
     * @param string $licenciaPermiso
     * @return Caducidad
     */
    public function setLicenciaPermiso($licenciaPermiso)
    {
        $this->licenciaPermiso = $licenciaPermiso;

        return $this;
    }

    /**
     * Get licenciaPermiso
     *
     * @return string
     */
    public function getLicenciaPermiso()
    {
        return $this->licenciaPermiso;
    }

    /**
     * Set mandaAviso
     *
     * @param boolean $mandaAviso
     * @return Caducidad
     */
    public function setMandaAviso($mandaAviso)
    {
        $this->mandaAviso = $mandaAviso;

        return $this;
    }

    /**
     * Get mandaAviso
     *
     * @return boolean
     */
    public function getMandaAviso()
    {
        return $this->mandaAviso;
    }
}
