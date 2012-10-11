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
       $mouvementsByEtb = FactureClient::getInstance()->filterWithParameters($mouvementsByEtb,$this->generation->arguments->toArray());
       $this->generation->documents = array();
       $this->generation->somme = 0;
       $cpt = 0;
       foreach ($mouvementsByEtb as $etablissementID => $mouvementsEtb) {
            $etablissement = EtablissementClient::getInstance()->findByIdentifiant($etablissementID);
            $facture = FactureClient::getInstance()->createDoc($mouvementsEtb, $etablissement, $date_facturation);
            $facture->save();
            $this->generation->somme += $facture->total_ttc;
            $this->generation->documents->add($cpt, $facture->_id);
            $cpt++;
        }
    }
    
    public function generatePDF() {
        parent::generatePDF();
        $factures = array();
        foreach ($this->generation->documents as $factureid) {
        $facture = FactureClient::getInstance()->find($factureid);
        if (!$facture) {
            echo("Facture $factureid doesn't exist\n");
            continue;

        }
        $pdf = new FactureLatex($facture, $this->config);
        if (!isset($factures[$pdf->getNbPages()]))
            $factures[$pdf->getNbPages()] = array();
        array_push($factures[$pdf->getNbPages()], $pdf);
        }
        $pages = array();
        foreach ($factures as $page => $pdfs) {
        if (isset($this->options['page'.$page.'perpage']) && $this->options['page'.$page.'perpage']) {
            $this->generation->add('fichiers')->add($this->generatePDFGroupByPageNumberAndConcatenateThem($pdfs), 'Documents de '.$page.' page(s) trié par numéro de page');
        }else{
            $this->generation->add('fichiers')->add($this->generatePDFAndConcatenateThem($pdfs), 'Documents de '.$page.' page(s)');
        }
        }
        $this->generation->save();
        $this->cleanFiles($pages);
    }
}

?>
