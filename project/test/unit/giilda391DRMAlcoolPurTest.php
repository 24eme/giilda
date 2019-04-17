<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$produit_hash_matiere_premiere = null;
$produit_hash_alcoolpur = null;
foreach(ConfigurationClient::getInstance()->getCurrent()->getProduits() as $produit) {
    if(!$produit->needTav()) { continue; }
    $produit_hash_alcoolpur = $produit->getHash();
    break;
}
foreach(ConfigurationClient::getInstance()->getCurrent()->getProduits() as $produit) {
    if(!preg_match('/MATIERES_PREMIERES/', $produit->code_douane)) { continue;}
    $produit_hash_matiere_premiere = $produit->getHash();
    break;
}

if(!$produit_hash_matiere_premiere || !$produit_hash_alcoolpur) {
    $t = new lime_test(0);
    exit;
}

$t = new lime_test(0);

$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration')->getEtablissement();
$periode = date('Ym');

foreach(DRMClient::getInstance()->viewByIdentifiant($viti->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
}

$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);

$produitMP = $drm->addProduit($produit_hash_matiere_premiere, "details");
$produitA = $drm->addProduit($produit_hash_alcoolpur, "details");

$t->comment("Test du formulaire");

$form = new DRMMatierePremiereForm($produitMP);
