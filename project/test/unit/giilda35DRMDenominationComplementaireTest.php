<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$t = new lime_test(12);

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

$t->comment("DRM qui crée des produit avec des denominations complémentaires");

$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);
$drm->save();

## Ajout d'un produit
$details = $drm->addProduit($produit_hash, 'details');
$produitLibelle = $details->getLibelle();
$details->stocks_debut->initial = 1000;
$details->sorties->consommationfamilialedegustation = 100;
$drm->update();
$drm->save();
$t->is($drm->getProduit($produit_hash, 'details')->get('stocks_fin/final'), 900, $drm->_id." : vérification du stock final sur le produit ".$details->getHash());

## Ajout d'un produit avec une dénomination
$millesime = "2016";
$t->comment("On instancie dans la DRM un produit avec denomination complémentaire : ".$millesime);

$detailsM = $drm->addProduit($produit_hash, 'details', $millesime);
$detailsM->stocks_debut->initial = 200;
$detailsM->sorties->consommationfamilialedegustation = 50;
$drm->update();
$drm->save();
$t->is($drm->getProduit($produit_hash, 'details', $millesime)->get('stocks_fin/final'), 150, $drm->_id." : vérification du stock final sur le produit ".$detailsM->getHash());

## Vérification que les deux produits existes et que leur clé soit différentes
$countProduit = 0;
$produitsByCertif = $drm->declaration->getProduitsDetailsByCertifications(true);
foreach ($produitsByCertif as $produitByCertif) {
  if(count($produitByCertif->produits) && (($produitByCertif->certification_libelle == "IGP") || ($produitByCertif->certification_libelle == "AOP"))){
    $countProduit = count($produitByCertif->produits);
  }
}
$t->is($countProduit, 2, $drm->_id." : le nombre de produits est bien 2");

## vérification que la dénomination complémentaire soit bien remplie dans le second produit
foreach ($produitsByCertif as $produitByCertif) {
  if(count($produitByCertif->produits) && (($produitByCertif->certification_libelle == "IGP") || ($produitByCertif->certification_libelle == "AOP"))){
    $produitsAdded = $produitByCertif->produits;
    $cpt = 0;
    foreach ($produitsAdded as $produitAdded) {
      if($cpt == 0){
        $t->is($produitAdded->get('denomination_complementaire'), null, $drm->_id." : La dénomination complémentaire est nulle ici.");
      }else{
        $t->is($produitAdded->get('denomination_complementaire'), $millesime, $drm->_id." : La dénomination complémentaire vaut $millesime");
      }
      $cpt++;
    }
  }
}

## vérification que le getLibelle renvoie le millésime

foreach ($produitsByCertif as $produitByCertif) {
  if(count($produitByCertif->produits) && (($produitByCertif->certification_libelle == "IGP") || ($produitByCertif->certification_libelle == "AOP"))){
    $produitsAdded = $produitByCertif->produits;
    $cpt = 0;
    foreach ($produitsAdded as $produitAdded) {
      if($cpt == 0){
        $t->is($produitAdded->getLibelle(), $produitLibelle, $drm->_id." : Le libelle du produit est bien : ".$produitAdded->getLibelle());
      }else{
        $t->is($produitAdded->getLibelle(), $produitLibelle." ".$millesime, $drm->_id." : Le libelle du produit est bien : ".$produitAdded->getLibelle());
      }
      $cpt++;
    }
  }
}

## changement de Dénomination complémentaire

$grandCru = "Grand Cru";
$hashOrigine = $detailsM->getHash();
$baseLibelle = "";
$denomComplLibelle = "";
$drm->get($hashOrigine)->set("denomination_complementaire",$grandCru);
$drm->update();
$drm->save();

$produitFounded = false;
foreach ($drm->declaration->getProduitsDetailsByCertifications(true) as $produitByCertif) {
  if(count($produitByCertif->produits) && (($produitByCertif->certification_libelle == "IGP") || ($produitByCertif->certification_libelle == "AOP"))){
    $produitsAdded = $produitByCertif->produits;
    $cpt = 0;
    foreach ($produitsAdded as $produitAdded) {
      if($cpt > 0){
        $produitFounded = $produitAdded;
        $denomComplLibelle = $produitFounded->getLibelle();
      }else{
        $baseLibelle = $produitAdded->getLibelle();
      }
      $cpt++;
    }
  }
}
$hashDest = $produitFounded->getHash();
$keyDetailsDest = $produitFounded->getKey();

$t->is($drm->get($hashDest)->get('denomination_complementaire'), $grandCru, $drm->_id." : la dénomination complémentaire est bien devenue $grandCru ");
$t->isnt($hashOrigine, $hashDest, $drm->_id." : La hash a bougé parce que $millesime a été remplacé par $grandCru");
$t->is($drm->get($hashDest)->get('stocks_fin/final'), 150, $drm->_id." : vérification du stock final sur le produit ".$hashDest);
$t->is($drm->get($hashDest)->get('sorties/consommationfamilialedegustation'), 50, $drm->_id." : vérification de la conso familiale ".$hashDest);

$existNodeMillesime = $drm->exist($hashOrigine);
$t->ok(!$existNodeMillesime, $drm->_id." : il n'y a plus de produit avec $millesime ");

# test des mvts avant validation
$drm_mvts_sorted = $drm->getMouvementsCalculeByIdentifiant($drm->identifiant);
$mvts_sorted = DRMClient::getInstance()->sortMouvementsForDRM($drm_mvts_sorted);
$cpt = 0;
foreach ($mvts_sorted as $type_mvt_drm => $mvts_grouped) {
  foreach ($mvts_grouped as $hash_produit => $mvts) {
    foreach ($mvts as $key => $mvt) {
      if(!$cpt){
        $t->is($mvt->getProduitLibelle(), $baseLibelle, $drm->_id." : le libelle du mvt du produit est bien $baseLibelle");
      }else{
        $t->is($mvt->getProduitLibelle(), $denomComplLibelle, $drm->_id." : le libelle du mvt du produit est bien $denomComplLibelle");
      }
      $cpt++;
    }
  }
}

# test des mvts après validation
$drm->validate();
$drm->save();

$mvts_viti = $drm->mouvements->{$drm->identifiant};
$t->is(count($mvts_viti), 2, $drm->_id." : la validation a généré deux mouvements chez le viti");

$mvt_consoFam = null;
$cpt = 0;
foreach ($mvts_viti as $mvt) {
  if ($mvt->type_hash == 'sorties/consommationfamilialedegustation') {
    $mvt_consoFam = $mvt;
  }
  if(!$cpt){
    $getKeyOfMvthash = str_replace("details/","",strstr($mvt_consoFam->produit_hash,"details/"));
    $t->is($getKeyOfMvthash, "DEFAUT", $drm->_id." : la fin de la hash produit du mvt est bien DEFAUT");
    $t->is($mvt->getProduitLibelle(), $baseLibelle, $drm->_id." : le libelle du mvt du produit est bien $baseLibelle");
  }else{
    $getKeyOfMvthash = str_replace("details/","",strstr($mvt_consoFam->produit_hash,"details/"));
    $t->is($getKeyOfMvthash, $keyDetailsDest, $drm->_id." : la fin de la hash produit du mvt est bien $keyDetailsDest");
    $t->is($mvt->getProduitLibelle(), $denomComplLibelle, $drm->_id." : le libelle du mvt du produit est bien $denomComplLibelle");
  }
  $cpt++;
}

$drm_mvts_sorted = DRMMouvementsConsultationView::getInstance()->getMouvementsByEtablissementAndPeriode($drm->identifiant, $drm->periode);
$mvts_sorted = DRMClient::getInstance()->sortMouvementsForDRM($drm_mvts_sorted);
$cpt = 0;
foreach ($mvts_sorted as $type_mvt_drm => $mvts_grouped) {
  foreach ($mvts_grouped as $hash_produit => $mvts) {
    foreach ($mvts as $key => $mvt) {
      if(!$cpt){
        $t->is($mvt->produit_libelle, $denomComplLibelle, $drm->_id." : le libelle du mvt du produit est bien $denomComplLibelle");
      }else{
        $t->is($mvt->produit_libelle, $baseLibelle, $drm->_id." : le libelle du mvt du produit est bien $baseLibelle");
      }
      $cpt++;
    }
  }
}
