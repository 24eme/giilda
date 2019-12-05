<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class GenerationFactureDRMPDF
 * @author mathurin
 */
class GenerationFactureDRMPDF extends GenerationPDF {

    protected $drmSource = null;
    protected $facture = null;
    protected $mailer = null;
    protected $routing = null;

    function __construct(Generation $g, $config = null, $options = null) {
        $this->mailer = $options['mailer'];
        $this->routing = $options['routing'];
        parent::__construct($g, $config, $options);
    }

    public function preGeneratePDF() {
       parent::preGeneratePDF();
       $drmid = $this->generation->arguments->drmid;
       $date_facturation =  $this->generation->arguments->date_facturation;

       $this->drmSource = DRMClient::getInstance()->find($drmid);
       $societe = $this->drmSource->getEtablissement()->getSociete();
       $parameters = $this->generation->arguments;
       $mouvementsBySoc = array($societe->identifiant => FactureClient::getInstance()->getFacturationForSociete($societe));
       $mouvementsBySoc = FactureClient::getInstance()->filterWithParameters($mouvementsBySoc,$parameters);

       if($mouvementsBySoc)
       {
           $this->generation = FactureClient::getInstance()->createFacturesBySoc($mouvementsBySoc,$date_facturation, $message_communication,$this->generation);
           $this->generation->save();
       }

    }

    public function postGeneratePDF(){
      $mailManager = new FactureEmailManager($this->getMailer(),$this->getRouting());

      if(count($this->generation->documents) > 1){
        throw new sfException("L'envoie d'email ne pourra avoir lieu, la generation possède plusieurs documents", 1);
      }elseif (count($this->generation->documents) < 1) {
          throw new sfException("pas de documents à envoyer", 1);
      }

      foreach ($this->generation->documents as $factureId) {
        $this->facture = FactureClient::getInstance()->find($factureId);
      }
      if(!$this->facture || !$this->drmSource){
        throw new sfException("L'envoie d'email ne pourra avoir lieu, la facture ou la drm source n'existe pas en base ", 1);

      }
      $mailManager->setFacture($this->facture);
      $mailManager->setDrmSource($this->drmSource);
      $mailManager->sendMailFacture();

    }

    protected function generatePDFForADocumentId($factureid) {
      $facture = FactureClient::getInstance()->find($factureid);
      if (!$facture) {
	       throw new sfException("Facture $factureid doesn't exist\n");
      }
      return new FactureLatex($facture, $this->config);
    }

    protected function getMailer(){
      return $this->mailer;
    }

    protected function getRouting(){
      return $this->routing;
    }

    protected function getDocumentName() {
      return "Factures";
    }

}
