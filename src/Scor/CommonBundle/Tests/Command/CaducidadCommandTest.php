<?php

namespace Scor\CommonBundle\Tests\Command;

use Scor\CommonBundle\Command\CaducidadCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CaducidadCommandTest extends WebTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CaducidadCommand());

        $command = $application->find('caducidad:email');
        $commandTester = new CommandTester($command);

        // Ejecutamos para 1 mes
        $commandTester->execute(array('command' => $command->getName(), 'meses' => 1));
        // Evaluamos si la respuesta de la consola es correcta
        $this->assertRegExp('/avisador/', $commandTester->getDisplay());
        $this->assertRegExp('/emails para 1 mes/', $commandTester->getDisplay());
        $this->assertNotRegExp('/Exception/', $commandTester->getDisplay());

        // Ejecutamos para 2 meses
        $commandTester->execute(array('command' => $command->getName(), 'meses' => 2));
        // Evaluamos si la respuesta de la consola es correcta
        $this->assertRegExp('/avisador/', $commandTester->getDisplay());
        $this->assertRegExp('/emails para 2 mes/', $commandTester->getDisplay());
        $this->assertNotRegExp('/Exception/', $commandTester->getDisplay());

        // Ejecutamos para 3 meses
        $commandTester->execute(array('command' => $command->getName(), 'meses' => 3));
        // Evaluamos si la respuesta de la consola es correcta
        $this->assertRegExp('/avisador/', $commandTester->getDisplay());
        $this->assertRegExp('/emails para 3 mes/', $commandTester->getDisplay());
        $this->assertNotRegExp('/Exception/', $commandTester->getDisplay());

        // Ejecutamos para 1 mes y pedimos el envío de un informe
        $commandTester->execute(array('command' => $command->getName(), 'meses' => 1, '--informe' => true));
        // Evaluamos si la respuesta de la consola es correcta
        $this->assertRegExp('/enviado/', $commandTester->getDisplay());
        $this->assertNotRegExp('/Exception/', $commandTester->getDisplay());
    }
}
