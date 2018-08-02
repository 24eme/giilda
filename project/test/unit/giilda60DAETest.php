<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

$conf = ConfigurationClient::getInstance()->getCurrent();

$t = new lime_test(2);

$t->comment("Création d'un DAE pour un viti");

$societeViti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getSociete();
$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getEtablissement();

$identifiant = $viti->identifiant;
$daesViti = DAEClient::getInstance()->findByIdentifiant($identifiant);
foreach ($daesViti as $dae) {
    $d = DAEClient::getInstance()->find($dae->_id);
    $d->delete();
}

$dateSortie = date('Y-m-d');

$produit_hash = null;
foreach(ConfigurationClient::getInstance()->getCurrent()->getProduits() as $produit) {
    $produit_hash = $produit->getHash();
    break;
}
$periode = date('Ym');

$type_acheteur = DAEClient::ACHETEUR_TYPE_IMPORTATEUR;
$date = date('Y-m-d');
$destination = "France";
$millesime = "2016";
$volume = 36.15;
$contenant = 'Bouteille 37cl';
$prix_ht = 250.25;
$label = "Chimique";
$id = 'DAE-'.$identifiant.'-'.str_replace('-', '', $date).'-001';

$dae = DAEClient::getInstance()->createSimpleDAE($identifiant, $date);
$dae->save();

$t->is($dae->_id, $id, "L'id du doc est $id");

$dae = DAEClient::getInstance()->findLastByIdentifiantDate($identifiant, date('Ymd'));

$t->is($dae->_id, $id, "Le dernier DAE est bien récupéré par rapport à la date du jour");
