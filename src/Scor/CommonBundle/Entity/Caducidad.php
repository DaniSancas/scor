<?php

namespace Scor\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Caducidad
 *
 * @ORM\Table(name="caducidad")
 * @ORM\Entity(repositoryClass="Scor\CommonBundle\Entity\CaducidadRepository")
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
     * @Assert\NotBlank()
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
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     * @Assert\NotBlank()
     */
    private $fecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="licencia_permiso", type="smallint", nullable=false)
     * @Assert\NotBlank()
     */
    private $licenciaPermiso;


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
     * @param integer $licenciaPermiso
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
     * @return integer 
     */
    public function getLicenciaPermiso()
    {
        return $this->licenciaPermiso;
    }
}
