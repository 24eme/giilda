<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration')->getEtablissement();

$t = new lime_test(1);

$operation = 'COVID';

$subvention = SubventionClient::getInstance()->createOrFind($viti->identifiant, $operation);

$t->is($subvention->_id, 'SUBVENTION-'.$viti->identifiant.'-'.$operation, 'id de document généré');
