<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

if($application = "civa") {
    sfConfig::set('app_compte_synchro', true);
}

sfConfig::set('societe_configuration_societe', array('extras' => array('cvi' => array("nom" => "CVI"), 'siret' => array("nom" => "SIRET"))));

foreach (CompteTagsView::getInstance()->listByTags('test', 'test_extra') as $k => $v) {
    if (preg_match('/SOCIETE-([^ ]*)/', implode(' ', array_values($v->value)), $m)) {
      $soc = SocieteClient::getInstance()->findByIdentifiantSociete($m[1]);
      $soc->delete();
    }
    if (preg_match('/ETABLISSEMENT-([^ ]*)/', implode(' ', array_values($v->value)), $m)) {
      $etb = EtablissementClient::getInstance()->findByIdentifiant($m[1]);
      if ($etb) {
          $etb->delete();
      }
    }
}

SocieteClient::getInstance()->clearSingleton();

$t = new lime_test(1);

$t->is(count(SocieteConfiguration::getInstance()->getExtras()), 2, "La configuration contient 2 champs extras");

$t->comment("Création de la société");

$societe = SocieteClient::getInstance()->createSociete("société viti test extra", SocieteClient::TYPE_OPERATEUR);
$societe->pays = "FR";
$societe->adresse = "42 rue dulud";
$societe->code_postal = "92100";
$societe->commune = "Neuilly sur seine";
$societe->save();

$id = $societe->getidentifiant();
$compte01 = $societe->getMasterCompte();
$compte01->addTag('test', 'test');
$compte01->addTag('test', 'test_extra');
$compte01->save();

$t->comment("Création de l'établissement");

$etablissement = $societe->createEtablissement(EtablissementFamilles::FAMILLE_PRODUCTEUR);
$etablissement->nom = "établissement viti test extra";
$etablissement->num_interne = $etablissement->identifiant."001";
$etablissement->save();

$compte = $etablissement->getMasterCompte();
$compte->addTag('test', 'test');
$compte->addTag('test', 'test_extra');
$compte->save();
