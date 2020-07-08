<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration')->getEtablissement();
$operation = 'COVID';

$subvention = SubventionClient::getInstance()->find('SUBVENTION-'.$viti->identifiant.'-'.$operation, acCouchdbClient::HYDRATE_JSON);
if($subvention) {
    acCouchdbManager::getClient()->deleteDoc($subvention);
}

$t = new lime_test(5);

$t->comment('Creation du document');

$subvention = SubventionClient::getInstance()->createDoc($viti->identifiant, $operation);

$t->is($subvention->_id, 'SUBVENTION-'.$viti->identifiant.'-'.$operation, 'id de document généré');

$subvention->save();

$t->ok($subvention->_rev, 'Enregistrement du document');
$t->is($subvention->declarant->raison_sociale, $viti->raison_sociale, "Declrant Raison sociale");
$t->is($subvention->declarant->siret, $viti->siret, "Declarant Siret");
$t->ok(count($subvention->getInfosSchema()) > 0, "Schéma du champs info");

$t->comment('Étape infos');
