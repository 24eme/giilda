<?php
require_once(dirname(__FILE__).'/../bootstrap/common.php');


$t = new lime_test(14);


$t->comment("création d'une DRM avec des sorties facturables et non");

$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getEtablissement();
$viti->crd_regime = EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU;
$viti->save();

$produits = array_keys(ConfigurationClient::getInstance()->getCurrent()->getProduits());
$produit_hash = array_shift($produits);
$periode = date('Ym');

//Suppression des DRM précédentes
foreach(DRMClient::getInstance()->viewByIdentifiant($viti->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
}

$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);

$drm->add("transmission_douane")->add("success", false);
$drm->add("transmission_douane")->add("coherente", false);
$drm->declarant->no_accises = 12345;
$drm->societe->siret = 123456789;
$drm->updateControles();
$drm->save();

$t->is($drm->controles->coherence->nb, 1, "Absence de cohérence crée un controle");
$t->is($drm->controles->transmission->nb, 1, "Erreur de transmission crée un controle");
$t->is($drm->controles->exist("vigilance"), false, "Pas de vigilance");
$t->is($drm->controles->exist("erreur"), false, "Pas d'erreur dans la DRM");
$t->is($drm->controles->exist("engagement"), false, "Pas d'engagement");



$drm->remove("transmission_douane");
$drm->updateControles();
$drm->save();

$t->is($drm->exist("transmission_douane"), false, "Suppression de la transmission douane");
$t->is($drm->exist("controles"), false, "Plus de controle");


$produits = array_keys(ConfigurationClient::getInstance()->getCurrent()->getProduits());
$produit_hash = array_shift($produits);

$details = $drm->addProduit($produit_hash, 'details');
$details->entrees->recolte = 100;
$details->sorties->destructionperte = 106;

$drm->update();
$drm->updateControles();
$drm->save();

$t->is($drm->controles->exist("erreur"), true, "Erreur dans la DRM crée un controle");
$t->is($drm->controles->erreur->nb, 1, "Erreur dans la DRM crée un controle");

$details->sorties->destructionperte = 50;
$drm->update();
$drm->updateControles();
$drm->save();


$t->is($drm->exist("controles"), false, "Plus d'erreur dans la DRM");

$t->is(is_null($drm->declarant->no_accises), false, "Numéro d'accise: ". $drm->declarant->no_accises);
$drm->declarant->no_accises = null;
$t->is(is_null($drm->declarant->no_accises), true, "Numéro d'accise vide");
$drm->updateControles();
$drm->save();
$t->is($drm->controles->exist("vigilance"), false, "Point de vigilance dans la DRM");

$drm->declarant->no_accises = 12345;
$drm->updateControles();
$drm->save();

$t->is($drm->exist("controles"), false, "Plus d'erreur dans la DRM");