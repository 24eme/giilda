<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration')->getEtablissement();
$operation = 'COVID';

$subvention = SubventionClient::getInstance()->find('SUBVENTION-'.$viti->identifiant.'-'.$operation);
if($subvention) {
    $subvention->delete();
}

$t = new lime_test(2);

$t->comment('Creation du document');

$subvention = SubventionClient::getInstance()->createDoc($viti->identifiant, $operation);

$t->is($subvention->_id, 'SUBVENTION-'.$viti->identifiant.'-'.$operation, 'id de document généré');

$subvention->save();

$t->ok($subvention->_rev, 'Enregistrement du document');
