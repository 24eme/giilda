<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$produit = null;
$configuration = ConfigurationClient::getInstance()->getCurrent();
foreach($configuration->getProduits() as $produit) {
    $produit = $produit->getHash();
    break;
}

$mouvementReintegration = null;
$mouvementSansDetail = null;
foreach($configuration->declaration->details as $details) {
    foreach ($details as $detail) {
        if(!$detail->hasDetails()) {
            $mouvementSansDetail = $detail;
            continue;
        }
        if($detail->getDetails() != ConfigurationDetailLigne::DETAILS_REINTEGRATION) {
            continue;
        }
        $mouvementReintegration = $detail;
    }
}


if(!$mouvementReintegration) {
    $t = new lime_test(0);
    exit;
}

$mouvementReintegrationHash = $mouvementReintegration->getParent()->getKey().'/'.$mouvementReintegration->getKey().'_details';

$t = new lime_test(7);

$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration')->getEtablissement();
$periode = date('Ym');

foreach(DRMClient::getInstance()->viewByIdentifiant($viti->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
}

$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);
$drm->addProduit($produit, "details");


$produit = $drm->getProduit($produit, "details");
$t->ok($produit->exist($mouvementReintegrationHash), 'Le details pour le mouvement en reintegration existe');
$t->ok(!$produit->exist($mouvementSansDetail->getParent()->getKey().'/'.$mouvementSansDetail->getKey().'_details'), "Le mouvement classique n'a pas de détails");

$produit->stocks_debut->initial = 1000;

$detail = DRMESDetailReintegration::freeInstance($drm);
$detail->volume = 100;
$detail->date = date('Y-m-d');

$produit->get($mouvementReintegrationHash)->addDetail($detail);
$drm->update();

$t->is(count($produit->get($mouvementReintegrationHash)->toArray(true, false)), 1, "Le tableau contient 1 détail");
$t->is($produit->get($mouvementReintegrationHash)->getFirst()->volume, 100, "Le volume est enregistré");
$t->is($produit->get($mouvementReintegrationHash)->getFirst()->date, $detail->date, "La date est enregistré");
$t->is($produit->get($mouvementReintegrationHash)->getFirst()->identifiant, $detail->date, "L'identifant est la date");
$t->is($produit->get(str_replace("_details", "", $mouvementReintegrationHash)), 100, "Le volume total de réintegration est 100");
