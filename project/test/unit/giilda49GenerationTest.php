<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

$t = new lime_test(5);

$t->comment("Création d'une génération");
$date = "99998877665544";

$doc = GenerationClient::getInstance()->find("GENERATION-TEST-".$date);
if($doc) { $doc->delete(); }
$doc = GenerationClient::getInstance()->find("GENERATION-TEST-".$date."-TESTSOUSGENERATION");
if($doc) { $doc->delete(); }
$doc = GenerationClient::getInstance()->find("GENERATION-TEST-".$date."-TESTSOUSGENERATION2");
if($doc) { $doc->delete(); }

$generation = new Generation();
$generation->date_emission = $date;
$generation->type_document = "TEST";
$generation->constructId();
$generation->save();

$t->is($generation->_id, "GENERATION-TEST-".$date, "id de la génération");

$sousGeneration1 = $generation->getOrCreateSubGeneration("TESTSOUSGENERATION");
$t->is($sousGeneration1->_id, $generation->_id."-TESTSOUSGENERATION", "id de la sous génération");
$sousGeneration1->save();
$sousGeneration2 = $generation->getOrCreateSubGeneration("TESTSOUSGENERATION2");
$sousGeneration2->save();
$sousGeneration1 = $generation->getOrCreateSubGeneration("TESTSOUSGENERATION");
$t->ok($sousGeneration1->_rev, "Récupération de la sous génération");
$t->is($sousGeneration1->getMasterGeneration()->_id, $generation->_id, "Récupération de la génération maitre");
$t->is(count($generation->getSubGenerations()), 2, "Récupération des sous générations");
