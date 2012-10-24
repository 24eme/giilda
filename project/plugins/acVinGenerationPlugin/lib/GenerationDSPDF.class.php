<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class GenerationDSPDF
 * @author mathurin
 */
class GenerationDSPDF extends GenerationPDF {
    
    function __construct(Generation $g, $config = null, $options = null) {
        parent::__construct($g, $config, $options);
    }
    
    public function preGeneratePDF() {
        parent::preGeneratePDF();
        
        $etablissementsViews = array(); 
	$operateur_types = array(EtablissementFamilles::FAMILLE_PRODUCTEUR, EtablissementFamilles::FAMILLE_NEGOCIANT);
	if ($this->generation->arguments->exist('operateur_types')) {
	  $operateur_types = explode(',',$this->generation->arguments->operateur_types);
	}
        foreach ($operateur_types as $operateur_type) {
	   if(EtablissementFamilles::FAMILLE_PRODUCTEUR != $operateur_type && EtablissementFamilles::FAMILLE_NEGOCIANT != $operateur_type)
              throw new sfException("this operateur type $operateur_type isn't a valid operateur type");
           $etablissementsViews = array_merge($etablissementsViews, EtablissementClient::getInstance()->findByFamille($operateur_type,null)->rows);
        }
       
        $dsClient = DSClient::getInstance();
        $cpt = 0;
        foreach ($etablissementsViews as $etablissement) {
            $ds = $dsClient->createDsByEtbId($etablissement->key[5], $this->generation->arguments->date_declaration);
            $ds->save();
            $this->generation->documents->add($cpt, $ds->_id);
            $cpt++;
        }
     }
    
    public function generatePDF() {
        parent::generatePDF();
                $dss = array();
        foreach ($this->generation->documents as $dsid) {
        $ds = DSClient::getInstance()->find($dsid);
        if (!$ds) {
            echo("DS $dsid doesn't exist\n");
            continue;

        }
        $pdf = new DSLatex($ds, $this->config);
        if (!isset($dss[$pdf->getNbPages()]))
            $dss[$pdf->getNbPages()] = array();
        array_push($dss[$pdf->getNbPages()], $pdf);
        }
        $pages = array();
        foreach ($dss as $page => $pdfs) {
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
