<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

if($application == "civa") {
    sfConfig::set('app_compte_synchro', true);
}

foreach (CompteTagsView::getInstance()->listByTags('test', 'test') as $k => $v) {
    if (preg_match('/SOCIETE-([^ ]*)/', implode(' ', array_values($v->value)), $m)) {
      $soc = SocieteClient::getInstance()->findByIdentifiantSociete($m[1]);
      foreach($soc->getEtablissementsObj() as $k => $etabl) {
          if ($etabl->etablissement) {
            foreach (VracClient::getInstance()->retrieveBySoussigne($etabl->etablissement->identifiant)->rows as $k => $vrac) {
              $vrac_obj = VracClient::getInstance()->find($vrac->id);
              $vrac_obj->delete();
            }
            foreach (DRMClient::getInstance()->viewByIdentifiant($etabl->etablissement->identifiant) as $id => $drm) {
              $drm = DRMClient::getInstance()->find($id);
              $drm->delete(false);
            }
          }
      }
      $soc->delete();
    }
}


$t = new lime_test(17);
$t->comment('création des différentes sociétés');

$codePostalRegion = "92100";

if($application == "ivbd") {
    $codePostalRegion = "24100";
}

$societeviti = SocieteClient::getInstance()->createSociete("société viti test", SocieteClient::TYPE_OPERATEUR);
$societeviti->pays = "FR";
$societeviti->code_postal = $codePostalRegion;
$societeviti->commune = "Neuilly sur seine";
$societeviti->insee = "94512";
$societeviti->email = "test@test.org";
$societeviti->save();
$id = $societeviti->getidentifiant();
$compteviti = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compteviti->addTag('test', 'test');
$compteviti->addTag('test', 'test_viti');
$compteviti->save();
$t->is($compteviti->tags->automatique->toArray(true, false), array('societe', 'ressortissant'), "Création de société viti crée un compte du même type");

$societeviti = SocieteClient::getInstance()->createSociete("société viti test 2", SocieteClient::TYPE_OPERATEUR);
$societeviti->pays = "FR";
$societeviti->code_postal = $codePostalRegion;
$societeviti->commune = "Neuilly sur seine";
$societeviti->insee = "94512";
$societeviti->save();
$id = $societeviti->getidentifiant();
$compteviti = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compteviti->addTag('test', 'test');
$compteviti->addTag('test', 'test_viti_2');
$compteviti->save();

$societenegocvo = SocieteClient::getInstance()->createSociete("société négo de la région test", SocieteClient::TYPE_OPERATEUR);
$societenegocvo->pays = "FR";
$societenegocvo->code_postal = $codePostalRegion;
$societenegocvo->commune = "Neuilly sur seine";
$societenegocvo->save();
$id = $societenegocvo->getidentifiant();
$compte = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compte->addTag('test', 'test');
$compte->addTag('test', 'test_nego_region');
$compte->save();
$t->is($compte->tags->automatique->toArray(true, false), array('societe', 'ressortissant'), "Création de société négo crée un compte du même type");

$societenegocvo = SocieteClient::getInstance()->createSociete("société négo 2 de la région test", SocieteClient::TYPE_OPERATEUR);
$societenegocvo->pays = "FR";
$societenegocvo->code_postal = $codePostalRegion;
$societenegocvo->commune = "Neuilly sur seine";
$societenegocvo->save();
$id = $societenegocvo->getidentifiant();
$compte = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compte->addTag('test', 'test');
$compte->addTag('test', 'test_nego_region_2');
$compte->save();
$t->is($compte->tags->automatique->toArray(true, false), array('societe', 'ressortissant'), "Création de société négo 2 crée un compte du même type");

$societenegohors = SocieteClient::getInstance()->createSociete("société négo hors région test", SocieteClient::TYPE_OPERATEUR);
$societenegohors->pays = "BE";
$societenegohors->code_postal = "1000";
$societenegohors->commune = "Bruxelles";
$societenegohors->save();
$id = $societenegohors->getidentifiant();
$compte = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compte->addTag('test', 'test');
$compte->addTag('test', 'test_nego_horsregion');
$compte->save();
$t->is($compte->tags->automatique->toArray(true, false), array('societe', 'ressortissant'), "Création de société négo hors région crée un compte du même type");


$societevitinego = SocieteClient::getInstance()->createSociete("société négo viti région test", SocieteClient::TYPE_OPERATEUR);
$societevitinego->pays = "FR";
$societevitinego->code_postal = $codePostalRegion;
$societevitinego->commune = "Paris";
$societevitinego->save();
$id = $societevitinego->getidentifiant();
$compte = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compte->addTag('test', 'test');
$compte->addTag('test', 'test_nego_viti_region');
$compte->save();
$t->is($compte->tags->automatique->toArray(true, false), array('societe', 'ressortissant'), "Création de société mixte région crée un compte du même type");


$societecourtier = SocieteClient::getInstance()->createSociete("société courtier test", SocieteClient::TYPE_COURTIER);
$societecourtier->pays = "FR";
$societecourtier->code_postal = $codePostalRegion;
$societecourtier->commune = "Neuilly sur seine";
$societecourtier->save();
$id = $societecourtier->getidentifiant();
$compte = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compte->addTag('test', 'test_courtier');
$compte->addTag('test', 'test');
$compte->save();
$t->is($compte->tags->automatique->toArray(true, false), array('societe', 'intermediaire'), "Création de société courtier crée un compte du même type");

$societeintermediaire = SocieteClient::getInstance()->createSociete("société intermédiaire test", SocieteClient::TYPE_COURTIER);
$societeintermediaire->pays = "FR";
$societeintermediaire->code_postal = $codePostalRegion;
$societeintermediaire->commune = "Neuilly sur seine";
$societeintermediaire->save();
$id = $societeintermediaire->getidentifiant();
$compte = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compte->addTag('test', 'test_intermediaire');
$compte->addTag('test', 'test');
$compte->save();
$t->is($compte->tags->automatique->toArray(true, false), array('societe', 'intermediaire'), "Création de société intermédiaire crée un compte du même type");

$societeintermediaire = SocieteClient::getInstance()->createSociete("société cooperative test", SocieteClient::TYPE_OPERATEUR);
$societeintermediaire->pays = "FR";
$societeintermediaire->code_postal = $codePostalRegion;
$societeintermediaire->commune = "Neuilly sur seine";
$societeintermediaire->save();
$id = $societeintermediaire->getidentifiant();
$compte = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compte->addTag('test', 'test_cooperative');
$compte->addTag('test', 'test');
$compte->save();
$t->is($compte->tags->automatique->toArray(true, false), array('societe', 'ressortissant'), "Création de société intermédiaire crée un compte du même type");

$societeviti->date_modification = '2017-01-01';
$societeviti->save();
try {
  $societeviti->switchStatusAndSave();
  $t->is($societeviti->statut , SocieteClient::STATUT_SUSPENDU, "Changement de statut (suspendu) de la societe viti");
  $societeviti->date_modification = '2017-01-01';
  $societeviti->save();
  $societeviti->switchStatusAndSave();
  $t->is($societeviti->statut , SocieteClient::STATUT_ACTIF, "Changement de statut (actif) de la societe viti");
}catch(sfException $e) {
  $t->fail("Changement de statut de la societe viti");
}

$societelie1 = SocieteClient::getInstance()->createSociete("société lié 1 test", SocieteClient::TYPE_OPERATEUR);
$societelie1->pays = "FR";
$societelie1->code_postal = $codePostalRegion;
$societelie1->commune = "Neuilly sur seine";
$societelie1->save();
$id = $societelie1->getidentifiant();
$compte = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compte->addTag('test', 'test_societe_lie_1');
$compte->addTag('test', 'test');
$compte->save();
$t->is($compte->tags->automatique->toArray(true, false), array('societe', 'ressortissant'), "Création de société lié 1 crée un compte du même type");

$societelie2 = SocieteClient::getInstance()->createSociete("société lié 2 test", SocieteClient::TYPE_OPERATEUR);
$societelie2->pays = "FR";
$societelie2->code_postal = $codePostalRegion;
$societelie2->commune = "Neuilly sur seine";
$societelie2->save();
$id = $societelie2->getidentifiant();
$compte = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compte->addTag('test', 'test_societe_lie_2');
$compte->addTag('test', 'test');
$compte->save();
$t->is($compte->tags->automatique->toArray(true, false), array('societe', 'ressortissant'), "Création de société lié 2 crée un compte du même type");

$t->ok(!$societelie2->exist('societes_liees'), "La champ société liés n'existe pas");
$societelie2->addAndSaveSocieteLiee($societelie1);
$t->ok($societelie2->exist('societes_liees'), 'La champ société liés existe');
$t->is($societelie2->societes_liees[0], $societelie1->_id, "L'id de la société 1 a été ajouté dans la société 2");
$societelie1 = SocieteClient::getInstance()->find($societelie1->_id);
$t->ok($societelie1->exist('societes_liees'), 'La champ société liés existe');
$t->is($societelie1->societes_liees[0], $societelie2->_id, "L'id de la société 2 a été ajouté dans la société 1");


