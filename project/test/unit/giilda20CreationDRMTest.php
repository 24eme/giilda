<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$t = new lime_test(8);
$t->comment("création d'une DRM en lien avec les deux contrats");

$nego = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_region')->getEtablissement();
$nego_horsregion = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_horsregion')->getEtablissement();
$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getEtablissement();

$contrat_nego = true;
//$contrat_horsregion =

$produits = array_keys(ConfigurationClient::getInstance()->getCurrent()->getProduits());
$produit_hash = array_shift($produits);

$periode = date('Ym');
if ($drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($viti->identifiant, $periode)) {
  $drm->delete(false);
}

$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);
$drm->save();
$t->isnt($drm->periode, null, $drm->_id." : période indiquée");
$t->isnt($drm->declarant->raison_sociale, null, $drm->_id." : raison sociale du déclaration renseignée");

$details = $drm->addProduit($produit_hash, 'details');
$details->stocks_debut->initial = 1000;
$drm->update();
$drm->save();
$t->is($drm->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 1000, $drm->_id." : vérification du stock final");

$details->sorties->ventefrancecrd = 200;
$details->sorties->destructionperte = 100;
$drm->update();
$drm->save();
$t->is($drm->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 700, $drm->_id." : vérification que le stock final est impacté par les sorties de 300hl");

$drm->validate();
$drm->save();
$mvts_viti = $drm->mouvements->{$drm->identifiant};
$t->is(count($drm->mouvements) * count($mvts_viti), 2, $drm->_id." : la validation a généré deux mouvements");
foreach ($mvts_viti as $mvt) {
  if ($mvt->type_hash == 'sorties/ventefrancecrd') {
    $mvt_crd = $mvt;
  }elseif ($mvt->type_hash == 'sorties/destructionperte') {
    $mvt_dest = $mvt;
  }
}
$t->is($mvt_crd->facturable, 1, $drm->_id." : le mouvement de sortie crd est facturable");
$t->is($mvt_dest->facturable, 0, $drm->_id." : le mouvement de sortie destruction n'est pas facturable");

$t->is(count(MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($viti->getSociete())), 1, $drm->_id." : on retrouve le mouvement facturable dans la vue facture");
