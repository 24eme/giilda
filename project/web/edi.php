<?php

ini_set('memory_limit', '512M');

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('edi', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
