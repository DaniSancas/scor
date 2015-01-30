<?php

namespace Scor\CommonBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateToSpanishDateTransformer implements DataTransformerInterface
{
    /**
     * Transforma un objeto DateTime a fecha española (dd/mm/aaaa) en modo texto.
     *
     * @param \DateTime $date
     *
     * @return string
     *
     * @throws TransformationFailedException si la fecha no viene en un formato en el que se pueda convertir.
     */
    public function transform($date)
    {
        if(null === $date)
        {
            return "";
        }

        try{
            // Desglosamos la fecha estándar
            $day    = intval($date->format("d"));
            $month  = intval($date->format("m"));
            $year   = intval($date->format("Y"));
        }catch(Exception $e){
            throw new TransformationFailedException($e->getMessage());
        }        
        
        if((!is_int($day) || !is_int($month) || !is_int($year)) || (!checkdate($month, $day, $year)))
        {
            throw new TransformationFailedException(sprintf('El formato de la fecha es incorrecto. Año %s, mes %s, día %s', $year, $month, $day));
        }
        
        return sprintf("%02d", $day).'/'.sprintf("%02d", $month).'/'.sprintf("%04d", $year);
    }

    /**
     * Transforma una fecha española (dd/mm/aaaa) en modo texto a un objeto DateTime.
     *
     * @param string $spanishDate
     *
     * @return \DateTime|null
     *
     * @throws TransformationFailedException si la fecha no viene en un formato en el que se pueda convertir.
     */
    public function reverseTransform($spanishDate)
    {
        if(null === $spanishDate)
        {
            return null;
        }
        
        // Desglosamos la fecha española
        $day    = intval(substr($spanishDate, 0, 2));
        $month  = intval(substr($spanishDate, 3, 2));
        $year   = intval(substr($spanishDate, 6, 4));
        
        if((strlen($spanishDate) !== 10) || (!is_int($day) || !is_int($month) || !is_int($year)) || (!checkdate($month, $day, $year)))
        {
            throw new TransformationFailedException(sprintf('El formato de la fecha es incorrecto. Año %s, mes %s, día %s', $year, $month, $day));
        }
        
        try{
            $date = new \DateTime(sprintf("%04d", $year).'-'.sprintf("%02d", $month).'-'.sprintf("%02d", $day));
        }catch(\Exception $e){
            throw new TransformationFailedException($e->getMessage());
        }
        
        return $date;
    }
}
