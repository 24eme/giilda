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
       $documents = array();
       $this->generation->somme = 0;
       $cpt = 0;
       $total = 0;
       foreach ($mouvementsBySoc as $societeID => $mouvementsSoc) {
	 $societe = SocieteClient::getInstance()->find($societeID);
	 if (!$societe)
	   throw new sfException($societeID." unknown :(");
	 $facture = FactureClient::getInstance()->createDoc($mouvementsSoc, $societe, $arguments['date_facturation']);
         $facture->save();
         $total += $facture->total_ttc;
         $documents[$cpt] = $facture->_id;
         $this->generation->somme = $total;
	 $this->generation->documents = $documents;
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
