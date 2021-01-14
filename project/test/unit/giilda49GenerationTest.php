<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

$t = new lime_test(0);

$t->comment("Création d'une génération");
$date = "99998877665544";

$doc = GenerationClient::getInstance()->find("GENERATION-TEST-".$date);
if($doc) { $doc->delete; }
$doc = GenerationClient::getInstance()->find("GENERATION-TEST-".$date."-TESTSOUSGENERATION");
if($doc) { $doc->delete; }
$doc = GenerationClient::getInstance()->find("GENERATION-TEST-".$date."-TESTSOUSGENERATION2");
if($doc) { $doc->delete; }

$generation = new Generation();
$generation->date_emission = $date;
$generation->type_document = "TEST";
$generation->constructId();
$generation->save();

$t->is($generation->_id, "GENERATION-TEST-".$date, "id de la génération");

$sousSeneration1 = $generation->getOrCreateSubGeneration("TESTSOUSGENERATION");
$sousSeneration1->save();
$sousSeneration2 = $generation->getOrCreateSubGeneration("TESTSOUSGENERATION2");
$sousSeneration2->save();

$t->is($sousSeneration->_id, $generation->_id."-TESTSOUSGENERATION", "id de la sous génération");
$t->is($sousSeneration->getMasterGeneration()->id, $generation->_id, "Récupération de la génération maitre");
$t->is(count($generation->getSubGenerations()), 2, "Récupération des sous générations");
