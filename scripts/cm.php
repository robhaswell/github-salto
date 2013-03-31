#!/usr/bin/env php
<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
$bootloader = new GithubSalto_Bootloader(dirname(__DIR__) . '/', 'library/');
$bootloader->setEnvironment('cli');
$bootloader->load(array('constants', 'exceptionHandler', 'errorHandler', 'defaults'));

$manager = new CM_Cli_CommandManager();
$returnCode = $manager->run(new CM_Cli_Arguments($argv));
exit($returnCode);
