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

    public function __construct($mailer) {
        $this->mailer = $mailer;
    }

    public function setVrac($vrac) {
        $this->vrac = $vrac;
    }
    
    
    public function sendMailAttenteSignature() {
        $createurObject = $this->vrac->getCreateurObject();
        $nonCreateursArr = $this->vrac->getNonCreateursArray();

        $resultEmailArr = array();
        
        $resultEmailArr[] = $createurObject->email;
        $responsableNom = $createurObject->nom;
        $responsableCourtier = ($createurObject->isCourtier() 
                && $this->vrac->exist('interlocuteur_commercial')
                && $this->vrac->interlocuteur_commercial
                && $this->vrac->interlocuteur_commercial->exist('nom')
                && $this->vrac->interlocuteur_commercial->nom)?
                ', dont l\'interlocuteur commercial est '.$this->vrac->interlocuteur_commercial->nom : '.' ;
        
        $mess = 
"Bonjour, 
    
Un contrat vient d'être initié par ".$responsableNom.", en voici un résumé : 

";
        $mess .= $this->enteteMessageVrac();
        $mess .= "  
 

Ce contrat attend votre signature. Pour le visualiser et le signer cliquez sur le lien suivant : ".$this->getUrlVisualisationContrat()."

 
Pour être valable, le contrat doit être signé par toutes les parties. Le PDF correspondant avec le numéro d'enregistrement INTERLOIRE vous sera alors envoyé par courriel.

 
Attention si le contrat n’est pas signé par toutes les parties dans les 5 jours à compter de sa date de création, il sera automatiquement supprimé.

 

Pour toutes questions, veuillez contacter ".$responsableNom.", responsable du contrat".$responsableCourtier."

 

———

L’application de télédéclaration des contrats d’INTERLOIRE
(ce message est adressé automatiquement, merci de ne pas répondre)

Rappel de votre identifiant : IDENTIFIANT";

        foreach ($nonCreateursArr as $id => $nonCreateur) {

            $message_replaced = str_replace('IDENTIFIANT', substr($nonCreateur->identifiant,0,6),$mess);
            
            $message = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $nonCreateur->email, 'Demande de signature pour un contrat (' . $createurObject->nom . ')', $message_replaced);
            try {
                $this->getMailer()->send($message);
            } catch (Exception $e) {
                $this->getUser()->setFlash('error', 'Erreur de configuration : Mail de confirmation non envoyé, veuillez contacter INTERLOIRE');
                return null;
            }
            $resultEmailArr[] = $nonCreateur->email;
        }
    }

    public function sendMailContratVise() {
        
        $soussignesArr = $this->vrac->getNonCreateursArray();
        $createur = $this->vrac->getCreateurObject();
        $soussignesArr[$createur->_id] = $createur;

        $responsableNom = $createur->nom;
        $responsableCourtier = ($createur->isCourtier() 
                && $this->vrac->exist('interlocuteur_commercial')
                && $this->vrac->interlocuteur_commercial
                && $this->vrac->interlocuteur_commercial->exist('nom')
                && $this->vrac->interlocuteur_commercial->nom)?
                ', dont l\'interlocuteur commercial est '.$this->vrac->interlocuteur_commercial->nom : '.' ;
        
        $resultEmailArr = array();
        $mess = $this->enteteMessageVrac();
        $mess .= "  

 
Ce contrat a été signé électroniquement par l'ensemble des soussignés. Pour le visualiser à tout moment vous pouvez cliquer sur le lien suivant : ".$this->getUrlVisualisationContrat()."

Ci joint, le PDF correspondant avec le numéro d'enregistrement Interloire.

Pour toutes questions, veuillez contacter ".$responsableNom.", responsable du contrat".$responsableCourtier."

———

L’application de télédéclaration des contrats d’Interloire

Rappel de votre identifiant : IDENTIFIANT";
       
        $pdf = new VracLatex($this->vrac);
        $pdfContent = $pdf->getPDFFileContents();        
        $pdfName = $pdf->getPublicFileName();

        
        foreach ($soussignesArr as $id => $soussigne) {

            $message_replaced = str_replace('IDENTIFIANT', substr($soussigne->identifiant,0,6),$mess);
            
            $message = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $soussigne->email, 'Validation du contrat n° '.$this->vrac->numero_archive.' (' . $createur->nom . ')', $message_replaced);
            
            $attachment = new Swift_Attachment($pdfContent, $pdfName, 'application/pdf');
            $message->attach($attachment);
            
            $attachment = new Swift_Attachment(file_get_contents(dirname(__FILE__).'/../../../../../web/data/reglementation_generale_des_transactions.pdf'), 'reglementation_generale_des_transactions.pdf', 'application/pdf');
            $message->attach($attachment);
	    
            
            
            $this->getMailer()->send($message);
            $resultEmailArr[] = $soussigne->email;
        }
        return $resultEmailArr;
    }

    public function sendMailAnnulation($automatique = false){
        
        $soussignesArr = $this->vrac->getNonCreateursArray();
        $createur = $this->vrac->getCreateurObject();
        $soussignesArr[$createur->_id] = $createur;

        $responsableNom = $createur->nom;
        $responsableCourtier = ($createur->isCourtier() 
                && $this->vrac->exist('interlocuteur_commercial')
                && $this->vrac->interlocuteur_commercial
                && $this->vrac->interlocuteur_commercial->exist('nom')
                && $this->vrac->interlocuteur_commercial->nom)?
                ', dont l\'interlocuteur commercial est '.$this->vrac->interlocuteur_commercial->nom : '.' ;
                
        $resultEmailArr = array();
        $mess = "Bonjour, 
            
Le contrat suivant a été annulé :

";
        if($automatique){
            $mess = "Bonjour, 
            
Le contrat suivant a été annulé automatiquement par le portail de télédeclaration :

";
        }
        
        $mess .= $this->enteteMessageVrac();        

$mess .= "
    

Il ne sera plus visible ni accessible sur le portail déclaratif d'Interloire.

Pour toutes questions, veuillez contacter ".$responsableNom.", responsable du contrat".$responsableCourtier."

———

L’application de télédéclaration des contrats d’Interloire

Rappel de votre identifiant : IDENTIFIANT";
               
        foreach ($soussignesArr as $id => $soussigne) {

            $message_replaced = str_replace('IDENTIFIANT', substr($soussigne->identifiant,0,6),$mess);
            
            $message = $this->getMailer()->compose(array(sfConfig::get('app_mail_from_email') => sfConfig::get('app_mail_from_name')), $soussigne->email, 'Annulation du contrat n° '.$this->vrac->numero_archive.' (' . $createur->nom . ')', $message_replaced);
            
            $this->getMailer()->send($message);
            $resultEmailArr[] = $soussigne->email;
        }
        return $resultEmailArr;
    }
        
    
    private function getUrlVisualisationContrat() {
        return sfConfig::get('app_domain').sfContext::getInstance()->getRouting()->generate('vrac_visualisation',array('numero_contrat' => $this->vrac->numero_contrat),false);
     }
    
    private function enteteMessageVrac() {
        
        sfProjectConfiguration::getActive()->loadHelpers("Vrac");
        if (!$this->vrac) {
            throw new sfException("Le contrat Vrac n'existe pas.");
        }
        $quantite = $this->vrac->getQuantite().' '.  showUnite($this->vrac);
        
        
$mess = 'Contrat ' . showTypeFromLabel($this->vrac->type_transaction,'',$this->vrac). ' de '.$this->vrac->produit_libelle.' ('.$quantite.')
    
Vendeur : ' . $this->vrac->vendeur->nom . '
Acheteur : ' . $this->vrac->acheteur->nom;
        if ($this->vrac->mandataire_exist) {
            $mess .= '
Courtier : ' . $this->vrac->mandataire->nom;
        }
        return $mess;
    }
    
    private function getMailer(){
        return $this->mailer;
    }
}
