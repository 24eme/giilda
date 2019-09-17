<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SocieteSepaEmailManager
 *
 * @author mathurin
 */
class SocieteSepaEmailManager {

    protected $mailer = null;
    protected $user = null;
    protected $societe = null;

    public function __construct($mailer,$user) {
        $this->mailer = $mailer;
        $this->user = $user;
        sfProjectConfiguration::getActive()->loadHelpers("Date");
        sfProjectConfiguration::getActive()->loadHelpers("Orthographe");
        sfProjectConfiguration::getActive()->loadHelpers("DRM");
        sfProjectConfiguration::getActive()->loadHelpers("Float");
    }

    public function setSociete($societe){
      $this->societe = $societe;
    }

    public function sendMailSepaActivate() {
      $etablissement = $this->societe->getEtablissementPrincipal();
      $mess = "
Bonjour,

Merci d’avoir opté pour le prélèvement automatique. Nous vous annonçons que votre mandat SEPA, désormais lié à votre compte, a bien été validé par nos soins.

Votre prochain prélèvement est prévu le ".Date::francizeDate($this->societe->getSepaDateEffectif())."

A compter d'aujourd’hui, les prochaines factures générées dans votre compte mentionneront les échéances de prélèvement.

Attention pour les factures déjà émises dont l'échéance est antérieure au ".Date::francizeDate($this->societe->getSepaDateEffectif()).", merci de bien vouloir procéder à leur règlement selon votre moyen de paiement habituel.



Le service Recouvrement

--

Pour toute demande, n'hésitez pas à contacter notre service support :

- par téléphone au ".sfConfig::get('app_facture_telephone_service_facturation')." de 8h30 à 12h30 et de 13h30 à 16h30.";

        $subject = "Activation de votre mandat de prélèvement";

        $message = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $this->societe->getEmail(), $subject, $mess);

        try {
            $this->getMailer()->send($message);
            $resultEmailArr[] = $this->societe->getEmail();

        } catch (Exception $e) {
            $this->getUser()->setFlash('error', 'Erreur de configuration : Mail de confirmation non envoyé, veuillez contacter INTERLOIRE');
            return null;
        }

        return $resultEmailArr;
    }

    private function getMailer() {
        return $this->mailer;
    }

}
