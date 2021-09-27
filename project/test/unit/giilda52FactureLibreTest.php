<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

if(!count(ComptabiliteClient::getInstance()->findCompta()->getAllIdentifiantsAnalytiquesArrayForCompta())) {
    $t = new lime_test(0);
    exit(0);
}

$t = new lime_test(15);

if($doc = MouvementsFactureClient::getInstance()->find("MOUVEMENTSFACTURE-TEST")) {
    $doc->delete();
}

$keyCompta = key(ComptabiliteClient::getInstance()->findCompta()->getAllIdentifiantsAnalytiquesArrayForCompta());

$t->comment("Création d'un document de mouvements de facturation libre");

$societeViti = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getSociete();

$docMouvementsLibres = MouvementsFactureClient::getInstance()->createMouvementsFacture();
$docMouvementsLibres->set('_id', "MOUVEMENTSFACTURE-TEST");

$form = new FactureMouvementsEditionForm($docMouvementsLibres);

$values["libelle"] = "Test opération";
$values["mouvements"]["nouveau"]["nouveau"]["identifiant"] = $societeViti->_id;
$values["mouvements"]["nouveau"]["nouveau"]["identifiant_analytique"] = $keyCompta;
$values["mouvements"]["nouveau"]["nouveau"]["libelle"] = "Bouchons";
$values["mouvements"]["nouveau"]["nouveau"]["prix_unitaire"] = 1.50;
$values["mouvements"]["nouveau"]["nouveau"]["quantite"] = 10.00;
if (FactureConfiguration::getInstance()->hasTvaChoices()) {
  $values["mouvements"]["nouveau"]["nouveau"]["taux_tva"] = '0.100';
}
$values["_revision"] = $docMouvementsLibres->_rev;

$form->bind($values);

$t->ok($form->isValid(), "Le formulaire est valide");

$form->save();

$totalHT = 15;
if (FactureConfiguration::getInstance()->hasTvaChoices()) {
  $totalTTC = 16.5;
  $totalTaxe = 1.5;
} else {
  $totalTTC = 18;
  $totalTaxe = 3;
}

$mouvement = $docMouvementsLibres->mouvements->getFirst()->getFirst();
$form = new FactureMouvementsEditionForm($docMouvementsLibres);
$defaultValues = $form->getDefaults();

$t->ok($docMouvementsLibres->_rev, "Le document a été enregistré");
$t->is($docMouvementsLibres->libelle, "Test opération", "Le libellé est bien enregistré");
$t->is($defaultValues["mouvements"][$mouvement->getParent()->getKey()][$mouvement->getKey()]["quantite"], $values["mouvements"]["nouveau"]["nouveau"]["quantite"], "La quantité du formulaire n'a pas bougé");
$t->is($mouvement->quantite, $values["mouvements"]["nouveau"]["nouveau"]["quantite"], "La quantité est celle saisie dans le formulaire");
$t->is($docMouvementsLibres->getNbMvts(), 1, "Le document à 1 mouvements de facturation");
$t->is($docMouvementsLibres->getNbSocietes(), 1, "La document à 1 société");
$t->is($docMouvementsLibres->getTotalHt(), $totalHT, "Le montant total HT est de 15 €");
$t->is($docMouvementsLibres->getTotalHtAFacture(), $totalHT, "Le montant total HT à facturer est de 15 €");

$t->comment("Génération de la facture");

$paramFacturation =  array(
    "modele" => "MouvementsFacture",
    "date_facturation" => date('Y-m-d'),
    "date_mouvement" =>  null,
    "type_document" => GenerationClient::TYPE_DOCUMENT_FACTURES,
    "message_communication" => null,
    "seuil" => null,
);

$mouvementsFacture = array($societeViti->identifiant => FactureClient::getInstance()->getFacturationForSociete($societeViti));
$mouvementsFacture = FactureClient::getInstance()->filterWithParameters($mouvementsFacture, $paramFacturation);

$facture = FactureClient::getInstance()->createAndSaveFacturesBySociete($societeViti, $paramFacturation);
$facture->save();

$t->is($facture->total_ht, $totalHT, "Le total TTC est de ".$totalHT." €");
$t->is($facture->total_ttc, $totalTTC, "Le total TTC est de ".$totalTTC." €");
$t->is($facture->total_taxe, $totalTaxe, "Le total TTC est de ".$totalTaxe." €");

$nbLignes = 0;
foreach($facture->lignes as $lignes) {
    foreach($lignes->details as $ligne) {
        $nbLignes++;
    }
}

$t->is($nbLignes, 1, "La facture à 1 lignes");

$generation = FactureClient::getInstance()->createGenerationForOneFacture($facture);

$t->ok($generation, "La génération est créée");

$t->comment("Test d'une nouvelle facturation sur la société pour s'assurer qu'aucune facture ne sera pas créée");

$t->ok(!FactureClient::getInstance()->createAndSaveFacturesBySociete($societeViti, $paramFacturation), "La facture n'a pas été créée");
