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

$t = new lime_test(8);

$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration')->getEtablissement();
$periode = date('Ym');

foreach(DRMClient::getInstance()->viewByIdentifiant($viti->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
}

$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);

$produitMP = $drm->addProduit($produit_hash_matiere_premiere, "details");
$produitA = $drm->addProduit($produit_hash_alcoolpur, "details");
$produitB = $drm->addProduit($produit_hash_alcoolpur, "details", "Ratafia");

$t->comment("Test du formulaire");

$produitB->tav = 40;
$produitMP->stocks_debut->initial = 100;

$form = new DRMMatierePremiereForm($produitMP);

$t->is($form['stocks_debut']->getValue(), $produitMP->stocks_debut->initial, "Le stock de début est intialisé");
$t->is($form['sorties'][$produitA->getHash()]['volume']->getValue(), null, "Le volume de sortie est vide");
$t->is($form['sorties'][$produitA->getHash()]['tav']->getValue(), null, "Le tav du produit est vide");
$t->is($form['sorties'][$produitB->getHash()]['volume']->getValue(), null, "Le volume de sortie est vide");
$t->is($form['sorties'][$produitB->getHash()]['tav']->getValue(), 40, "Le tav du produit est vide");
$t->is(count($form['sorties']), 2, "Le formulaire a 2 produits");

$values = $form->getDefaults();
$values['stocks_debut'] = 120;

$form->bind($values);

$t->ok($form->isValid(), "Le form est valide");

$form->save();

$t->is($produitMP->stocks_debut->initial, $values['stocks_debut'], "Le stock de début a été enregistré");
