<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$conf = ConfigurationClient::getCurrent();

$t = new lime_test(4);

$nego = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_region')->getEtablissement();
$produits = array_keys(ConfigurationClient::getInstance()->getCurrent()->getProduits());
$produit_hash = array_shift($produits);
$periode = date('Ym');

foreach(DRMClient::getInstance()->viewByIdentifiant($nego->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
}

$t->comment("DRM Négociant avec récolte");

$drm = DRMClient::getInstance()->createDoc($nego->identifiant, $periode, true);
$drm->save();

$t->ok($drm->isDRMNegoce(), "C'est une DRM Négoce");

$details = $drm->addProduit($produit_hash, 'details');
$details->entrees->recolte = 100;

$drm->update();
$drm->validate();
$drm->save();

foreach($drm->mouvements->get($nego->identifiant) as $mouvement) {
    if($mouvement->type_hash == "entrees/recolte") {
        $t->is($mouvement->facturable, -1, "Le mouvement de récolte est facturable");
        $t->is($mouvement->getQuantite(), 100, "La quantité est facturable est de 100");
        $t->is($mouvement->getPrixHt(), 100 * $details->getCVOTaux(), "Le prix est la quantité * La CVO du produit");
    }
}
