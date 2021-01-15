<?php

class GenerationFactureMail extends GenerationAbstract {

    public function generateMailForADocumentId($id) {
        $facture = FactureClient::getInstance()->find($id);

        if(!$facture->getSociete()->getEmail()) {
            echo $facture->getSociete()->_id."\n";
            return;
        }

        $message = Swift_Message::newInstance()
         ->setFrom(sfConfig::get('app_mail_from_email'))
         ->setTo($facture->getSociete()->getEmail())
         ->setSubject("Facture Interpro")
         ->setBody("Bonjour,

Nouvelle facture de votre interprofession :

         ");

        return $message;
    }

    public function getMailer() {

        return sfContext::getInstance()->getMailer();
    }

    public function generate() {
        $factureAEnvoyer = array();
        $factureDejaEnvoye = $this->generation->documents->toArray();
        foreach($this->generation->getMasterGeneration()->documents as $factureId) {
            if(in_array($factureId, $factureDejaEnvoye)) {
                continue;
            }
            $mail = $this->generateMailForADocumentId($factureId);

            if(!$mail) {
                continue;
            }

            $sended = $this->getMailer()->send($mail);

            if(!$sended) {
                continue;
            }

            $this->generation->documents->add(null, $factureId);
            $this->generation->save();
        }
    }
}
