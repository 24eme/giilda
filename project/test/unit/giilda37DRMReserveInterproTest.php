<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

sfContext::createInstance($configuration);

$t = new lime_test(17);

$compteviti = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration');
$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration')->getEtablissement();
$annee = date('Y') - 1;
$periode = $annee.'06';

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
$drm->save();

$produit = $details->getCepage();
$validation = new DRMValidation($drm, true);
$t->ok(!$validation->hasErreur('reserve_interpro'), "le stock fin qui est sous la réservee, ne provoque pas d'erreur");
$t->ok(!$validation->hasVigilance('reserve_interpro'), "le stock sous de la réservee, provoque une vigilence (car il y a une erreur)");
$t->is($produit->getVolumeCommercialisable(), 2, "le volume commercialisable obtenu est bien de 2hl (12 - 10)");

$periode = $annee.'07';
$drm2 = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);

$drm2->addProduit($produit_hash, 'details');
$details = $drm2->getProduit($produit_hash, 'details');
$produit = $details->getCepage();

$t->is($drm2->_get('precedente'), $drm->_id, "La drm précédente est stockée");
$t->ok($produit->exist('reserve_interpro'), 'Le champ réserve interpro reste bien stocké');
$t->is($produit->getRerserveIntepro(), 10, "la reserve interpro est la même que pour la DRM précédente");
$t->is($produit->total, 12, "le stock total est la même que pour la DRM précédente");
$t->is($produit->getVolumeCommercialisable(), 2, "le volume commercialisable est bien le même que pour la DRM précédente");

$periode = $annee.'08';
$drm3 = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);

$drm3->addProduit($produit_hash, 'details');
$details = $drm3->getProduit($produit_hash, 'details');
$produit = $details->getCepage();

$t->is($drm3->_get('precedente'), $drm2->_id, "La drm précédente est stockée");
$t->ok($produit->exist('reserve_interpro'), 'Le champ réserve interpro reste bien stocké');
$t->is($produit->getRerserveIntepro(), 10, "la reserve interpro est la même que pour la DRM précédente même si on change de campagne");
$t->is($produit->total, 0, "le stock total a été réinitialisé");
$t->is($produit->getVolumeCommercialisable(), -10, "le volume commercialisable a été réinitialisé");
