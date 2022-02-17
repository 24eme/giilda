<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$conf = ConfigurationClient::getInstance()->getCurrent();

$nego = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_region')->getEtablissement();
$produits = array_keys($conf->getProduits());
$produit_hash = array_shift($produits);
$periode = date('Ym');

foreach(DRMClient::getInstance()->viewByIdentifiant($nego->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
}

$t = new lime_test(3);

$t->comment("DRM Négociant non facture");

$drm = DRMClient::getInstance()->createDoc($nego->identifiant, $periode, true);
$drm->save();

$t->ok($drm->isDRMNegociant(), "C'est une DRM Négoce");
if($application == "civa") {
    $t->ok($drm->isFacturable(), "C'est une DRM facturable");
} else {
    $t->ok(!$drm->isFacturable(), "C'est une DRM non facturable");
}

$details = $drm->addProduit($produit_hash, 'details');
$details->entrees->recolte = 100;
$details->sorties->ventefrancecrd = 100;

$drm->update();
$drm->validate();
$drm->save();

$recapCvos = DRMClient::getInstance()->getRecapCvos($drm->identifiant, $drm->periode);

if($application == "civa") {
    $t->is(round($recapCvos["TOTAL"]->totalVolumeDroitsCvo, 4), 100, "Volume facturation à 100");
} else {
    $t->is(round($recapCvos["TOTAL"]->totalVolumeDroitsCvo, 4), 0, "Aucun volume n'est facturable");
}