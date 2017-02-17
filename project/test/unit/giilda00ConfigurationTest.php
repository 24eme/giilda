<?php

require_once(dirname(__FILE__).'/../../config/ProjectConfiguration.class.php');
require_once dirname(__FILE__).'/../../lib/vendor/symfony/test/bootstrap/unit.php';

$t = new lime_test(21);
$t->comment("Test de l'existence des configurations");

$applications = array(
    null,
    "generique",
    "ivso",
    "ivbd",
    "bivc",
);
foreach($applications as $application) {
    if($application) {
        $configuration = ProjectConfiguration::getApplicationConfiguration($application, 'dev', true);
        $t->is(get_class(ProjectConfiguration::getActive()), $application."Configuration");
    } else {
        $t->is(get_class(ProjectConfiguration::getActive()), "ProjectConfiguration");
    }

    try {
        VracConfiguration::getInstance();
        ($application) ? $t->pass() : $t->fail("La configuration vrac ne doit pas être chargé quand il n'y a pas d'application");
    } catch(Exception $e) {
        ($application) ? $t->fail("La configuration vrac doit être chargé : ".$application) : $t->pass();
    }

    try {
        FactureConfiguration::getInstance();
        ($application) ? $t->pass() : $t->fail("La configuration facture ne doit pas être chargé quand il n'y a pas d'application");
    } catch(Exception $e) {
        ($application) ? $t->fail("La configuration facture doit être chargé : ".$application) : $t->pass();
    }

    try {
        DRMConfiguration::getInstance();
        ($application) ? $t->pass() : $t->fail("La configuration drm ne doit pas être chargé quand il n'y a pas d'application");
    } catch(Exception $e) {
        ($application) ? $t->fail("La configuration drm doit être chargé : ".$application) : $t->pass();
    }

    try {
        SV12Configuration::getInstance();
        ($application) ? $t->pass() : $t->fail("La configuration sv12 ne doit pas être chargé quand il n'y a pas d'application");
    } catch(Exception $e) {
        ($application) ? $t->fail("La configuration sv12 doit être chargé : ".$application) : $t->pass();
    }

    try {
        StatistiqueConfiguration::getInstance();
        ($application) ? $t->pass() : $t->fail("La configuration statistique ne doit pas être chargé quand il n'y a pas d'application");
    } catch(Exception $e) {
        ($application) ? $t->fail("La configuration statistique doit être chargé : ".$application) : $t->pass();
    }
}
