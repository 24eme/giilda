<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

if($application != "declarvins") {
    $t = new lime_test(0);
    exit(0);
}

$viti = json_decode(file_get_contents(sfConfig::get('sf_test_dir')."/data/declarvins/ETABLISSEMENT.json"));

//$conf = ConfigurationClient::getInstance()->getCurrent();

foreach(GenerationClient::getInstance()->findHistoryWithType(array(GenerationClient::TYPE_DOCUMENT_FACTURES, GenerationClient::TYPE_DOCUMENT_FACTURES_MAILS)) as $row) {
    GenerationClient::getInstance()->deleteDoc(GenerationClient::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON));
}

//Suppression des DRM précédentes
foreach(DRMClient::getInstance()->viewByIdentifiant($viti->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k, acCouchdbClient::HYDRATE_JSON);
  acCouchdbManager::getClient()->deleteDoc($drm);

}

$t = new lime_test(0);

$t->comment("Chargement d'une DRM");

$jsonDRM = json_decode(file_get_contents(sfConfig::get('sf_test_dir')."/data/declarvins/DRM.json"));
DRMClient::getInstance()->storeDoc($jsonDRM);


