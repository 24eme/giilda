<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

$t = new lime_test(14);

$t->comment("Création d'une génération");
$date = "99998877665544";

$doc = GenerationClient::getInstance()->find("GENERATION-TEST-".$date);
if($doc) { $doc->delete(); }
$doc = GenerationClient::getInstance()->find("GENERATION-TEST-".$date."-TESTSOUSGENERATION1");
if($doc) { $doc->delete(); }
$doc = GenerationClient::getInstance()->find("GENERATION-TEST-".$date."-TESTSOUSGENERATION2");
if($doc) { $doc->delete(); }

$generation = new Generation();
$generation->date_emission = $date;
$generation->type_document = "TEST";
$generation->constructId();
$generation->save();

$t->comment("Création de sous générations");

$t->is($generation->_id, "GENERATION-TEST-".$date, "id de la génération");

$sousGeneration1 = $generation->getOrCreateSubGeneration("TESTSOUSGENERATION1");
$t->is($sousGeneration1->_id, $generation->_id."-TESTSOUSGENERATION1", "id de la sous génération");
$t->is($sousGeneration1->type_document, "TESTSOUSGENERATION1", "type de document de la sous génération");
$sousGeneration1->save();
$t->is($sousGeneration1->date_emission, date('YmdHis'), "Date emission de la génération");
$t->is($sousGeneration1->statut, GenerationClient::GENERATION_STATUT_ENATTENTE, "Le status est en attente");

$sousGeneration2 = $generation->getOrCreateSubGeneration("TESTSOUSGENERATION2");
$sousGeneration2->save();
$sousGeneration1 = $generation->getOrCreateSubGeneration("TESTSOUSGENERATION1");
$t->ok($sousGeneration1->_rev, "Récupération de la sous génération");
$t->is($sousGeneration1->getMasterGeneration()->_id, $generation->_id, "Récupération de la génération maitre");
$t->is(count($generation->getSubGenerations()), 2, "Récupération des sous générations");

$generation->getOrAdd('sous_generation_types')->add(null, GenerationClient::TYPE_DOCUMENT_EXPORT_CSV);
$t->is(count($generation->sous_generation_types), 3, "Il y a 3 sous générations");
$t->is($generation->sous_generation_types->toArray(true, false), array(
    'TESTSOUSGENERATION1',
    'TESTSOUSGENERATION2',
    GenerationClient::TYPE_DOCUMENT_EXPORT_CSV
), "Récupération du type de sous génération possible");

$expected_has_ss_gen = false;
$expected_nb_ss_gen = 0;
$expected_type_ss_gen = ['FACTURE' => []];
$expected_ss_gen = $expected_type_ss_gen;

if ($application === 'bivc') {
    $expected_has_ss_gen = true;
    $expected_nb_ss_gen = 2;
    $expected_type_ss_gen = [
        GenerationClient::TYPE_DOCUMENT_FACTURES_MAILS,
        GenerationClient::TYPE_DOCUMENT_FACTURES_PAPIER
    ];
    $expected_ss_gen['FACTURE'] = $expected_type_ss_gen;
}

$t->ok(GenerationConfiguration::getInstance()->hasSousGeneration(), "retourne la config des sous generations");
$t->is($expected_has_ss_gen, GenerationConfiguration::getInstance()->hasSousGeneration('FACTURE'), "a des sous generations pour le type de doc");
$t->is($expected_type_ss_gen, GenerationConfiguration::getInstance()->getSousGeneration('FACTURE'), "Retourne les différentes sous gen avec type");
$t->is($expected_ss_gen, GenerationConfiguration::getInstance()->getSousGeneration(), "Retourne les différentes sous gen sans type");
