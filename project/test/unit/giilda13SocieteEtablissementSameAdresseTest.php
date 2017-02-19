<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

foreach (CompteTagsView::getInstance()->listByTags('test', 'test_same') as $k => $v) {
    if (preg_match('/SOCIETE-([^ ]*)/', implode(' ', array_values($v->value)), $m)) {
      $soc = SocieteClient::getInstance()->findByIdentifiantSociete($m[1]);
      $soc->delete();
    }
}


$t = new lime_test(13);
$t->comment("création d'une société et un etablissement avec meme adresse et meme contact");

$societe = SocieteClient::getInstance()->createSociete("société viti test contacts", SocieteClient::TYPE_OPERATEUR);
$societe->pays = "FR";
$societe->adresse = "42 rue dulud";
$societe->code_postal = "92100";
$societe->commune = "Neuilly sur seine";
$societe->email = 'email@example.org';
$societe->save();
$id = $societe->getidentifiant();
$compte01 = $societe->getMasterCompte();
$compte01->addTag('test', 'test');
$compte01->addTag('test', 'test_same');
$compte01->save();
$t->is($compte01->email, $societe->email, $societe->_id." : le compte de la societe a le même mail que la societe");

$etablissement = $societe->createEtablissement(EtablissementFamilles::FAMILLE_PRODUCTEUR);
$etablissement->save();

$t->is($etablissement->isSameAdresseThan($societe), true, $etablissement->_id." : un etablissement créé depuis une société a la même adresse");
$t->is($etablissement->isSameContactThan($societe), true, $etablissement->_id." : un etablissement créé depuis une société a les même contacts");
$t->is($etablissement->getMasterCompte()->_id, $societe->getMasterCompte()->_id, $etablissement->_id." : un etablissement créé depuis une société a le même compte");

$etablissement->adresse = "rue dulud";
$etablissement->save();
$compte02 = $etablissement->getMasterCompte();
$compte02->addTag('test', 'test');
$compte02->addTag('test', 'test_same');
$compte02->save();

$t->is($etablissement->isSameAdresseThan($societe), false, $etablissement->_id." : un etablissement dont on a changé l'adresse n'a plus la même adresse que la societe");
$t->is($etablissement->isSameContactThan($societe), true, $etablissement->_id." : un etablissement dont on a changé l'adresse  a les même contacts que la societe");
$t->isnt($etablissement->getMasterCompte()->_id, $societe->getMasterCompte()->_id, $etablissement->_id." : un etablissement dont on a changé l'adresse n'a pas le même compte de la societe");

$etablissement->email = "contact@exemple.fr";
$etablissement->save();
$t->is($etablissement->isSameAdresseThan($societe), false, $etablissement->_id." : un etablissement dont on a changé l'adresse et l'email n'a plus la même adresse que la societe");
$t->is($etablissement->isSameContactThan($societe), false, $etablissement->_id." : un etablissement dont on a changé l'email a les même contacts que la societe");
$t->isnt($etablissement->getMasterCompte()->_id, $societe->getMasterCompte()->_id, $etablissement->_id." : un etablissement dont on a changé l'adresse n'a pas le même compte de la societe");

$etablissement->adresse = NULL;
$etablissement->email = NULL;
$etablissement->save();
$t->is($etablissement->isSameAdresseThan($societe), true, $etablissement->_id." : un etablissement dont l'adresse et l'email sont RAZ, a la même adresse que la societe");
$t->is($etablissement->isSameContactThan($societe), true, $etablissement->_id." : un etablissement dont l'adresse et l'email sont RAZ, a les même contacts que la societe");
$t->is($etablissement->getMasterCompte()->_id, $societe->getMasterCompte()->_id, $etablissement->_id." : un etablissement dont l'adresse et l'email sont RAZ a le même compte que la societe");
