<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FactureEmailManager
 *
 * @author mathurin
 */
class FactureEmailManager {

    protected $mailer = null;
    protected $routing = null;
    protected $facture = null;
    protected $drmSource = null;

    public function __construct($mailer,$routing) {
        $this->mailer = $mailer;
        $this->routing = $routing;
        sfProjectConfiguration::getActive()->loadHelpers("Date");
        sfProjectConfiguration::getActive()->loadHelpers("Orthographe");
        sfProjectConfiguration::getActive()->loadHelpers("DRM");
        sfProjectConfiguration::getActive()->loadHelpers("Float");
    }

    public function setFacture($facture){
      $this->facture = $facture;
    }

    public function setDrmSource($drmSource){
      $this->drmSource = $drmSource;
    }

    public function sendMailFacture() {

        $etablissement = EtablissementClient::getInstance()->find($this->drmSource->identifiant);
        $contact = EtablissementClient::getInstance()->buildInfosContact($etablissement);
        $societe = $this->facture->getSociete();


        $mess = "
Madame, Monsieur

Veuillez touver ci-joint à ce mail la version PDF de votre facture InterLoire n° ".$this->facture->numero_interloire." d'un montant de ".sprintFloat($this->facture->total_ttc,"%01.02f")." € TTC

Cette facture est issue des mouvements des DRMs suivantes :

";
    foreach ($this->facture->getOrigines() as $docId) {
      if (strstr($docId, 'DRM') !== false) {
          $drmIdFormatted = DRMClient::getInstance()->getLibelleFromId($docId);
$mess.="    - ".$drmIdFormatted."
";
      }
    }
$mess.="

Cette facture a été automatiquement générée lors de la validation de la DRM ".getFrPeriodeElision($this->drmSource->periode).",

elle est disponible ici : ".sfConfig::get('app_teledeclaration_url').$this->routing->generate("facture_pdf",array("identifiant" => $this->facture->_id),false) ."

A tout moment vous pouvez consulter l'ensemble de vos factures sur votre espace InterLoire en cliquant sur l'onglet « Facture » présent en haut à droite.

Pour toutes questions, veuillez contacter:

 - le service Economie et Etudes d'InterLoire: " . $contact->nom . " - " . $contact->email . " - " . $contact->telephone . " .

--

L’application de télédéclaration des DRM ". sfConfig::get('app_teledeclaration_url') ." .";
        $pdf = new FactureLatex($this->facture);
        $pdfContent = $pdf->getPDFFileContents();
        $pdfName = $pdf->getPublicFileName();


        $subject = "La nouvelle facture n° ".$this->facture->numero_interloire." est disponible sur votre espace InterLoire";

        $message = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $etablissement->getEmailTeledeclaration(), $subject, $mess);

        $message->attach(new Swift_Attachment($pdfContent, $pdfName, 'application/pdf'));


        try {
            $this->getMailer()->send($message);
            $resultEmailArr[] = $etablissement->getEmailTeledeclaration();
            if ($this->drm->email_transmission) {
                $message_transmission = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $this->drm->email_transmission, $subject, $mess);
                $message_transmission->attach(new Swift_Attachment($pdfContent, $pdfName, 'application/pdf'));
                $this->getMailer()->send($message_transmission);
                $resultEmailArr[] = $this->drm->email_transmission;
            }
        } catch (Exception $e) {
            $this->getUser()->setFlash('error', 'Erreur de configuration : Mail de confirmation non envoyé, veuillez contacter INTERLOIRE');
            return null;
        }

        return $resultEmailArr;
    }

    private function getMailer() {
        return $this->mailer;
    }

    private function getUrlVisualisationFacture() {
        return sfContext::getInstance()->getRouting()->generate('drm_visualisation', $this->drm, true);
    }

}
