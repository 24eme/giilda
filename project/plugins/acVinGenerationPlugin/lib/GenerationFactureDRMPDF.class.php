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

    function __construct(Generation $g, $config = null, $options = null) {
        parent::__construct($g, $config, $options);
    }

    public function preGeneratePDF() {
       parent::preGeneratePDF();
       $drmid = $this->generation->arguments->drmid;
       $date_facturation =  $this->generation->arguments->date_facturation;
       $message_communication = $this->generation->arguments->message_communication;

       $drm = DRMClient::getInstance()->find($drmid);
       $societe = $drm->getEtablissement()->getSociete();

       $mouvementsBySoc = array($societe->identifiant => FactureClient::getInstance()->getFacturationForSociete($societe));
       $mouvementsBySoc = FactureClient::getInstance()->filterWithParameters($mouvementsBySoc,$parameters);

       if($mouvementsBySoc)
       {
           $this->generation = FactureClient::getInstance()->createFacturesBySoc($mouvementsBySoc,$date_facturation, $message_communication,$this->generation);
           $this->generation->save();
       }

    }

    public function postGeneratePDF(){
      var_dump("SEND MAIL!!!!"); exit;
    }

    protected function generatePDFForADocumentId($factureid) {
      $facture = FactureClient::getInstance()->find($factureid);
      if (!$facture) {
	       throw new sfException("Facture $factureid doesn't exist\n");
      }
      return new FactureLatex($facture, $this->config);
    }

    protected function getDocumentName() {
      return "Factures";
    }

}
