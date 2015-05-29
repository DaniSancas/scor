<?php
namespace Scor\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Scor\AppBundle\Library\Util;

/**
 * CronJob llamado desde app/cron.php
 *
 * Ejemplo de llamada:
 * php app/cron.php caducidad:email 3 --informe -vvv
 *
 * Con esta ejecución enviará emails con caducidades entre 2 y 3 meses, enviará un informe al admin y mostrará por consola mensajes de debug
 *
 * Class CaducidadCommand
 * @package Scor\AppBundle\Command
 */
class CaducidadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('caducidad:email')
            ->setDescription('Tarea que envia emails de licencias proximas a expirar.') // Sin tildes porque sale por consola
            ->addArgument(
                'meses',
                InputArgument::REQUIRED,
                'Intervalo de meses para caducar (1..3)'
            )->addOption(
                'informe',
                null,
                InputOption::VALUE_NONE,
                'Enviar el informe de entregas al admin'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $meses = $input->getArgument('meses');

        // Si se llama con --informe, se mandará un email al webmaster con el detalle de la tarea ejecutada
        $email = ($input->getOption('informe')) ? $this->getContainer()->getParameter('webmaster_email') : null;

        // Comenzamos
        $output->writeln("Ejecucion del avisador de caducidad para ".$meses." mes(es)...");

        // Dependiendo de la opción de meses (1..3) se obtienen las caducidades pertinentes
        // También se establecen asuntos y plantillas de email específicas para no repetir siempre las mismas (medida anti-spam)
        switch($meses)
        {
            case 3:
                $caducidades = $em->getRepository('AppBundle:Caducidad')->findCaducidadByMeses(3);
                $asunto = 'Su permiso o licencia está próximo a caducar.';
                $plantilla = 'AppBundle:Cron:emailTresMeses.txt.twig';
                break;
            case 2:
                $caducidades = $em->getRepository('AppBundle:Caducidad')->findCaducidadByMeses(2);
                $asunto = 'Renueve su permiso o licencia antes de que caduque.';
                $plantilla = 'AppBundle:Cron:emailDosMeses.txt.twig';
                break;
            default:
                $caducidades = $em->getRepository('AppBundle:Caducidad')->findCaducidadByMeses(1);
                $asunto = 'Su permiso o licencia caduca este mes.';
                $plantilla = 'AppBundle:Cron:emailUnMes.txt.twig';
                break;
        }

        $contEmails = 0;
        $contErrores = 0;
        $informe = "";

        // Por cada caducidad, se enviará un email recordatorio
        foreach($caducidades as $caducidad)
        {
            try{
                $message = \Swift_Message::newInstance()
                    ->setSubject($asunto)
                    ->setFrom($this->getContainer()->getParameter('contacto_email'))
                    ->setTo($caducidad->getEmail())
                    ->setBody(
                        $this->getContainer()->get('templating')->render($plantilla,
                            array(
                                'nombre' => $caducidad->getNombre(),
                                'apellidos' => $caducidad->getApellidos(),
                                'fecha' => $caducidad->getFecha()->format('d/m/Y'),
                                'licencia_permiso' => Util::getLicenciaOPermiso($caducidad->getLicenciaPermiso()),
                                'link_cita' => 'http://'.$this->getContainer()->getParameter('dominio').$this->getContainer()->get('router')->generate('pedir_cita'),
                                'link_unsuscribe' => 'http://'.$this->getContainer()->getParameter('dominio').$this->getContainer()->get('router')->generate('desactivar_caducidad', array('id' => $caducidad->getId(), 'email' => $caducidad->getEmail()))
                            )
                        )
                    );

                // Con "debug" mostramos el cuerpo completo del email por consola
                if($output->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG)
                    $output->writeln($message->getBody(), $output::OUTPUT_PLAIN);

                if($this->getContainer()->get('mailer')->send($message))
                {
                    // Si el email se envía correctamente, incrementamos contado
                    $contEmails++;

                    // Con "very_verbose" mostramos por consola a quién se ha enviado
                    if($output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE)
                        $output->writeln("Email enviado a ".substr($message->getHeaders()->get("to"), 4));

                    // Si se va a enviar un informe, se van almacenando datos para este
                    if($email)
                        $informe .= "Email enviado a ".substr($message->getHeaders()->get("to"), 4)."\r\n";
                }
            }catch (Exception $e){
                $contErrores++;

                // Con "very_verbose" mostramos la Excepción por consola en caso de que la hubiera
                if($output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE)
                    $output->writeln("Exception: ".$e->getMessage());

                // Si se va a enviar un informe, dejamos apuntada la excepción
                if($email)
                    $informe .= "Exception: ".$e->getMessage()."\r\n";
            }
        }

        // Total enviados
        $output->writeln("Total emails para ".$meses." mes(es): ".$contEmails);
        if($email)
            $informe .= "\r\nTotal emails para ".$meses." mes(es): ".$contEmails."\r\n";

        // Total errores
        if($contErrores > 0)
            $output->writeln("Total errores: ".$contErrores);

        if($email)
            $informe .= "\r\nTotal errores: ".$contErrores."\r\n";

        // Enviamos el informe detallado
        // Informamos por consola si procede
        if($email)
        {
            if($output->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG)
                $output->writeln("\r\n\r\nEnvio de informe via email:\r\n");

            $message = \Swift_Message::newInstance()
                ->setSubject("Informe de emails para ".$meses." mes(es)")
                ->setFrom($this->getContainer()->getParameter('contacto_email'))
                ->setTo($email)
                ->setBody($informe);

            if($this->getContainer()->get('mailer')->send($message))
                $output->writeln("Informe enviado a ".$email);

            if($output->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG)
                $output->writeln($message->getBody(), $output::OUTPUT_PLAIN);
        }
    }
} 
