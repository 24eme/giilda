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
        $this->generation->arguments->add();
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
