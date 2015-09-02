<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMEmailManager
 *
 * @author mathurin
 */
class DRMEmailManager {

    protected $drm = null;
    protected $mailer = null;

    public function __construct($mailer) {
        $this->mailer = $mailer;
        sfProjectConfiguration::getActive()->loadHelpers("Date");
        sfProjectConfiguration::getActive()->loadHelpers("Orthographe");
        sfProjectConfiguration::getActive()->loadHelpers("DRM");
    }

    public function setDRM($drm) {
        $this->drm = $drm;
    }

    public function sendMailCoordonneesOperateurChanged($type, $diff) {
        $typeInfos = null;
        $typeLibelle = null;
        $mailsInterloire = 'test@test.fr';
        switch ($type) {
            case CompteClient::TYPE_COMPTE_ETABLISSEMENT:
                $typeInfos = $this->drm->getDeclarant();
                $typeLibelle = "l'etablissement";
                $identification = $typeInfos->nom . " (" . $this->drm->identifiant . ")";
                break;

            case CompteClient::TYPE_COMPTE_SOCIETE:
                $typeInfos = $this->drm->getSociete();
                $typeLibelle = 'la société';
                $identification = $typeInfos->raison_sociale . " (" . substr(0, 6, $this->drm->identifiant) . ")";
                break;
        }

        $mess = "Les coordonnée de " . $typeLibelle . " " . $identification . " ont été modifiés.
Voici les différentes modifications enregistrées :

";
        foreach ($diff as $key => $value) {
            $mess .= $key . " : " . $value . "
";
        }
        $mess .= "

——

L’application de télédéclaration des contrats d’InterLoire";


        $subject = "Changement de coordonnées de la société " . $typeLibelle . " (" . $identification . ")";

        $message = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $mailsInterloire, $subject, $mess);
        try {
            $this->getMailer()->send($message);
        } catch (Exception $e) {
            $this->getUser()->setFlash('error', 'Erreur de configuration : Mail de confirmation non envoyé, veuillez contacter INTERLOIRE');
            return null;
        }
        return true;
    }

    public function sendMailValidation() {

        $etablissement = EtablissementClient::getInstance()->find($this->drm->identifiant);
        $contact = EtablissementClient::getInstance()->buildInfosContact($etablissement);


        $mess = "  

La DRM " . getFrPeriodeElision($this->drm->periode) . " de " . $etablissement->nom . " a été validée électroniquement sur le portail de télédeclaration d'InterLoire.

La version PDF de cette DRM est également disponible en pièce jointe dans ce mail.

Pour toutes questions, veuillez contacter " . $contact->nom . " - " . $contact->email . " - " . $contact->telephone . " .
    
--

L’application de télédéclaration des DRM d’InterLoire

Rappel de votre identifiant : " . substr($this->drm->identifiant, 0, 6) . "
    
Vous pouvez la visualiser à tout moment en cliquant sur le lien suivant : " . $this->getUrlVisualisationDrm() . " .";

        $pdf = new DRMLatex($this->drm);
        $pdfContent = $pdf->getPDFFileContents();
        $pdfName = $pdf->getPublicFileName();

        $subject = "Validation de votre DRM " . getFrPeriodeElision($this->drm->periode) . " créée le " . $this->getDateSaisieDrmFormatted() . " .";

        $message = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $etablissement->getEmailTeledeclaration(), $subject, $mess);

        $message->attach(new Swift_Attachment($pdfContent, $pdfName, 'application/pdf'));

//            $attachment = new Swift_Attachment(file_get_contents(dirname(__FILE__) . '/../../../../../web/data/reglementation_generale_des_transactions.pdf'), 'reglementation_generale_des_transactions.pdf', 'application/pdf');
//            $message->attach($attachment);

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

    private function getUrlVisualisationDrm() {
        return sfContext::getInstance()->getRouting()->generate('drm_visualisation', $this->drm, true);
    }

    protected function getDateSaisieDrmFormatted() {
        return date("d/m/Y", strtotime($this->drm->valide->date_saisie));
    }

}
