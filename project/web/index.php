<?php


require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('declaration', 'ivso', false);
sfContext::createInstance($configuration)->dispatch();
