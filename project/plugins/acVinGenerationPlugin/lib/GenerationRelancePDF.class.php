<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class GenerationRelancePDF
 * @author mathurin
 */
class GenerationRelancePDF extends GenerationPDF {
    
    function __construct(Generation $g, $config = null, $options = null) {
        parent::__construct($g, $config, $options);
    }
    
    public function preGeneratePDF() {
       parent::preGeneratePDF(); 
       $arguments = $this->generation->arguments->toArray();
       $id_gen = $this->generation->_id;
       
       if(!array_key_exists('date_emission', $arguments)){
           throw new sfException("Les arguments de la génération $id_gen doivent comprendre une date d'emission.");
       }
       
//       
//        $etablissementsViews = EtablissementClient::getInstance()->findAll()->rows;        
//        $cpt = count($this->generation->documents);
//        foreach ($etablissementsViews as $etablissement) {
//            $etb_id = $etablissement->key[EtablissementRegionView::KEY_IDENTIFIANT];
//            $alertes_relancables = AlerteHistoryView::getInstance()->getRechercheByEtablissementAndStatut($etb_id, AlerteClient::STATUT_A_RELANCER);
//            if(count($alertes_relancables)){
//                
//            }
//            $this->generation->documents->add($cpt, $alerte->_id);
//            $cpt++;
//        }  
    }
    
    protected function generatePDFForADocumentId($relanceId) {
      $relance = RelanceClient::getInstance()->find($relanceId);
      if (!$relance) {
	throw new sfException("La relance $relanceId doesn't exist\n");
      }
      return new RelanceLatex($relance, $this->config);
    }

    protected function getDocumentName() {
      return "Relances";
    }
    
}
