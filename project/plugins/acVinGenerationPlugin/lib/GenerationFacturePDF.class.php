<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class GenerationFacturePDF
 * @author mathurin
 */
class GenerationFacturePDF extends GenerationPDF {
    
    function __construct(Generation $g, $config = null, $options = null) {
        parent::__construct($g, $config, $options);
    }
    
    public function preGeneratePDF() {
       parent::preGeneratePDF();     
       $regions = explode(',',$this->generation->arguments->regions);
       $allMouvementsByRegion = FactureClient::getInstance()->getMouvementsForMasse($regions); 
       $mouvementsBySoc = FactureClient::getInstance()->getMouvementsNonFacturesBySoc($allMouvementsByRegion);
       $arguments = $this->generation->arguments->toArray();
       $mouvementsBySoc = FactureClient::getInstance()->filterWithParameters($mouvementsBySoc,$arguments);
       $message_communication = (array_key_exists('message_communication', $arguments))? $arguments['message_communication'] : null;
       if(!$this->generation->exist('somme')) $this->generation->somme = 0;
       $cpt = count($this->generation->documents);
       foreach ($mouvementsBySoc as $societeID => $mouvementsSoc) {
	 $societe = SocieteClient::getInstance()->find($societeID);
	 if (!$societe)
	   throw new sfException($societeID." unknown :(");
	 $facture = FactureClient::getInstance()->createDoc($mouvementsSoc, $societe, $arguments['date_facturation'],$message_communication);
         $facture->save();
         $this->generation->somme += $facture->total_ttc;
         $this->generation->documents->add($cpt,$facture->_id);
         $cpt++;
        }
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
