<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');
sfContext::createInstance($configuration);

if($application == "civa") {
    $t = new lime_test(0);
    exit(0);
}

$conf = ConfigurationClient::getInstance()->getCurrent();

$hasCVONegociant = false;
foreach ($conf->declaration->filter('details') as $configDetails) {
    foreach ($configDetails as $details) {
        foreach($conf->declaration->details->getDetailsSorted($details) as $detail) {
            if($detail->isFacturableInverseNegociant()) {
                $hasCVONegociant = true;
            }
        }
    }
}

$t = new lime_test(40);

$t->comment("Configuration");

$t->is(FactureClient::getInstance()->getTauxTva('2013-12-31'), 19.6, "Taux de tva avant 2014");
$t->is(FactureClient::getInstance()->getTauxTva('2014-01-01'), 20, "Taux de tva à partir de 2014");

$t->comment("Création d'une facture à partir des DRM pour une société");

$paramFacturation =  array(
    "modele" => "DRM",
    "date_facturation" => date('Y').'-08-01',
    "date_mouvement" => null,
    "type_document" => GenerationClient::TYPE_DOCUMENT_FACTURES,
    "message_communication" => null,
    "seuil" => null,
);

$societeViti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getSociete();
$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_viti')->getEtablissement();
$t->comment("Suppression des DRM précédentes pour ".$viti->identifiant);
foreach(DRMClient::getInstance()->viewByIdentifiant($viti->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
}

$t->comment("Création de la DRM");
$produits = array_keys($conf->getProduits());
$produit_hash = array_shift($produits);
$periode = date('Ym');
$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);
$details = $drm->addProduit($produit_hash, 'details');
$details->stocks_debut->initial = 1000;
$details->sorties->ventefrancecrd = 200;
$drm->update();
$drm->save();
$drm->validate();
$drm->save();

$t->is($drm->_get('taux_tva'), FactureClient::getInstance()->getTauxTva($drm->getDate()) / 100, "La taux de tva ".(FactureClient::getInstance()->getTauxTva($drm->getDate()) / 100)." est stocké dans la DRM");

$t->comment("Recherche des mouvements (non facturable)");
$mouvementsFactureMasse = FactureClient::getInstance()->getMouvementsNonFacturesBySoc(FactureClient::getInstance()->getMouvementsForMasse(null));
$t->comment("Recherche des mouvements (> à 999999)");
$mouvementsFactureMasse = FactureClient::getInstance()->filterWithParameters($mouvementsFactureMasse, array_merge($paramFacturation, array('seuil' => 999999)));

$t->is(count($mouvementsFactureMasse), 0, "Avec un seuil à 99999 aucune société à facturer");

$mouvementsFactureMasse = FactureClient::getInstance()->getMouvementsNonFacturesBySoc(FactureClient::getInstance()->getMouvementsForMasse(null));
$mouvementsFactureMasse = FactureClient::getInstance()->filterWithParameters($mouvementsFactureMasse, $paramFacturation);

$mouvementsFacture = array($societeViti->identifiant => FactureClient::getInstance()->getFacturationForSociete($societeViti));
$mouvementsFacture = FactureClient::getInstance()->filterWithParameters($mouvementsFacture, $paramFacturation);

$t->is(json_encode($mouvementsFactureMasse[$societeViti->identifiant]), json_encode($mouvementsFacture[$societeViti->identifiant]), "La méthode récupération massive des mouvements est ok");

$prixHt = 0.0;
$nbmvt = 0;
if(isset($mouvementsFacture[$societeViti->identifiant])) {
foreach ($mouvementsFacture[$societeViti->identifiant] as $mvt) {
  $prixHt += $mvt->prix_ht;
  $nbmvt++;
}
}
$prixHt = $prixHt;
$prixTaxe = $prixHt * 0.2;
$prixTTC = $prixHt+$prixTaxe;

$facture = FactureClient::getInstance()->createAndSaveFacturesBySociete($societeViti, $paramFacturation);

$facture->save();
$t->ok($facture, "La facture est créée");

$t->is($facture->identifiant, $societeViti->identifiant, "La facture appartient à la société demandé");

$t->ok($facture->emetteur->adresse, "L'adresse de l'emetteur est rempli");

$t->is($facture->total_ht, $prixHt, "Le total HT est de ".$prixHt." €");
$t->is($facture->total_ttc, $prixTTC, "Le total TTC est de ".$prixTTC."  €");
$t->is($facture->total_taxe, $prixTaxe, "Le total de taxe est de ".$prixTaxe."  €");

$nbLignes = 0;
$doublons = 0;
foreach($facture->lignes as $lignes) {
    $libellesUnique = array();
    foreach($lignes->details as $ligne) {
        $nbLignes++;
        if(array_key_exists($ligne->libelle . $ligne->origine_type, $libellesUnique)) {
            $doublons++;
        }
        $libellesUnique[$ligne->libelle . $ligne->origine_type] = 1;
    }
}

$t->ok(!$doublons, "Aucune ligne (par libellé) en doublon");

$t->is($nbLignes, $nbmvt, "La facture à ".$nbmvt." lignes");

if($application == "ivbd") {
    $t->is($facture->campagne, (date('Y')+1)."", "La campagne est de la facture est sur l'année viticole");
} else {
    $t->is($facture->campagne, date('Y'), "La campagne est l'année courante");
}

$t->ok(preg_match("/^[0-9]{5}$/", $facture->numero_archive), "Le numéro d'archive a été créé et est composé de 5 chiffres");

if($application == "ivbd") {
    $t->is($facture->numero_piece_comptable, "1".substr($facture->campagne, -2).$facture->numero_archive, "Le numéro de facture est bien formé");
} elseif($application == "ivso") {
    $t->is($facture->numero_piece_comptable, "C".substr($facture->campagne, -2).$facture->numero_archive, "Le numéro de facture est bien formé");
} else {
    $t->is($facture->numero_piece_comptable, substr($facture->campagne, -2).$facture->numero_archive, "Le numéro de facture est bien formé");
}

$t->is($facture->versement_comptable, 0, "La facture n'est pas versé comptablement");

$generation = FactureClient::getInstance()->createGenerationForOneFacture($facture);

$t->ok($generation, "La génération est créée");

$t->comment("Test d'une nouvelle facturation sur la société pour s'assurer qu'aucune facture ne sera créée");

$t->ok(!FactureClient::getInstance()->createAndSaveFacturesBySociete($societeViti, $paramFacturation), "La facture n'a pas été créée");

$t->comment("Création d'un avoir à partir de la facture");

$t->ok($facture->isRedressable(), "La facture est redressable");

$prixHt = $prixHt * -1;
$prixTaxe = $prixHt * 0.2;
$prixTTC = $prixHt+$prixTaxe;

$avoir = FactureClient::getInstance()->defactureCreateAvoirAndSaveThem($facture);
$t->ok($avoir, "L'avoir est créé");
$t->is($avoir->identifiant, $societeViti->identifiant, "L'avoir appartient à la même société que la facture");
$t->ok($avoir->isAvoir(), "L'avoir est marqué comme un avoir");
$t->is($avoir->total_ht, $prixHt, "Le total TTC est de ".$prixHt." €");
$t->is($avoir->total_ttc, $prixTTC, "Le total TTC est de ".$prixTTC." €");
$t->is($avoir->total_taxe, $prixTaxe, "Le total TTC est de ".$prixTaxe." €");
$t->is($avoir->date_facturation, date('Y-m-d'), "La date de l'avoir est celle d'aujourd'hui");
$t->ok(!$avoir->isRedressable(), "L'avoir n'est pas redressable");

$t->is($facture->avoir, $avoir->_id, "L'avoir est conservé dans la facture");
$t->ok($facture->isRedressee(), "La facture est au statut redressé");
$t->ok(!$facture->isRedressable(), "La facture n'est pas redressable");

if($application == 'ivso' && false) {
  $md5sumAttendu = "d65f0fd40ecbecd6c5b12b6931bda08f";
  $facture->campagne = "2017";
  $facture->numero_piece_comptable = "C1700006";
  $facture->numero_archive = "00006";
  $facture->date_facturation = '2017-08-01';
  $facture->date_echeance = '2017-08-31';
  $facture->code_comptable_client = '2743';
  $facture->identifiant = '002743';
  $facture->numero_adherent = '002743';

  foreach($facture->getLignes() as $key => $ligne){
     $ligne->libelle = "DRM de mars 2017";
  }
  $factureLatex = new FactureLatex($facture);
  $latexFileName = $factureLatex->getLatexFile();
  $md5sum = md5_file($latexFileName);
  $t->is($md5sumAttendu, $md5sum, "Le md5 du pdf doit être le suivant ".$md5sumAttendu." path = ".$latexFileName);
}else{
  $t->is(1, 1, "Pas de test sur la structure latex");
}

$t->comment("Facturation createOrigine");

$mouvement = new stdClass();
$mouvement->origine = FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_DRM;
$mouvement->vrac_destinataire = "Test destinaire";
$mouvement->detail_libelle = "000001";
$mouvement->vrac_destinataire = date('Ymd')."000001";
$mouvement->vrac_numero = "VRAC-2018000099999";
$mouvement->matiere = FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE;
$mouvement->quantite = 100;

if($application == "ivso") {
    $origine = (FactureConfiguration::getInstance()->isPdfProduitFirst()) ? "Contrat ".$mouvement->vrac_destinataire : "Contrat n° 99999 (".$mouvement->vrac_destinataire.") ";
} else {
    $origine = (FactureConfiguration::getInstance()->isPdfProduitFirst()) ? "Contrat ".$mouvement->vrac_destinataire : "Contrat n° ".$mouvement->detail_libelle." (".$mouvement->vrac_destinataire.") ";
}

$t->is(MouvementfactureFacturationView::getInstance()->createOrigine(SocieteClient::TYPE_OPERATEUR, $mouvement), $origine, "Le calcule de l'origine est correct");

$t->comment("Facturation d'une DRM négo");

$societeNego = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_region')->getSociete();
$nego = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_region')->getEtablissement();
$societeNego2 = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_region_2')->getSociete();
$nego2 = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_nego_region_2')->getEtablissement();

foreach(DRMClient::getInstance()->viewByIdentifiant($nego->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
}

$vrac = new Vrac();
$vrac->initCreateur($nego2->getIdentifiant());
$vrac->teledeclare = true;
$vrac->acheteur_identifiant = $nego2->getIdentifiant();
$vrac->vendeur_identifiant = $nego->getIdentifiant();
$produits = array_keys(ConfigurationClient::getInstance()->getCurrent()->getProduits());
$produit = array_shift($produits);
$vrac->setProduit($produit);
$vrac->type_transaction = VracClient::TYPE_TRANSACTION_VIN_VRAC;
$vrac->jus_quantite = 100;
$vrac->setPrixUnitaire(70);
$vrac->save();

$drm = DRMClient::getInstance()->createDoc($nego->identifiant, $periode, true);
$drm->save();

$details = $drm->addProduit($produit, 'details');
$details->entrees->recolte = 100;
$details->sorties->manquant = 1;
$details->sorties->destructionperte = 2;
$details->sorties->transfertsrecolte = 10;

$prixHt = round($details->entrees->recolte / 12 * 2, 2) * $details->getCVOTaux() - round($details->sorties->manquant * $details->getCVOTaux(), 2) - round($details->sorties->destructionperte * $details->getCVOTaux(), 2);
if ($details->sorties->exist('vrac_details')) {
    $vrac_detail = DRMESDetailVrac::freeInstance($drm);
    $vrac_detail->identifiant = $vrac->_id;
    $vrac_detail->volume = 1;
    $vrac_detail = $details->sorties->vrac_details->addDetail($vrac_detail);
    $prixHt += - round($vrac_detail->volume * ($details->getCVOTaux() / 2), 2);
}elseif ($details->sorties->exist('creationvrac_details')) {
    $vrac_detail = DRMESDetailCreationVrac::freeInstance($drm);
    $vrac_detail->acheteur = $nego2->identifiant;
    $vrac_detail->type_contrat = VracClient::TYPE_TRANSACTION_VIN_VRAC;
    $vrac_detail->volume = 1;
    $vrac_detail = $details->sorties->creationvrac_details->addDetail($vrac_detail);
    $prixHt += - round($vrac_detail->volume * ($details->getCVOTaux() / 2), 2);
}else{
    $details->sorties->ventefrancecrd = 1;
    $prixHt += - round($details->sorties->ventefrancecrd * ($details->getCVOTaux() / 2), 2);
}

$details_2 = $drm->addProduit($produit, 'details', 'BIO');
$details_2->entrees->transfertsrecolte = 10;

$drm->update();
$drm->validate();
$drm->save();

$dateFacturation = new DateTime(preg_replace("/([0-9]{4})([0-9]{2})/", '\1-\2-31', $periode));
$paramFacturation["date_mouvement"] = $dateFacturation->modify("+ 1 month")->format('Y-m-d');
$facture = FactureClient::getInstance()->createAndSaveFacturesBySociete($societeNego, $paramFacturation);

if($hasCVONegociant) {
    $t->is($drm->mouvements->get($nego2->getIdentifiant())->getFirst()->facturable, 1, 'Mouvement du negociant est facturable');
    $t->ok($facture, "La facture est créée");
    $t->is($facture->total_ht, round($prixHt, 2), "Le total HT est de ".$prixHt." €");
    $t->is($facture->lignes->get($drm->_id)->libelle,DRMClient::getInstance()->getLibelleFromId($drm->_id)." (sur la base des volumes produits)", 'Libellé de la catégorie');
    $t->is(count($facture->lignes->get($drm->_id)->details->toArray(true, false)), 5, "La facture à 5 lignes");
} else {
    $t->pass("Aucun test");
    $t->ok(!$facture, "La facture n'est pas créée");
    $t->pass("Rien à facturer");
    $t->pass("Aucun test");
    $t->pass("Aucun test");
}

$facture_negoce2 = FactureClient::getInstance()->createAndSaveFacturesBySociete($societeNego2, $paramFacturation);
if($hasCVONegociant) {
    $t->ok($facture_negoce2, "La facture de l'acheteur est créée");
    $t->is($facture_negoce2->total_ht, round($vrac_detail->volume * ($details->getCVOTaux() / 2), 2), "Le total HT est correct");
} else {
    $t->ok(!$facture_negoce2, "La facture n'est pas créée");
    $t->pass("Rien à facturer");
}
