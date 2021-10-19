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

$comptabilite = ComptabiliteClient::getInstance()->findCompta();
$keyCompta = key($comptabilite->getAllIdentifiantsAnalytiquesArrayForCompta());
$analytique = new stdClass();
$analytique->identifiant_analytique_numero_compte = 1111;
$analytique->identifiant_analytique = 2222;
$analytique->identifiant_analytique_libelle_compta = "Tva 10%";
$analytique->identifiant_analytique_taux_tva = 0.1;
$comptabilite->identifiants_analytiques->add('1111_2222', $analytique);
$comptabilite->save();

$t->comment("Création d'un document de mouvements de facturation libre");

$societeViti = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getSociete();

$docMouvementsLibres = MouvementsFactureClient::getInstance()->createMouvementsFacture();
$docMouvementsLibres->set('_id', "MOUVEMENTSFACTURE-TEST");
$key = EtablissementClient::getInstance()->getFirstIdentifiant($societeViti->identifiant);
$mvt = $docMouvementsLibres->mouvements->add($key)->add("11111");
$mvt->region = ($societeViti->getRegionViticole(false))? $societeViti->getRegionViticole() : $societeViti->type_societe;

$form = new FactureMouvementsEditionForm($docMouvementsLibres);

$values["libelle"] = "Test opération";
$values["mouvements"][$key]["11111"]["identifiant"] = $societeViti->_id;
$values["mouvements"][$key]["11111"]["identifiant_analytique"] = $keyCompta;
$values["mouvements"][$key]["11111"]["libelle"] = "Bouchons";
$values["mouvements"][$key]["11111"]["prix_unitaire"] = 1.50;
$values["mouvements"][$key]["11111"]["quantite"] = 10.00;

$values["mouvements"]["nouveau"]["nouveau"]["identifiant"] = $societeViti->_id;
$values["mouvements"]["nouveau"]["nouveau"]["identifiant_analytique"] = '1111_2222';
$values["mouvements"]["nouveau"]["nouveau"]["libelle"] = "etiquettes";
$values["mouvements"]["nouveau"]["nouveau"]["prix_unitaire"] = 2;
$values["mouvements"]["nouveau"]["nouveau"]["quantite"] = 5;
$values["_revision"] = $docMouvementsLibres->_rev;

$form->bind($values);

$t->ok($form->isValid(), "Le formulaire est valide");

$form->save();


$totalHT = 25;
$totalTTC = 29;
$totalTaxe = 4;

$mouvement = $docMouvementsLibres->mouvements->getFirst()->getFirst();
$form = new FactureMouvementsEditionForm($docMouvementsLibres);
$defaultValues = $form->getDefaults();

$t->ok($docMouvementsLibres->_rev, "Le document a été enregistré");
$t->is($docMouvementsLibres->libelle, "Test opération", "Le libellé est bien enregistré");
$t->is($defaultValues["mouvements"][$key]["11111"]["quantite"]+$values["mouvements"]["nouveau"]["nouveau"]["quantite"], 15, "La quantité du formulaire n'a pas bougé");
$t->is($mouvement->quantite, $values["mouvements"][$key]["11111"]["quantite"], "La quantité est celle saisie dans le formulaire");
$t->is($docMouvementsLibres->getNbMvts(), 2, "Le document à 2 mouvements de facturation");
$t->is($docMouvementsLibres->getNbSocietes(), 1, "La document à 1 société");
$t->is($docMouvementsLibres->getTotalHt(), $totalHT, "Le montant total HT est de 25 €");
$t->is($docMouvementsLibres->getTotalHtAFacture(), $totalHT, "Le montant total HT à facturer est de 25 €");

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

$t->is($nbLignes, 2, "La facture à 2 lignes");

$generation = FactureClient::getInstance()->createGenerationForOneFacture($facture);

$t->ok($generation, "La génération est créée");

$t->comment("Test d'une nouvelle facturation sur la société pour s'assurer qu'aucune facture ne sera pas créée");

$t->ok(!FactureClient::getInstance()->createAndSaveFacturesBySociete($societeViti, $paramFacturation), "La facture n'a pas été créée");
