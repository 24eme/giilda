<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$conf = ConfigurationClient::getCurrent();
if (!($conf->declaration->exist('details/sorties/vrac')) || ($conf->declaration->get('details/sorties/vrac')->details != "VRAC")) {
    $t = new lime_test(0);
    exit(0);
}

$t = new lime_test(17);

$nego = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_region')->getEtablissement();
$nego_horsregion = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_horsregion')->getEtablissement();
$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getEtablissement();
$produits = array_keys(ConfigurationClient::getInstance()->getCurrent()->getProduits());
$produit_hash = array_shift($produits);
$periode = date('Ym');

//Suppression des DRM précédentes
foreach(DRMClient::getInstance()->viewByIdentifiant($viti->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
}

//Début des tests
$t->comment("création d'une DRM avec une sortie vrac");

$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);
$drm->save();
$details = $drm->addProduit($produit_hash, 'details');
$details->stocks_debut->initial = 1000;

$vrac = array_shift(VracClient::getInstance()->getBySoussigne($drm->campagne, $nego->identifiant)->rows);
$vracObj = VracClient::getInstance()->find($vrac->value[VracSoussigneIdentifiantView::VRAC_VIEW_VALUE_ID]);

$contrat = DRMESDetailVrac::freeInstance($drm);
$contrat->identifiant = $vrac->value[VracSoussigneIdentifiantView::VRAC_VIEW_VALUE_ID];
$contrat->volume = 100;
$details->sorties->vrac_details->addDetail($contrat);
$details->sorties->ventefrancecrd = 100;
$contrat_key = $contrat->getKey();
if (!$contrat_key) {
  $t->fail($drm->_id." : Pas de clé trouvée pour la sortie vrac");
}else {
  $t->is($drm->getProduit($produit_hash, 'details')->get("sorties/vrac_details")->get($contrat_key)->getKey(), $contrat_key, $drm->_id." : les clés de contrat sont conservées");
}
$drm->update();
$drm->save();

$drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($viti->identifiant, $periode);
$t->is($drm->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 800, $drm->_id." : le stock final est impacté");
$t->is(count($drm->getProduit($produit_hash, 'details')->get('sorties/vrac_details')), 1, $drm->_id." : la DRM a bien une sortie vrac");

$t->comment("validation de la DRM et génération des mouvements");
$drm->validate();
$drm->save();

$mvts_viti = $drm->mouvements->{$drm->identifiant};
$t->is(count($mvts_viti), 2, $drm->_id." : la validation a généré trois mouvements (viti + négo)");
foreach ($mvts_viti as $mvt) {
  if ($mvt->type_hash == 'sorties/ventefrancecrd') {
    $mvt_crd = $mvt;
  }elseif ($mvt->type_hash == 'vrac_details') {
    $mvt_vrac = $mvt;
  }
}
$t->is($mvt_vrac->facturable, 1, $drm->_id." : le mouvement de sortie vrac est facturable");
$t->is($mvt_vrac->cvo, $mvt_crd->cvo / 2, $drm->_id." : la cvo du mouvement de sortie vrac est de 50%");

$mvts_nego = $drm->mouvements->{$nego->identifiant};
$t->is(count($mvts_nego), 1, $drm->_id." : la validation a généré un mouvement chez le nego");
foreach ($mvts_nego as $mvt_nego) {
  break;
}
$t->is($mvt_nego->cvo, $mvt_vrac->cvo, $drm->_id." : la cvo du mouvement de sortie vrac nego est de 50%");

$t->is(count(MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($viti->getSociete())), 2, $drm->_id." : on retrouve le mouvement facturable dans la vue facture du viti");
$t->is(count(MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($nego->getSociete())), 1, $drm->_id." : on retrouve le mouvement facturable dans la vue facture du négo");

$t->comment("Testes sur les enlévements liés au contrat ".$vracObj->_id." de volume proposé : ".$vracObj->volume_propose);
$enlevements = VracClient::getInstance()->buildEnlevements($vracObj);
$t->is(count($enlevements), 1, $vracObj->_id." : on retrouve bien un enlévement");
$enlevement = array_shift($enlevements);
$t->is($enlevement->volume, 100, $vracObj->_id." : on retrouve l'enlévement de volume 100 hl.");

$vracObj = VracClient::getInstance()->find($vrac->value[VracSoussigneIdentifiantView::VRAC_VIEW_VALUE_ID]);
$t->is($vracObj->volume_enleve, 100, $vracObj->_id." : Le contrat a bien pour volume enlevé : ".$vracObj->volume_enleve);

$t->comment("Génère une nouvelle modificatrice et change le contrat pour un contrat hors region");
$drm_mod = $drm->generateModificative();
$vrac_details = $drm_mod->getProduit($produit_hash, 'details')->get('sorties/vrac_details');
$vrac_details->remove($contrat_key);
$vrac = array_shift(VracClient::getInstance()->getBySoussigne($drm->campagne, $nego_horsregion->identifiant)->rows);
$contrat = DRMESDetailVrac::freeInstance($drm);
$contrat->identifiant = $vrac->value[VracSoussigneIdentifiantView::VRAC_VIEW_VALUE_ID];
$contrat->volume = 100;
$vrac_details->addDetail($contrat);
$drm_mod->update();
$drm_mod->validate();
$drm_mod->save();
$t->is($drm_mod->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 800, $drm_mod->_id." : le stock final est impacté par la modification de l'exports");
$t->is(count($drm_mod->mouvements), 3, $drm_mod->_id." : la validation a impacté trois tiers (viti, nego, nego hors region)");
$mvts_nego_horsRegion =  $drm_mod->mouvements->{$nego_horsregion->identifiant}->toArray(true, false);
$mvt_nego_horsRegion = (count($mvts_nego_horsRegion))? array_shift($mvts_nego_horsRegion)['facturable'] : 0;
$t->is($mvt_nego_horsRegion, 0, $drm_mod->_id." : le mouvement du négo hors région n'est pas facturable");

$somme_cvo = 0;
foreach($drm_mod->mouvements->{$nego->identifiant} as $k => $mvt) {
  $somme_cvo += $mvt->cvo * $mvt->volume;
}
foreach($drm_mod->mouvements->{$viti->identifiant} as $k => $mvt) {
  $somme_cvo += $mvt->cvo * $mvt->volume;
}
$t->is($somme_cvo, 0, $drm_mod->_id." : Le viti est facturé de toute la cvo");
