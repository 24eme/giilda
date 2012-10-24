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
       $allMouvementsByRegion = FactureClient::getInstance()->getMouvementsForMasse($regions,9); 
       $mouvementsByEtb = FactureClient::getInstance()->getMouvementsNonFacturesByEtb($allMouvementsByRegion);
       $arguments = $this->generation->arguments->toArray();
       $mouvementsByEtb = FactureClient::getInstance()->filterWithParameters($mouvementsByEtb,$arguments);
       $this->generation->documents = array();
       $this->generation->somme = 0;
       $cpt = 0;
       foreach ($mouvementsByEtb as $etablissementID => $mouvementsEtb) {
            $etablissement = EtablissementClient::getInstance()->findByIdentifiant($etablissementID);
            $facture = FactureClient::getInstance()->createDoc($mouvementsEtb, $etablissement, $arguments['date_facturation']);
            $facture->save();
            $this->generation->somme += $facture->total_ttc;
            $this->generation->documents->add($cpt, $facture->_id);
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
