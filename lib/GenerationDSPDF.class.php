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
        
        $etablissementClient = EtablissementClient::getInstance();
        $etablissementsViews = array(); 
        $operateur_types = explode(',',$this->generation->arguments->operateur_types);
       // $generation->arguments->add('regions', implode(',', array_values($values['regions'])));
        if(count($operateur_types)===1)
        {
            if(($operateur_types[0]!== EtablissementFamilles::FAMILLE_PRODUCTEUR) && ($operateur_types[0]!== EtablissementFamilles::FAMILLE_NEGOCIANT))
              throw new sfException("this operateur type $operateur_types[0] isn't a valid operateur type");
            $etablissementsViews = $etablissementClient->findByFamille($operateur_types[0],null)->rows;
            
        }
        else
        {
            if(!in_array(EtablissementFamilles::FAMILLE_PRODUCTEUR,$operateur_types) || !in_array(EtablissementFamilles::FAMILLE_NEGOCIANT,$operateur_types))
              throw new sfException("this operateur type $operateur_types isn't a valid operateur type");
            
          foreach ($operateur_types as $operateur_type) {
              $etablissementsViews = array_merge($etablissementsViews, $etablissementClient->findByFamille($operateur_type,null)->rows);
          }
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
