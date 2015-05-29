#!/usr/bin/env php
<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/AppKernel.php';

use Scor\AppBundle\Command\CaducidadCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;;

$application = new Application(new AppKernel("prod", false));
$application->add(new CaducidadCommand());
$application->run();
