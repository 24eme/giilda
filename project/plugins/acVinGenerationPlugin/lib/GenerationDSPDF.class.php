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
    }
    
    public function generatePDF() {
        parent::generatePDF();
        $ds = array();
        foreach ($this->generation->documents as $dsid) {
        $ds = DSClient::getInstance()->find($dsid);        
        if (!$ds) {
            echo("DS $ds doesn't exist\n");
            continue;

        }
        $pdf = new DSLatex($ds, $this->config);
        if (!isset($ds[$pdf->getNbPages()]))
            $ds[$pdf->getNbPages()] = array();
        array_push($ds[$pdf->getNbPages()], $pdf);
        }
        $pages = array();
        foreach ($ds as $page => $pdfs) {
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
