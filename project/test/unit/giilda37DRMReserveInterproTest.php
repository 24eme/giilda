<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

sfContext::createInstance($configuration);

$t = new lime_test(7);

$compteviti = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration');
$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration')->getEtablissement();
$periode = date('Ym');

$produits = array_keys(ConfigurationClient::getInstance()->getCurrent()->getProduits());
$produit_hash = array_shift($produits);

foreach(DRMClient::getInstance()->viewByIdentifiant($viti->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
}

$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);

$drm->addProduit($produit_hash, 'details');
$details = $drm->getProduit($produit_hash, 'details');
$produit = $details->getCepage()->add('reserve_interpro', 10);
$details->stocks_debut->initial = 11;
$drm->update();
$drm->save();

$t->comment($drm->_id);

$validation = new DRMValidation($drm, true);
$t->ok(!$validation->hasErreur('reserve_interpro'), "le stock fin qui n'est pas sous la réservee, ne provoque pas d'erreur");
$t->ok($validation->hasVigilance('reserve_interpro'), "le stock proche de la réservee, provoque une vigilence");


$details->stocks_debut->initial = 9;
$drm->update();
$validation = new DRMValidation($drm, true);
$t->ok($validation->hasErreur('reserve_interpro'), "le stock fin qui est sous la réservee, ne provoque pas d'erreur");
$t->ok(!$validation->hasVigilance('reserve_interpro'), "le stock sous de la réservee, provoque une vigilence (car il y a une erreur)");

$details->stocks_debut->initial = 12;
$drm->update();
$produit = $details->getCepage();
$validation = new DRMValidation($drm, true);
$t->ok(!$validation->hasErreur('reserve_interpro'), "le stock fin qui est sous la réservee, ne provoque pas d'erreur");
$t->ok(!$validation->hasVigilance('reserve_interpro'), "le stock sous de la réservee, provoque une vigilence (car il y a une erreur)");
$t->is($produit->getVolumeCommercialisable(), 2, "le volume commercialisable obtenu est bien de 2hl (12 - 10)");
