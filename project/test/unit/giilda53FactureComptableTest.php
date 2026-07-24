<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

$conf = ConfigurationClient::getInstance()->getCurrent();

$prefixComptable = null;
$codeCompteEcheance = null;
$codeCompteTVA = null;
$codeCompteLigne = null;
$nbLignes = 2;

if($application == "ivso") {
    $prefixComptable = "02";
    $codeCompteEcheance = "41100000";
    $codeCompteTVA = "44571502";
}

if($application == "ivbd") {
    $nbLignes = 1;
    $prefixComptable = "VEN";
    $codeCompteEcheance = "41110000";
    $codeCompteLigne = "75815000";
}

if($application == "bivc") {
    $prefixComptable = "VT";
    $codeCompteLigne = "75800000";
    $codeCompteEcheance = "41100000";
    $codeCompteTVA = "44571000";
}

if($application == "civa") {
    $t = new lime_test(0);
    exit(0);
}

$societeViti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getSociete();
$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getEtablissement();
foreach(DRMClient::getInstance()->viewByIdentifiant($viti->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
}
foreach(FactureSocieteView::getInstance()->findBySociete($societeViti) as $row) {
    $f = FactureClient::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON);
    FactureClient::getInstance()->deleteDoc($f);
}

$produits = array_keys($conf->getProduits());
$produit_hash = array_shift($produits);
$periode = date('Ym');
$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);
$details = $drm->addProduit($produit_hash, 'details');
$details->stocks_debut->initial = 1000;
$details->sorties->ventefrancecrd = 200;
$drm->update();
$drm->save();
$drm->validate();
$drm->save();

$facture = FactureClient::getInstance()->createFacturesBySociete($societeViti, array(
    "modele" => "DRM",
    "date_facturation" => date('Y').'-08-01',
    "date_mouvement" => null,
    "type_document" => GenerationClient::TYPE_DOCUMENT_FACTURES,
    "message_communication" => null,
    "seuil" => null,
));
$facture->save();

$paiement = $facture->add('paiements')->add();
$paiement->date = date('Y-m-d');
$paiement->montant = $facture->total_ttc / 2;
$facture->updateMontantPaiement();
$facture->save();

$facture = null;
foreach (FactureSocieteView::getInstance()->getFactureNonVerseeEnCompta() as $row) {
    $facture = FactureClient::getInstance()->find($row->id);
}

if($facture){
  foreach ($facture->lignes as $id => $mvt ) {
    $nbLignes += count($mvt->details);
  }
  $export = ExportFactureCSVFactory::getObject($application);
  ob_start();
  $export->printFacture($facture->_id);
  $exportCompta = ob_get_contents();
  ob_end_clean();
}

$t = new lime_test(9 + $nbLignes * 9);

$t->comment("Création d'un export de facturation à partir des facture pour une société");

$t->is(count(FactureSocieteView::getInstance()->getFactureNonVerseeEnCompta()), 1, "Récupération des factures non versé en compta");
$t->is(count(FactureSocieteView::getInstance()->getAllFacturesForCompta()), count(FactureSocieteView::getInstance()->getFactureNonVerseeEnCompta()), "Récupération de toutes les factures");

$arrayCompta = explode("\n",$exportCompta);

$nbLignesCSV = 0;
foreach ($arrayCompta as $cpt => $row) {
    if(!$row){
      continue;
    }
    $fieldArray = explode(";",$row);

    $t->is(count($fieldArray), 24, "Le nombre de champs pour la ligne $cpt du fichier de compta est bien ".count($fieldArray));
    $t->is($fieldArray[0], $prefixComptable, "Le préfix est ".$prefixComptable);

    $dateF = preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$fieldArray[1]);
    $t->ok($dateF, "La date de facturation a le bon format");

    $dateF = preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$fieldArray[2]);
    $t->ok($dateF, "La date d'emission a le bon format");

    $t->is($facture->numero_piece_comptable, $fieldArray[3], "Le numéro pièce comptable est celui de la facture");

    $desc = $fieldArray[4];
    $t->ok($desc, "Il y a une description comptable $desc ");

    $codeCompte = $fieldArray[5];
    $prix = $fieldArray[10];
    $sens = $fieldArray[9];

    $found = false;
    if($fieldArray[14] == "LIGNE") {
        foreach ($facture->lignes as $k => $lignes) {
            foreach ($lignes->details as $detail) {
              if((($detail->exist('code_compte') && $detail->code_compte == $codeCompte) || $codeCompteLigne == $codeCompte) && ($detail->montant_ht == $prix)){
                $found = $detail->getHash();
              }
            }
        }
        $resMontantCodeCompte = boolval($found);
        $t->ok($resMontantCodeCompte, "Le code compte $codeCompte de la ligne correspond à la ligne $found de la facture $facture->_id");
        $t->ok($resMontantCodeCompte, "Le montant $prix de la ligne correspond à la ligne $found de la facture $facture->_id");
        $t->is($sens, "CREDIT", "La ligne est un CREDIT");
    }

    if($fieldArray[14] == "TVA") {
        $t->is($codeCompte, $codeCompteTVA, "Le code compte $codeCompte de la ligne ECHEANCE est correct");
        $t->is($prix, $facture->total_taxe, "Le montant $prix de la ligne ECHEANCHE est correct");
        $t->is($sens, "CREDIT", "La ligne est un CREDIT");
    }

    if($fieldArray[14] == "ECHEANCE") {
        $t->is($codeCompte, $codeCompteEcheance, "Le code compte $codeCompte de la ligne ECHEANCE est correct");
        $t->is($prix, $facture->total_ttc, "Le montant $prix de la ligne ECHEANCHE est correct");
        $t->is($sens, "DEBIT", "Le sens de la ligne ECHEANCE est DEBIT");
    }

    $nbLignesCSV++;
}

$t->is($nbLignesCSV, $nbLignes, "Le nombre de ligne pour la compta est ".$nbLignes);


$t->is($facture->versement_comptable, 0, "La facture n'est pas versé en compta");

$t->comment("Versement de la facture en compta");

$facture->setVerseEnCompta();
$facture->save();

$t->is($facture->versement_comptable, 1, "La facture est versé en compta");
$t->is(count(FactureSocieteView::getInstance()->getFactureNonVerseeEnCompta()), 0, "Aucune facture non versé en compta");

$t->comment("Export du paiement");

$t->is(count(FactureSocieteView::getInstance()->getPaiementNonVerseeEnCompta()), 1, "Une facture ayant des paiements non versé en compta");

$facture = FactureClient::getInstance()->find(current(FactureSocieteView::getInstance()->getPaiementNonVerseeEnCompta())->id);

$export = new ExportFacturePaiementsCSV($facture, false, true);
$t->is(count(explode("\n", $export->exportFacturePaiements())), 2, "Une ligne de csv pour l'export des paiements");

$facture->paiements[0]->versement_comptable = 1;
$facture->save();

$t->is(count(FactureSocieteView::getInstance()->getPaiementNonVerseeEnCompta()), 0, "Plus de paiement non versé");

