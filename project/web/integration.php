<?php


require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('integration', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
