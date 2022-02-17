<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$t = new lime_test(7);
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

$detail = $configuration->declaration->details->sorties->add('test');
$detail->facturable = 1;
$t->is($detail->isFacturable(), true, "La sortie est facturable pour les viticulteurs");
$detail = $configuration->declaration->details->sorties->remove('test');

$t->is(ConfigurationCepage::isCodeDouaneNeedTav('1B1B001S'), false, "Le code douane 1B1B001S ne nécessite pas une saisie de TAV");
$t->is(ConfigurationCepage::isCodeDouaneNeedTav('ALCOOL_AUTRE_SUP_18'), true, "Le libellé fiscal ALCOOL_AUTRE_SUP_18 nécessite une saisie de TAV");
$t->is(ConfigurationCepage::isCodeDouaneNeedTav('MATIERES_PREMIERES_ALCOOLS'), false, "Le code douane ne nécessite pas une saisie de TAV");
