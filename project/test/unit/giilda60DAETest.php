<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

$conf = ConfigurationClient::getInstance()->getCurrent();

$t = new lime_test(11);

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
$date = (new DateTime())->modify('-1 month')->format('Y-m-d');
$destination = "FR";
$millesime = "2017";
$quantite = 36.15;
$contenant = 'Bouteille 75 cl';
$prix_ht = 250.25;
$label = 'BIO';
$acheteurAccises = "FR12345678910";
$acheteurNom = 'Nom du super importateur';

$id = 'DAE-'.$identifiant.'-'.str_replace('-', '', $date).'-001';

$dae = DAEClient::getInstance()->createSimpleDAE($identifiant, $date);

$t->is($dae->date, $date, "La date du dae est \"".$date."\"");
$t->is($dae->date_saisie, date('Y-m-d'), "La date de saisie du dae est \"".date('Y-m-d')."\"");

$form = new DAENouveauForm($dae);

$values = array(
    'produit_key' => $produit_hash,
    'label_key' => $label,
    'mention_key' => null,
    'millesime' => $millesime,
    'type_acheteur_key' => $type_acheteur,
    'destination_key' => $destination,
    'quantite' => $quantite,
    'contenance_key' => $contenant,
    'prix_unitaire' => $prix_ht,
    'no_accises_acheteur' => $acheteurAccises,
    'nom_acheteur' => $acheteurNom,
);

$form->bind($values);

$t->ok($form->isValid(), "Le formulaire est valide");

$form->save();

$t->is($dae->contenance_hl, 1, "Le ratio de la contenance en hl est de \"1\"");
$t->is($dae->conditionnement_key, 'HL', "L'unité de conditonnement en abrégé est \"hl\"");
$t->is($dae->conditionnement_libelle, 'Hectolitre', "L'unité de conditonnement est \"Hectolitre\"");
$t->is($dae->contenance_libelle, 0.0075, "Le libellé de la contenant est \"0.0075\"");
$t->is($dae->volume_hl, $quantite, "Le volume en hl est \"".$quantite."\"");
$t->is($dae->prix_hl, $prix_ht, "Le prix à hl est \"".$prix_ht."\"");

$t->is($dae->_id, $id, "L'id du doc est \"$id\"");

$dae = DAEClient::getInstance()->findLastByIdentifiantDate($identifiant, $date);

$t->is($dae->_id, $id, "Le dernier DAE est bien récupéré par rapport à la date du jour");

$export = new DAEExportCsvEdi(array($dae));
