<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$t = new lime_test(13);
$t->comment("création d'une DRM en lien avec les deux contrats");

$nego = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_region')->getEtablissement();
$nego_horsregion = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_horsregion')->getEtablissement();
$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getEtablissement();

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
$t->is($drm->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 700, $drm->_id." : le stock final est impacté par les sorties de 300hl");

$export = DRMESDetailExport::freeInstance($drm);
$export->identifiant = 'BE';
$export->volume = 50;
$details->sorties->export_details->addAdvancedDetail($export);

$export = DRMESDetailExport::freeInstance($drm);
$export->identifiant = 'BE';
$export->volume = 50;
$details->sorties->export_details->addAdvancedDetail($export);
$key = $export->getKey();
$t->is($drm->getProduit($produit_hash, 'details')->get("sorties/export_details")->get($key)->getKey(), $key, $drm->_id." : les clés d'export sont conservées");

$vrac = array_shift(VracClient::getInstance()->getBySoussigne($drm->campagne, $nego->identifiant)->rows);
$contrat = DRMESDetailVrac::freeInstance($drm);
$contrat->identifiant = $vrac->value[VracSoussigneIdentifiantView::VRAC_VIEW_VALUE_ID];
$contrat->volume = 50;
$details->sorties->vrac_details->addAdvancedDetail($contrat);
$key = $contrat->getKey();
if (!$key) {
  $t->fail($drm->_id." : Pas de clé trouvée pour la sortie vrac");
}else {
  $t->is($drm->getProduit($produit_hash, 'details')->get("sorties/vrac_details")->get($key)->getKey(), $key, $drm->_id." : les clés de contrat sont conservées");
}
$drm->update();
$drm->save();

$drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($viti->identifiant, $periode);
$t->is($drm->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 550, $drm->_id." :  le stock final est impacté par les sorties de 450hl");
$t->is(count($drm->getProduit($produit_hash, 'details')->get('sorties/export_details')), 2, $drm->_id." : la DRM a bien 2 sorties export");
$t->is(count($drm->getProduit($produit_hash, 'details')->get('sorties/vrac_details')), 1, $drm->_id." : la DRM a bien une sortie vrac");
$drm->validate();
$drm->save();

$mvts_viti = $drm->mouvements->{$drm->identifiant};
$t->is(count($drm->mouvements) * count($mvts_viti), 10, $drm->_id." : la validation a généré quatre mouvements");

foreach ($mvts_viti as $mvt) {
  if ($mvt->type_hash == 'sorties/ventefrancecrd') {
    $mvt_crd = $mvt;
  }elseif ($mvt->type_hash == 'sorties/destructionperte') {
    $mvt_dest = $mvt;
  }
}
$t->is($mvt_crd->facturable, 1, $drm->_id." : le mouvement de sortie crd est facturable");
$t->is($mvt_dest->facturable, 0, $drm->_id." : le mouvement de sortie destruction n'est pas facturable");

$t->is(count(MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($viti->getSociete())), 4, $drm->_id." : on retrouve le mouvement facturable dans la vue facture");
