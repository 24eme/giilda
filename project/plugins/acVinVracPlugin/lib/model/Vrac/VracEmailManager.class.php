<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VracEmailManager
 *
 * @author mathurin
 */
class VracEmailManager {

    protected $vrac = null;
    protected $mailer = null;
    protected $user = null;

    public function __construct($mailer, $user) {
        $this->mailer = $mailer;
        $this->user = $user;
    }

    public function setVrac($vrac) {
        $this->vrac = $vrac;
    }

    public function sendMailAttenteSignature() {
        $createurObject = $this->vrac->getCreateurObject();
        $nonCreateursArr = $this->vrac->getNonCreateursArray();

        $resultEmailArr = array();

        $emailCreateur = $this->getUser()->getCompte()->getEmail();
        if(!$emailCreateur){
          $emailCreateur = $createurObject->getEmailTeledeclaration();
        }

        $resultEmailArr[] = $emailCreateur;
        $responsableNom = $createurObject->nom;

        $mess = $this->enteteMessageVrac();
        $mess .= "


Ce contrat attend votre signature. Pour le visualiser et le signer cliquez sur le lien suivant : " . $this->getUrlVisualisationContrat() . " .

Pour être valable, le contrat doit être signé par toutes les parties et enregistré par InterLoire. Un fichier en format PDF avec le numéro d’enregistrement d’InterLoire vous sera alors envoyé par courriel.

Attention : si le contrat n’est pas signé par toutes les parties dans les 5 jours à compter de sa date de création, il sera automatiquement supprimé.

Pour toutes questions, veuillez contacter " . $responsableNom . ", l'initiateur du contrat.

——

L’application de télédéclaration des contrats d’InterLoire

Rappel de votre identifiant : IDENTIFIANT";

        foreach ($nonCreateursArr as $id => $nonCreateur) {

            $message_replaced = str_replace('IDENTIFIANT', substr($nonCreateur->identifiant, 0, 6), $mess);

            $subject = "Demande de signature d'un contrat (" . $createurObject->nom . " - créé le " . $this->getDateSaisieContratFormatted() . ")";

            $message = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $nonCreateur->getEmailTeledeclaration(), $subject, $message_replaced);
            try {
                $this->getMailer()->send($message);

            } catch (Exception $e) {
                $this->getUser()->setFlash('error', 'Erreur de configuration : Mail de confirmation non envoyé, veuillez contacter INTERLOIRE');
                return null;
            }
            $resultEmailArr[] = $nonCreateur->getEmailTeledeclaration();
        }
    }

    public function sendMailContratVise() {

        $resultEmailArr = array();
        $soussignesArr = $this->vrac->getNonCreateursArray();
        $createur = $this->vrac->getCreateurObject();
        $soussignesArr[$createur->_id] = $createur;

        $responsableNom = $createur->nom;

        $mess = $this->enteteMessageVrac();
        $mess .= "

Ce contrat a été signé électroniquement par l’ensemble des soussignés et enregistré par InterLoire.

Vous pouvez le visualiser à tout moment en cliquant sur le lien suivant : " . $this->getUrlVisualisationContrat() . " .

Il est également joint à ce mail en format PDF accompagné de la réglementation générale des transactions.

Pour toutes questions, veuillez contacter " . $responsableNom . ", l’initiateur du contrat.

--

L’application de télédéclaration des contrats d’InterLoire

Rappel de votre identifiant : IDENTIFIANT";

        $pdf = new VracLatex($this->vrac);
        $pdfContent = $pdf->getPDFFileContents();
        $pdfName = $pdf->getPublicFileName();


        foreach ($soussignesArr as $id => $soussigne) {

            $message_replaced = str_replace('IDENTIFIANT', substr($soussigne->identifiant, 0, 6), $mess);

            $subject = "Validation du contrat n°" . $this->vrac->numero_archive . " (" . $createur->nom . " - créé le " . $this->getDateSaisieContratFormatted() . ")";

            $message = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $soussigne->getEmailTeledeclaration(), $subject, $message_replaced);

            $attachment = new Swift_Attachment($pdfContent, $pdfName, 'application/pdf');
            $message->attach($attachment);

            $attachment = new Swift_Attachment(file_get_contents(dirname(__FILE__) . '/../../../../../web/data/reglementation_generale_des_transactions.pdf'), 'reglementation_generale_des_transactions.pdf', 'application/pdf');
            $message->attach($attachment);

            $this->getMailer()->send($message);
            $resultEmailArr[] = $soussigne->getEmailTeledeclaration();
        }
        return $resultEmailArr;
    }

    public function sendMailAnnulation($automatique = false) {

        $soussignesArr = array();
        if ($this->vrac->valide->statut != VracClient::STATUS_CONTRAT_BROUILLON) {
            $soussignesArr = $this->vrac->getNonCreateursArray();
        }
        $createur = $this->vrac->getCreateurObject();
        $soussignesArr[$createur->_id] = $createur;
        $responsableNom = $createur->nom;

        $resultEmailArr = array();

        $mess = $this->enteteMessageVrac();
        $mess .= "


";
        if ($automatique) {
            if ($this->vrac->valide->statut == VracClient::STATUS_CONTRAT_BROUILLON) {
                $mess.= "Ce contrat a été annulé automatiquement par le portail de télédéclaration car il est en brouillon depuis maintenant plus de 10 jours.";
            } else {
                $mess.= "Ce contrat a été annulé automatiquement par le portail de télédéclaration car il est en attente de signature depuis maintenant plus de 5 jours.";
            }
        } else {
            $mess.= "Le contrat suivant a été annulé par son responsable.";
        }

        $mess.= "

Il ne sera plus visible ni accessible sur le portail déclaratif d'Interloire.

Pour toutes questions, veuillez contacter " . $responsableNom . ", responsable du contrat.

——

L’application de télédéclaration des contrats d’InterLoire

Rappel de votre identifiant : IDENTIFIANT";

        foreach ($soussignesArr as $id => $soussigne) {

            $message_replaced = str_replace('IDENTIFIANT', substr($soussigne->identifiant, 0, 6), $mess);

            $subject = ($this->vrac->valide->date_saisie) ?
                    "Annulation d'un contrat (" . $createur->nom . " - créé le " . $this->getDateSaisieContratFormatted() . ")" :
                    "Annulation d'un contrat brouillon (" . $createur->nom . ")";

            $message = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $soussigne->getEmailTeledeclaration(), $subject, $message_replaced);

            $this->getMailer()->send($message);
            $resultEmailArr[] = $soussigne->getEmailTeledeclaration();
        }
        return $resultEmailArr;
    }

    public function sendMailRappel() {

        $soussignesArr = $this->vrac->getNonCreateursArray();
        $createur = $this->vrac->getCreateurObject();
        $responsableNom = $createur->nom;

        $emailsArr = array();
        foreach ($soussignesArr as $identifiant => $soussigne) {
            if (($identifiant == $this->vrac->vendeur_identifiant) && !$this->vrac->isSigneVendeur()) {
                $emailsArr[$identifiant] = $soussigne;
            }
            if (($identifiant == $this->vrac->acheteur_identifiant) && !$this->vrac->isSigneAcheteur()) {
                $emailsArr[$identifiant] = $soussigne;
            }
        }

        $mess = $this->enteteMessageVrac();
        $mess .= "


";
                $mess.= "Ce contrat est en attente de signature sur le portail de télédeclaration depuis maintenant plus de 3 jours.";

        $mess.= "
Il vous reste 2 jours pour lui apporter votre signature. A défaut, il sera automatiquement annulé.

Pour le visualiser et le signer cliquez sur le lien suivant : " . $this->getUrlVisualisationContrat() . " .

Pour toutes questions, veuillez contacter " . $responsableNom . ", responsable du contrat.

——

L’application de télédéclaration des contrats d’InterLoire

Rappel de votre identifiant : IDENTIFIANT";

        foreach ($emailsArr as $id => $soussigne) {

            $message_replaced = str_replace('IDENTIFIANT', substr($soussigne->identifiant, 0, 6), $mess);

            $subject = "Relance de signature d'un contrat (" . $createur->nom . " - créé le " . $this->getDateSaisieContratFormatted() . ")" ;

            $message = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $soussigne->getEmailTeledeclaration(), $subject, $message_replaced);

            $this->getMailer()->send($message);
            $resultEmailArr[] = $soussigne->getEmailTeledeclaration();
        }
        return $emailsArr;
    }

    private function getUrlVisualisationContrat() {
        return sfContext::getInstance()->getRouting()->generate('vrac_visualisation', array('numero_contrat' => $this->vrac->numero_contrat), true);
    }

    private function enteteMessageVrac() {

        sfProjectConfiguration::getActive()->loadHelpers("Vrac");
        if (!$this->vrac) {
            throw new sfException("Le contrat Vrac n'existe pas.");
        }

        $mess = 'Contrat ' . showTypeFromLabel($this->vrac->type_transaction, '', $this->vrac) . ' du ' . $this->getDateSaisieContratFormatted();
        $mess .= ($this->vrac->isVise()) ? ' (Numéro d’enregistrement : ' . $this->vrac->numero_archive . ')' : '';
        $mess .= '


Vendeur : ' . $this->vrac->vendeur->nom . '
Acheteur : ' . $this->vrac->acheteur->nom;
        if ($this->vrac->mandataire_exist) {

            $responsableCourtier = ($this->vrac->getCreateurObject()->isCourtier() && $this->vrac->exist('interlocuteur_commercial') && $this->vrac->interlocuteur_commercial && $this->vrac->interlocuteur_commercial->exist('nom') && $this->vrac->interlocuteur_commercial->nom) ? $this->vrac->interlocuteur_commercial->nom : null;


            $mess .= '
Courtier : ' . $this->vrac->mandataire->nom;
            if ($responsableCourtier) {
                $mess .= ' (votre interlocuteur : ' . $responsableCourtier . ')';
            }
        }
        return $mess;
    }

    private function getMailer() {
        return $this->mailer;
    }

    private function getUser() {
        return $this->user;
    }

    protected function getDateSaisieContratFormatted() {
        return date("d/m/Y", strtotime($this->vrac->valide->date_saisie));
    }

}
