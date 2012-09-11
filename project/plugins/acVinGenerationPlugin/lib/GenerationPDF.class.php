<?php

class GenerationPDF {

  private $generation = null;
  private $config = null;
  
  function __construct(Generation $g, $config = null) {
    $this->generation = $g;
    $this->config = $config;
  }

  function concatenatePDFs($pdffiles) {
    $fileres = rand().".pdf";
    exec('pdftk "'.implode('" "', $pdffiles).'" cat output "'.$fileres.'.pdf"');
    return $fileres;
  }

  function generatePDFOnePageRange($pdfs) {
    $files = array();
    foreach ($pdfs as $pdf) {
      $files[] = $pdf->getPDFFile();
    }
    return $this->concatenatePDFs($files);
  }

  function generatePDF() {
    if (!$this->generation) 
      throw new sfException('Object generation should not be null');
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
      $pages[$page] = $this->generatePDFOnePageRange($pdfs);
    }
    return $this->concatenatePDFs($pages);
  }

}