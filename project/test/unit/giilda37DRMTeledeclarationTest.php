<?php

require_once(dirname(__FILE__).'/../bootstrap/common.php');

sfContext::createInstance($configuration);

sfConfig::set('app_teledeclaration_contact_contrat', array());
sfConfig::set('app_mail_from_email', "test_from_mail@mail.org");
sfConfig::set('app_teledeclaration_interpro', "Interpro");

$t = new lime_test(21);

$compteviti = CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration');
$viti =  CompteTagsView::getInstance()->findOneCompteByTag('test', 'test_teledeclaration')->getEtablissement();
$periode = date('Ym');

$produits = array_keys(ConfigurationClient::getInstance()->getCurrent()->getProduits());
$produit_hash = array_shift($produits);

foreach(DRMClient::getInstance()->viewByIdentifiant($viti->identifiant) as $k => $v) {
  $drm = DRMClient::getInstance()->find($k);
  $drm->delete(false);
}

$drm = DRMClient::getInstance()->createDoc($viti->identifiant, $periode, true);

$drm->addProduit($produit_hash, 'details');
$drm->addProduit($produit_hash, 'detailsACQUITTE');

$details = $drm->getProduit($produit_hash, 'details');
$detailsAcquitte = $drm->getProduit($produit_hash, 'detailsACQUITTE');

$drm->addEditeur($compteviti);
$drm->save();

$t->is($drm->date_modification, date('Y-m-d'), 'La date de modification est enregistré');
$t->is($drm->editeurs[0]->compte, $compteviti->_id, "L'éditeur est enregistré");
$t->is($drm->editeurs[0]->date_modification, date('Y-m-d'), "La date d'édition est enregistré");

$t->comment("CRD");

$crd = $drm->crds->add(DRMClient::CRD_TYPE_SUSPENDU)->getOrAddCrdNode(DRMClient::DRM_CRD_CATEGORIE_TRANQ, DRMClient::DRM_CRD_VERT, 0.0075, "Bouteille 75 cl", 20);

$t->is($crd->getKey(), DRMClient::DRM_CRD_CATEGORIE_TRANQ."-".DRMClient::DRM_CRD_VERT."-750", "La clé est formaté correctement");
$t->is($crd->centilitrage, 0.0075, "Le centilitrage est en hl");

$t->comment("Annexe");

$sortieObservation = null;
foreach($drm->getConfig()->declaration->details->sorties as $sortie) {
    if(!$sortie->needDouaneObservation()) {
        continue;
    }
    $sortieObservation = $sortie->getKey();

    break;
}

$t->ok(!$details->exist('observations'), "Aucune observation de base");
$details->sorties->set($sortieObservation, 100);
$drm->update();
if(DRMConfiguration::getInstance()->isObservationsAuto()) {
    $t->is($details->observations, $details->sorties->getConfig()->get($sortieObservation)->getLibelleLong(), "Une observation a bien été ajouté automatiquement avec le libellé du mouvement");
} else {
    $t->is($details->observations, "","Le champs observation a bien été ajouté avec une chaine de caractère vide");
}

$details->sorties->set($sortieObservation, null);
$drm->update();

$t->ok(!$details->exist('observations'), "Si le volume est supprimé le champ observation aussi");

$annexesForm = new DRMAnnexesForm($drm);

$values = array('_revision' => $drm->_rev);

foreach($annexesForm['releve_non_apurement'] as $formItemKey => $formItem) {
    $values['releve_non_apurement'][$formItemKey] = array('numero_document' => '123456', 'date_emission' => date('d/m/Y'), 'numero_accise' => 'FR123456E1234');
}

$annexesForm->bind($values);

$t->ok($annexesForm->isValid(), "Le formulaire est valide");
$annexesForm->save();

$t->is($drm->get('releve_non_apurement/123456')->_get('date_emission'), date('Y-m-d'), "La date est enregistré au format machine");
$t->is($drm->get('releve_non_apurement/123456')->get('date_emission'), date('d/m/Y'), "La sortie de la date est en fr");

$t->comment("Validation");

if($details->entrees->getConfig()->get('retourmarchandisetaxees')->hasDetails()) {
    $detail = DRMESDetailReintegration::freeInstance($drm);
    $detail->volume = 100;
    $detail->date = date('Y-m-d');
    $details->get('entrees/retourmarchandisetaxees_details')->addDetail($detail);
} else {
    $details->entrees->retourmarchandisetaxees = 100;
}
$drm->update();
if(DRMConfiguration::getInstance()->isObservationsAuto()) {
    $details->observations = null;
}

$validation = new DRMValidation($drm, true);

$t->ok($validation->hasErreur('observations'), "Un point bloquant obligeant la saisie des observations est levé");
if($details->entrees->getConfig()->get('retourmarchandisetaxees')->hasDetails()) {
    $t->ok(!$validation->hasErreur('replacement_date'), "Le point bloquant obligeant la saisie de la date de replacement n'est pas levé");
} else {
    $t->ok($validation->hasErreur('replacement_date'), "Un point bloquant obligeant la saisie de la date de replacement est levé");
}

$details->entrees->retourmarchandisetaxees = null;
$details->sorties->manquant = null;
$detailsAcquitte->sorties->autre = 100;
$drm->update();

$validation = new DRMValidation($drm, true);

$t->ok(!$validation->hasErreur('observations'), "Le point bloquant obligeant la saisie des observations n'est pas levé si le produit est acquitte");

$drm->remove('date_modification');
$drm->validate();
$drm->save();
$t->is($drm->date_modification, date('Y-m-d'), "La date de modification est enregistré après la validation d'une DRM");

$drm->devalidate();
$drm->save();
$t->is($drm->valide->date_saisie, null, "La date de saisie n'est plus enregistré");


$t->comment("Mail");

$mailManager = new DRMEmailManager(sfContext::getInstance()->getMailer());
$drm->email_transmission = "email_transmission@mail.org";
$mailManager->setDRM($drm);

$messages = $mailManager->composeMailValidation(true);

$t->is(count($messages), 2, "L'envoi compte 2 messages");

@mkdir(sfConfig::get('sf_test_dir')."/output");
file_put_contents(sfConfig::get('sf_test_dir')."/output/email_drm_validation_teldeclaration_douane_transmission_douane.eml", $messages[0]);
$t->ok(strpos($messages[0], " test_from_mail@ma") !== false, "Les infos de contact dans le mail sont bonnes");

$t->ok($messages[0] instanceof Swift_Mime_SimpleMessage, "Mail généré : ".sfConfig::get('sf_test_dir')."/output/email_drm_validation_teldeclaration_douane_transmission_douane.eml");

$t->is($mailManager->sendMailValidation(false), array($viti->getEmailTeledeclaration(), $drm->email_transmission), "Les retours des email sont ok");

$messages = $mailManager->composeMailValidation(false);

@mkdir(sfConfig::get('sf_test_dir')."/output");
file_put_contents(sfConfig::get('sf_test_dir')."/output/email_drm_validation_teldeclaration.eml", $messages[0]);

$t->ok($messages[0] instanceof Swift_Mime_SimpleMessage, "Mail généré : ".sfConfig::get('sf_test_dir')."/output/email_drm_validation_teldeclaration.eml");
