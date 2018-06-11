<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$conf = ConfigurationClient::getCurrent();

$t = new lime_test(8 + 2*12);

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

$nbMouvementEntreeRecolte = 0;
$volumeTotal = 0;
$prixTotal = 0;
$facturable = true;
$coefficientFacturation = 1;

$dateMouvement = new DateTime($drm->getDate());
foreach($drm->mouvements->get($nego->identifiant) as $mouvement) {
    if($mouvement->type_hash == "entrees/recolte") {
        $nbMouvementEntreeRecolte += 1;
        $volumeTotal += $mouvement->getQuantite();
        $prixTotal += $mouvement->getPrixHt();
        if(!$mouvement->facturable) {
            $facturable = false;
        }
        if(!$mouvement->coefficient_facturation) {
            $coefficientFacturation = -1;
        }
        $t->ok($mouvement->volume > 0, "Le volume est supérieur à 0");
        $t->is($mouvement->date, $dateMouvement->format('Y-m-d'), "La date du mouvement est ".$dateMouvement->format('Y-m-d'));
        $dateMouvement = $dateMouvement->modify("last day of next month");
    }
}

$t->is($nbMouvementEntreeRecolte, 12, "Les mouvements de recolté on été scindé en 12");
$t->ok($facturable, "Tous les mouvements sont factruables");
$t->is($coefficientFacturation, 1, "Le coefficient de facturation de tous les mouvements est 1");
$t->is($volumeTotal, $details->entrees->recolte, "L'ensemble des mouvements couvrent le volume total d'entrée récolte");
$t->is($prixTotal, $details->entrees->recolte * $details->getCVOTaux(), "Le prix Ht est la quantité * La CVO du produit");

$recapCvos = DRMClient::getInstance()->getRecapCvos($drm->identifiant, $drm->periode);

$t->is(round($recapCvos["TOTAL"]->totalVolumeDroitsCvo, 4), $details->entrees->recolte, "Le volume du recap CVO est de ".$details->entrees->recolte);
$t->is($recapCvos["TOTAL"]->totalPrixDroitCvo, $details->entrees->recolte * $details->getCVOTaux(), "Le prix Ht de la du récap CVO est OK");
