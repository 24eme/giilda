<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

$t = new lime_test(6);
$t->comment('création des différentes établissements');

$societeviti = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getSociete();
$etablissementviti = EtablissementClient::getInstance()->createEtablissementFromSociete($societeviti, EtablissementFamilles::FAMILLE_PRODUCTEUR);
$etablissementviti->save();
$id = $etablissementviti->getSociete()->getidentifiant();
$compteviti = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compteviti->addTag('test', 'test');
$compteviti->save();
$t->is($compteviti->tags->automatique->toArray(true, false), array('societe', 'ressortissant', 'producteur', 'etablissement'), "Création d'un etablissement viti met à jour le compte");

$societenego = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_region')->getSociete();
$etablissementnego = EtablissementClient::getInstance()->createEtablissementFromSociete($societenego, EtablissementFamilles::FAMILLE_NEGOCIANT);
$etablissementnego->save();
$id = $etablissementnego->getSociete()->getidentifiant();
$comptenego = CompteClient::getInstance()->findByIdentifiant($id.'01');
$comptenego->addTag('test', 'test');
$comptenego->save();
$t->is($comptenego->tags->automatique->toArray(true, false), array('societe', 'ressortissant', 'negociant', 'etablissement'), "Création d'un etablissement nego met à jour le compte");

$societenego_horsregion = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_horsregion')->getSociete();
$etablissementnego_horsregion = EtablissementClient::getInstance()->createEtablissementFromSociete($societenego_horsregion, EtablissementFamilles::FAMILLE_NEGOCIANT);
$etablissementnego_horsregion->save();
$id = $etablissementnego_horsregion->getSociete()->getidentifiant();
$comptenego_horsregion = CompteClient::getInstance()->findByIdentifiant($id.'01');
$comptenego_horsregion->addTag('test', 'test');
$comptenego_horsregion->save();
$t->is($comptenego_horsregion->tags->automatique->toArray(true, false), array('societe', 'ressortissant', 'negociant', 'etablissement'), "Création d'un etablissement nego_horsregion met à jour le compte");

$societecourtier = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_courtier')->getSociete();
$etablissementcourtier = EtablissementClient::getInstance()->createEtablissementFromSociete($societecourtier, EtablissementFamilles::FAMILLE_COURTIER);
$etablissementcourtier->save();
$id = $etablissementcourtier->getSociete()->getidentifiant();
$comptecourtier = CompteClient::getInstance()->findByIdentifiant($id.'01');
$comptecourtier->addTag('test', 'test');
$comptecourtier->save();
$t->is($comptecourtier->tags->automatique->toArray(true, false), array('societe', 'intermediaire', 'etablissement', 'courtier'), "Création d'un etablissement courtier met à jour le compte");

$societeintermediaire = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_intermediaire')->getSociete();
$etablissementintermediaire = EtablissementClient::getInstance()->createEtablissementFromSociete($societeintermediaire, EtablissementFamilles::FAMILLE_REPRESENTANT);
$etablissementintermediaire->save();
$id = $etablissementintermediaire->getSociete()->getidentifiant();
$compteintermediaire = CompteClient::getInstance()->findByIdentifiant($id.'01');
$compteintermediaire->addTag('test', 'test');
$compteintermediaire->save();
$t->is($compteintermediaire->tags->automatique->toArray(true, false), array('societe', 'intermediaire', 'etablissement', 'representant'), "Création d'un etablissement intermediaire met à jour le compte");

$societecoop = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_cooperative')->getSociete();
$etablissementcoop = EtablissementClient::getInstance()->createEtablissementFromSociete($societecoop, EtablissementFamilles::FAMILLE_COOPERATIVE);
$etablissementcoop->save();
$id = $etablissementcoop->getSociete()->getidentifiant();
$comptecoop = CompteClient::getInstance()->findByIdentifiant($id.'01');
$comptecoop->addTag('test', 'test');
$comptecoop->save();
$t->is($comptecoop->tags->automatique->toArray(true, false), array('societe', 'ressortissant', 'cooperative', 'etablissement'), "Création d'un etablissement coop met à jour le compte");
