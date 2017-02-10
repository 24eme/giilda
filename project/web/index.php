<?php


require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

<<<<<<< HEAD
$configuration = ProjectConfiguration::getApplicationConfiguration('declaration', 'sancerre', true);
=======
$configuration = ProjectConfiguration::getApplicationConfiguration('generique', 'prod', false);
>>>>>>> 818031e0c7569688f9c336ffad574e247c1154e6
sfContext::createInstance($configuration)->dispatch();
