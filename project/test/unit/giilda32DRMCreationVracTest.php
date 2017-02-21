<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$conf = ConfigurationClient::getCurrent();
if (!($conf->declaration->exist('details/sorties/creationvrac')) || ($conf->declaration->get('details/sorties/creationvrac')->details != "CREATIONVRAC")) {
    $t = new lime_test(0);
    exit(0);
}

$t = new lime_test(25);

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
foreach(VracClient::getInstance()->retrieveBySoussigne($viti->identifiant)->rows as $r) {
  $vrac = DRMClient::getInstance()->find($r->id);
  $vrac->delete();
}

$t->comment("DRM qui crée des vracs");

$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);
$drm->save();

$t->is(count(VracClient::getInstance()->retrieveBySoussigne($viti->identifiant)->rows), 0, $drm->_id." : Pas de vrac pour le viti");
$t->is(count(VracClient::getInstance()->retrieveBySoussigne($nego->identifiant)->rows), 0, $drm->_id." : Pas de vrac pour le nego");
$t->is(count(VracClient::getInstance()->retrieveBySoussigne($nego_horsregion->identifiant)->rows), 0, $drm->_id." : Pas de vrac pour le nego hors région");

$details = $drm->addProduit($produit_hash, 'details');
$details->stocks_debut->initial = 1000;
$creationvrac = DRMESDetailCreationVrac::freeInstance($drm);
$creationvrac->volume = 100;
$creationvrac->prixhl = 150;
$creationvrac->acheteur = $nego->identifiant;
$creationvrac->type_contrat = VracClient::TYPE_TRANSACTION_VIN_VRAC;
$details->sorties->creationvrac_details->addDetail($creationvrac);

$drm->update();
$drm->save();
$t->is($drm->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 900, $drm->_id." : vérification du stock final");

$drm->validate();
$drm->save();
$t->is(count(VracClient::getInstance()->retrieveBySoussigne($viti->identifiant)->rows), 1, $drm->_id." : Un contrat vrac pour le viti");
$t->is(count(VracClient::getInstance()->retrieveBySoussigne($nego->identifiant)->rows), 1, $drm->_id." : Un contrat vrac pour le nego");
$t->is(count(VracClient::getInstance()->retrieveBySoussigne($nego_horsregion->identifiant)->rows), 0, $drm->_id." : Pas de vrac pour le nego hors région");

$contrat = VracClient::getInstance()->find(VracClient::getInstance()->retrieveBySoussigne($viti->identifiant)->rows[0]->id);
$t->is($contrat->type_transaction, VracClient::TYPE_TRANSACTION_VIN_VRAC, "Une sortie contrat de type vrac produit un contrat de type vrac");

$mvts_viti = MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($viti->getSociete());
$mvts_nego = MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($nego->getSociete());
$t->is(count($mvts_viti), 1, $drm->_id." : on retrouve le mouvement facturable dans la vue facture du viti");
$t->is(count($mvts_nego), 1, $drm->_id." : on retrouve le mouvement facturable dans la vue facture du négo");
$t->is($mvts_nego[0]->volume * $mvts_nego[0]->cvo, $mvts_viti[0]->volume * $mvts_viti[0]->cvo, $drm->_id." : la cvo est partagée entre le viti et le nego");
$t->isnt($mvts_viti[0]->detail_libelle, null, $drm->_id." : le mouvement a un detail_libelle");

$drm_mod = $drm->generateModificative();
$creationvrac2 = $drm_mod->getProduit($produit_hash, 'details')->sorties->creationvrac_details->get($creationvrac->getKey());
$creationvrac2->acheteur = $nego_horsregion->identifiant;
$drm_mod->update();
$drm_mod->save();
$t->is($creationvrac2->acheteur, $creationvrac2->getVrac()->acheteur_identifiant, $drm_mod->_id." : L'acheteur stocké est le même que l'acheteur du contrat");
$drm_mod->validate();
$drm_mod->save();
$t->is(count(VracClient::getInstance()->retrieveBySoussigne($viti->identifiant)->rows), 1, $drm_mod->_id." : le changement d'acheteur du mouvement de vrac ne change rien pour le viti");
$t->is(count(VracClient::getInstance()->retrieveBySoussigne($nego->identifiant)->rows), 0, $drm_mod->_id." : le changement d'acheteur du mouvement de vrac supprime le vrac pour le nego");
$t->is(count(VracClient::getInstance()->retrieveBySoussigne($nego_horsregion->identifiant)->rows), 1, $drm_mod->_id." : le changement d'acheteur du mouvement de vrac le lie avec le nego hors région");

$mvts_viti = MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($viti->getSociete());
$mvts_nego = MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($nego->getSociete());
$mvts_nego_horscvo = MouvementfactureFacturationView::getInstance()->getMouvementsNonFacturesBySociete($nego_horsregion->getSociete());
$t->is(count($mvts_viti), 3, $drm_mod->_id." : on retrouve le mouvement dans la vue facture du viti");
$t->is(count($mvts_nego), 2, $drm_mod->_id." : on obtient deux mouvements dans la vue facture du négo");
$t->is(count($mvts_nego_horscvo), 0, $drm_mod->_id." : on n'obtient pas de mouvement facturable dans la vue facture du négo hors region");


$drm_mod = $drm_mod->generateModificative();
$drm_mod->getProduit($produit_hash, 'details')->sorties->remove('creationvrac_details');
$drm_mod->getProduit($produit_hash, 'details')->sorties->add('creationvrac_details');
$drm_mod->update();
$drm_mod->validate();
$drm_mod->save();

$t->is(count(VracClient::getInstance()->retrieveBySoussigne($viti->identifiant)->rows), 0, $drm_mod->_id." : la suppression du mouvement de vrac supprime le vrac pour le viti");
$t->is(count(VracClient::getInstance()->retrieveBySoussigne($nego->identifiant)->rows), 0, $drm_mod->_id." : la suppression du mouvement de vrac supprime le vrac pour le nego");
$t->is(count(VracClient::getInstance()->retrieveBySoussigne($nego_horsregion->identifiant)->rows), 0, $drm_mod->_id." : la suppression du mouvement de vrac supprime le vrac pour le nego hors région");


$visa = '199999';
$t->comment("DRM modificatrice qui crée un contrat bouteille avec un visa : ".$visa);

$drm_mod = $drm_mod->generateModificative();
$drm_mod->save();
$creationvrac3 = DRMESDetailCreationVrac::freeInstance($drm_mod);
$creationvrac3->volume = 200;
$creationvrac3->prixhl = 150;
$creationvrac3->acheteur = $nego->identifiant;
$creationvrac3->type_contrat = VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE;
$creationvrac3->numero_archive = $visa;

$drm_mod->addProduit($produit_hash, 'details')->sorties->creationvractirebouche_details->addDetail($creationvrac3);

$drm_mod->update();
$drm_mod->save();

$drm_mod->validate();
$drm_mod->save();

$mvts = VracClient::getInstance()->retrieveBySoussigne($viti->identifiant)->rows;
$t->is(count($mvts), 1, $drm_mod->_id." : on retrouve bien un mouvement pour le viti");
$vrac = VracClient::getInstance()->find(($mvts[0]->id));
$t->isnt($vrac, null, $vrac->_id." : on retrouve bien un contrat pour le viti");
$t->is($vrac->numero_archive, $visa, $vrac->_id." : a bien pour numéro de visa ".$visa);
