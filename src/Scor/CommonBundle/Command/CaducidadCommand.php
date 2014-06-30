<?php
namespace Scor\CommonBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Scor\CommonBundle\Library\Util;


class CaducidadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('caducidad:email')
            ->setDescription('Tarea que envía emails de licencias próximas a expirar.')
            ->addArgument(
                'meses',
                InputArgument::REQUIRED,
                'Intervalo de meses para caducar (1..3)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $meses = $input->getArgument('meses');

        switch($meses)
        {
            case 3:
                $caducidades = $em->getRepository('CommonBundle:Caducidad')->findCaducidadByMeses(3);
                $asunto = 'Su permiso o licencia está próximo a caducar.';
                $plantilla = 'CommonBundle:Cron:emailTresMeses.txt.twig';
                break;
            case 2:
                $caducidades = $em->getRepository('CommonBundle:Caducidad')->findCaducidadByMeses(2);
                $asunto = 'Renueve su permiso o licencia antes de que caduque.';
                $plantilla = 'CommonBundle:Cron:emailDosMeses.txt.twig';
                break;
            default:
                $caducidades = $em->getRepository('CommonBundle:Caducidad')->findCaducidadByMeses(1);
                $asunto = 'Su permiso o licencia caduca este mes.';
                $plantilla = 'CommonBundle:Cron:emailUnMes.txt.twig';
                break;
        }

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
                                'link_unsuscribe' => ''
                            )
                        )
                    );
                $this->getContainer()->get('mailer')->send($message);
            }catch (Exception $e){
                $output->writeln("Exception: ".$e->getMessage(), OutputInterface::VERBOSITY_DEBUG);

            }
        }
    }
} 