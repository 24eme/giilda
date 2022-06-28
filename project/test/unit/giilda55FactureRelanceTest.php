<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

$conf = ConfigurationClient::getInstance()->getCurrent();

if(!FactureConfiguration::getInstance()->getPaiementsActif()) {
    $t = new lime_test(0);
    exit(0);
}

$t = new lime_test(9);

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
$details = $drm->addProduit($produit_hash, 'details', '1er produit');
$details->stocks_debut->initial = 1000;
$details->sorties->ventefrancecrd = 200;
$drm->update();
$drm->save();
$drm->validate();
$drm->save();

$paramFacture = array(
    "modele" => "DRM",
    "date_facturation" => date('Y').'-08-01',
    "date_mouvement" => null,
    "type_document" => GenerationClient::TYPE_DOCUMENT_FACTURES,
    "message_communication" => null,
    "seuil" => null,
);

$facture1 = FactureClient::getInstance()->createAndSaveFacturesBySociete($societeViti, $paramFacture);
$facture1->save();

$t->ok($facture1, "Facture n°1 créée");

$drm = DRMClient::getInstance()->find($drm->_id)->generateModificative();
$details = $drm->addProduit($produit_hash, 'details', '2ème produit');
$details->stocks_debut->initial = 1000;
$details->sorties->ventefrancecrd = 200;
$drm->update();
$drm->save();
$drm->validate();
$drm->save();

$facture2 = FactureClient::getInstance()->createAndSaveFacturesBySociete($societeViti, $paramFacture);
$facture2->save();

$t->ok($facture2, "Facture n°2 créée");

$drm = DRMClient::getInstance()->find($drm->_id)->generateModificative();
$details = $drm->addProduit($produit_hash, 'details', '3ème produit');
$details->stocks_debut->initial = 1000;
$details->sorties->ventefrancecrd = 200;
$drm->update();
$drm->save();
$drm->validate();
$drm->save();

$facture3 = FactureClient::getInstance()->createAndSaveFacturesBySociete($societeViti, $paramFacture);
$facture3->save();

$t->ok($facture3, "Facture n°3 créée");

$csv = ExportFactureRelanceCSV::getHeaderCsv();
foreach(FactureEtablissementView::getInstance()->getFactureNonPaye() as $row) {
    $export = new ExportFactureRelanceCSV($row->id, false);
    $csv .= $export->export();
}

$t->is(count(explode("\n", $csv)) - 1, 4, "Le CSV comporte 4 lignes");

$t->is(count(FactureEtablissementView::getInstance()->getFactureNonPaye()), 3, "3 factures non payées");

$paiement = $facture1->add('paiements')->add();
$paiement->date = date('Y-m-d');
$paiement->montant = $facture1->total_ttc;
$facture1->updateMontantPaiement();
$facture1->save();

$t->is(count(FactureEtablissementView::getInstance()->getFactureNonPaye()), 2, "2 factures non payées après le paiment de la première facture");

$paiement = $facture2->add('paiements')->add();
$paiement->date = date('Y-m-d');
$paiement->montant = $facture2->total_ttc / 2;
$facture2->updateMontantPaiement();
$facture2->save();

$t->is(count(FactureEtablissementView::getInstance()->getFactureNonPaye()), 2, "Toujours 2 factures non payées après un paiement partiel");

$paiement = $facture2->add('paiements')->add();
$paiement->date = date('Y-m-d');
$paiement->montant = $facture2->total_ttc / 2;
$facture2->updateMontantPaiement();
$facture2->save();

$t->is(count(FactureEtablissementView::getInstance()->getFactureNonPaye()), 1, "1 facture non payée après un 2ème paiement complémentaire");

FactureClient::getInstance()->defactureCreateAvoirAndSaveThem($facture3);

$t->is(count(FactureEtablissementView::getInstance()->getFactureNonPaye()), 0, "0 facture non payée après que la 3ème facture a été redréssée");


