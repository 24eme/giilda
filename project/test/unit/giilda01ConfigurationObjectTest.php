<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$t = new lime_test(3);
$t->comment("Tests du document de Configuration");

$t->comment("Tests sur les changements de cépages autorisé");
$configuration = ConfigurationClient::getInstance()->getCurrent();
$t->isnt($configuration, null, "La configuration ".$configuration->_id." n'est pas nulle ");

$produits = array_keys($configuration->getProduits());
$t->ok(count($produits) > 1 ,"La configuration a au moins un produit");
$produit_hash = array_shift($produits);

$produitCepage = $configuration->get($produit_hash);
$hasCepagesAutorises = $produitCepage->hasCepagesAutorises();
if($hasCepagesAutorises){
  $cepagesAutorises = $produitCepage->getCepagesAutorises()->toArray(true,false);
  $cepagesAutorisesStr = implode(',', $cepagesAutorises).', TEST CEPAGE';
  $produitCepage->setCepagesAutorises($cepagesAutorisesStr);

  $newCepagesAutorises = $produitCepage->getCepagesAutorises()->toArray(true,false);
  $t->ok(in_array('TEST CEPAGE',$newCepagesAutorises) ,"La configuration a bien le cepage autorisé TEST CEPAGE");
}else{
  $t->ok(true ,"La configuration n'a pas de cepages autorises");
}
