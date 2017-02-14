<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$t = new lime_test(23);
$t->comment("création d'une DRM avec des sorties facturables et non");

$nego = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_region')->getEtablissement();
$nego_horsregion = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_horsregion')->getEtablissement();
$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getEtablissement();

$produits = array_keys(ConfigurationClient::getInstance()->getCurrent()->getProduits());
$produit_hash = array_shift($produits);
$periode = date('Ym');
foreach(DRMClient::getInstance()->viewByIdentifiant($viti->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
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
$export_key1 = $export->getKey();

$export = DRMESDetailExport::freeInstance($drm);
$export->identifiant = 'BE';
$export->volume = 50;
$details->sorties->export_details->addAdvancedDetail($export);
$export_key2 = $export->getKey();
$t->is($drm->getProduit($produit_hash, 'details')->get("sorties/export_details")->get($export_key2)->getKey(), $export_key2, $drm->_id." : les clés d'export sont conservées");

$vrac = array_shift(VracClient::getInstance()->getBySoussigne($drm->campagne, $nego->identifiant)->rows);
$contrat = DRMESDetailVrac::freeInstance($drm);
$contrat->identifiant = $vrac->value[VracSoussigneIdentifiantView::VRAC_VIEW_VALUE_ID];
$contrat->volume = 50;
$details->sorties->vrac_details->addAdvancedDetail($contrat);
$contrat_key = $contrat->getKey();
if (!$contrat_key) {
  $t->fail($drm->_id." : Pas de clé trouvée pour la sortie vrac");
}else {
  $t->is($drm->getProduit($produit_hash, 'details')->get("sorties/vrac_details")->get($contrat_key)->getKey(), $contrat_key, $drm->_id." : les clés de contrat sont conservées");
}
$drm->update();
$drm->save();

$drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($viti->identifiant, $periode);
$t->is($drm->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 550, $drm->_id." : le stock final est impacté par les sorties de 450hl");
$t->is(count($drm->getProduit($produit_hash, 'details')->get('sorties/export_details')), 2, $drm->_id." : la DRM a bien 2 sorties export");
$t->is(count($drm->getProduit($produit_hash, 'details')->get('sorties/vrac_details')), 1, $drm->_id." : la DRM a bien une sortie vrac");

$t->comment("validation de la DRM et génération des mouvements");
$drm->validate();
$drm->save();

$mvts_viti = $drm->mouvements->{$drm->identifiant};
$t->is(count($mvts_viti), 5, $drm->_id." : la validation a généré cinq mouvements chez le viti");
foreach ($mvts_viti as $mvt) {
  if ($mvt->type_hash == 'sorties/ventefrancecrd') {
    $mvt_crd = $mvt;
  }elseif ($mvt->type_hash == 'sorties/destructionperte') {
    $mvt_dest = $mvt;
  }elseif ($mvt->type_hash == 'vrac_details') {
    $mvt_vrac = $mvt;
  }
}
$t->is($mvt_crd->facturable, 1, $drm->_id." : le mouvement de sortie crd est facturable");
$t->is($mvt_dest->facturable, 0, $drm->_id." : le mouvement de sortie destruction n'est pas facturable");
$t->is($mvt_vrac->facturable, 1, $drm->_id." : le mouvement de sortie vrac est facturable");
$t->is($mvt_vrac->cvo, $mvt_crd->cvo / 2, $drm->_id." : la cvo du mouvement de sortie vrac est de 50%");

$mvts_nego = $drm->mouvements->{$nego->identifiant};
$t->is(count($mvts_nego), 1, $drm->_id." : la validation a généré un mouvement chez le nego");
foreach ($mvts_nego as $mvt_nego) {
  break;
}
$t->is($mvt_nego->cvo, $mvt_vrac->cvo, $drm->_id." : la cvo du mouvement de sortie vrac nego est de 50%");

$t->is(count(MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($viti->getSociete())), 4, $drm->_id." : on retrouve le mouvement facturable dans la vue facture");

$t->comment("Génère une modificatrice et change les exports");
$drm_mod = $drm->generateModificative();
$drm_mod->save();
$t->is($drm_mod->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 550, $drm_mod->_id." : le stock final est conservé par la modificatrice");

$drm_mod->getProduit($produit_hash, 'details')->get('sorties/export_details')->remove($export_key1);
$drm_mod->update();
$t->is($drm_mod->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 600, $drm_mod->_id." : le stock final est impacté par la suppression d'un des exports");

$export = DRMESDetailExport::freeInstance($drm);
$detail = $drm_mod->getProduit($produit_hash, 'details')->get('sorties/export_details')->get($export_key2);
$export->identifiant = $detail->identifiant;
$export->volume = 100;
$export->key = $detail->key;
$drm_mod->getProduit($produit_hash, 'details')->get('sorties/export_details')->addAdvancedDetail($export);
$drm_mod->update();
$drm_mod->save();
$t->is($detail->getKey(), $export->getKey(), $drm_mod->_id." : Le détails doit avoir la même clé que l'export qui le remplace");
$t->is($drm_mod->getProduit($produit_hash, 'details')->get('sorties/export_details')->get($export_key2)->volume, 100, $drm_mod->_id." : le volume de l'export a été changé");
$t->is($drm_mod->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 550, $drm_mod->_id." : le stock final est impacté par la modification de l'exports");
$t->is(count($drm_mod->getProduit($produit_hash, 'details')->get('sorties/export_details')), 1, $drm_mod->_id." : il ne reste plus qu'un export");
$drm_mod->validate();
$drm_mod->save();
$mvts_viti = $drm_mod->mouvements->{$drm_mod->identifiant};
$t->is(count($mvts_viti) * count($drm_mod->mouvements), 2, $drm_mod->_id." : la validation a généré deux mouvements (tous pour le viti)");

$t->comment("Génère une nouvelle modificatrice et change le contrat pour un contrat hors region");
$drm_mod = $drm_mod->generateModificative();
$vrac_details = $drm_mod->getProduit($produit_hash, 'details')->get('sorties/vrac_details');
$vrac_details->remove($contrat_key);
$vrac = array_shift(VracClient::getInstance()->getBySoussigne($drm->campagne, $nego_horsregion->identifiant)->rows);
$contrat = DRMESDetailVrac::freeInstance($drm);
$contrat->identifiant = $vrac->value[VracSoussigneIdentifiantView::VRAC_VIEW_VALUE_ID];
$contrat->volume = 50;
$vrac_details->addAdvancedDetail($contrat);
$drm_mod->update();
$drm_mod->validate();
$drm_mod->save();
$t->is($drm_mod->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 550, $drm_mod->_id." : le stock final est impacté par la modification de l'exports");
$t->is(count($drm_mod->mouvements), 3, $drm_mod->_id." : la validation a impacté trois tiers (viti, nego, nego hors region)");
$t->is($drm_mod->mouvements->{$nego_horsregion->identifiant}->toArray(true, false)[0]->facturable, 0, $drm_mod->_id." : le mouvement du négo hors région n'est pas facturable");

$somme_cvo = 0;
foreach($drm_mod->mouvements->{$nego->identifiant} as $k => $mvt) {
  $somme_cvo += $mvt->cvo * $mvt->volume;
}
foreach($drm_mod->mouvements->{$viti->identifiant} as $k => $mvt) {
  $somme_cvo += $mvt->cvo * $mvt->volume;
}
$t->is($somme_cvo, 0, $drm_mod->_id." : Le viti est facturé de toute la cvo");
