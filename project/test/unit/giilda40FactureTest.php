<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

$t = new lime_test(18);
$t->comment("Création d'une facture à partir des DRM pour une société");

$paramFacturation =  array(
    "modele" => "DRM",
    "date_facturation" => date('Y-m-d'),
    "date_mouvement" => null,
    "type_document" => GenerationClient::TYPE_DOCUMENT_FACTURES,
    "message_communication" => null,
    "seuil" => null,
);

$societeViti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getSociete();
$facture = FactureClient::getInstance()->createAndSaveFacturesBySociete($societeViti, $paramFacturation);

$facture->save();
$t->ok($facture, "La facture est créée");

$t->is($facture->identifiant, $societeViti->identifiant, "La facture appartient à la société demandé");
$t->is($facture->total_ht, 450, "Le total HT est de 300 €");
$t->is($facture->total_ttc, 540, "Le total TTC est de 360 €");
$t->is($facture->total_taxe, 90, "Le total de taxe est de 60 €");

$generation = FactureClient::getInstance()->createGenerationForOneFacture($facture);

$t->ok($generation, "La génération est créée");

$t->comment("Test d'une nouvelle facturation sur la société pour s'assurer qu'aucune facture ne sera créée");

$t->ok(!FactureClient::getInstance()->createAndSaveFacturesBySociete($societeViti, $paramFacturation), "La facture n'a pas été créée");

$t->comment("Création d'un avoir à partir de la facture");

$t->ok($facture->isRedressable(), "La facture est redressable");

$avoir = FactureClient::getInstance()->defactureCreateAvoirAndSaveThem($facture);
$t->ok($avoir, "L'avoir est créé");
$t->is($avoir->identifiant, $societeViti->identifiant, "L'avoir appartient à la même société que la facture");
$t->ok($avoir->isAvoir(), "L'avoir est marqué comme un avoir");
$t->is($avoir->total_ht, -450, "Le total TTC est de -300 €");
$t->is($avoir->total_ttc, -540, "Le total TTC est de -360 €");
$t->is($avoir->total_taxe, -90, "Le total TTC est de -60 €");
$t->ok(!$avoir->isRedressable(), "L'avoir n'est pas redressable");

$t->is($facture->avoir, $avoir->_id, "L'avoir est conservé dans la facture");
$t->ok($facture->isRedressee(), "La facture est au statut redressé");
$t->ok(!$facture->isRedressable(), "La facture n'est pas redressable");
