<?php

class GenerationPDF {

  private $generation = null;
  private $config = null;
  
  function __construct(Generation $g, $config = null, $options = null) {
    $this->generation = $g;
    $this->config = $config;
    $this->options = $options;
  }

  function concatenatePDFs($pdffiles) {
    $fileres = rand().".pdf";
    exec('pdftk "'.implode('" "', $pdffiles).'" cat output "'.$fileres.'"');
    return $fileres;
  }

  function generateAPDFForAPageId($pdf, $pageid) {
    $fileres = rand().".pdf";
    exec('pdftk "'.$pdf.'" cat '.intval($pageid).' output "'.$fileres.'"');
    return $fileres;    
  }

  function concatenatePDFsForAPageId($pdfs, $pageid) {
    $files = array();
    foreach ($pdfs as $pdf) {
      $files[] = $this->generateAPDFForAPageId($pdf, $pageid);
    }
    $fileres = $this-> concatenatePDFs($files);
    $this->cleanFiles($files);
    return $filesres;
  }

  private function generatePDFFile($pdfs) {
    $files = array();
    foreach ($pdfs as $pdf) {
      $files[] = $pdf->getPDFFile();
    }
    return $files;
  }
  
  function generatePDFAndConcatenateThem($pdfs) {
    return $this->concatenatePDFs($this->generatePDFFile($pdfs));
  }

  function generatePDFGroupByPageNumberAndConcatenateThem($pdfs, $pagenumber) {
    $files = $this->generatePDFFile($pdfs);
    $filesbypage = array();
    for($i = 1 ; $i <= $pagenumber ; $i++) {
      $filesbypage[] = $this->concatenatePDFsForAPageId($pdfs, $i);
    }
    $this->cleanFiles($files);
    $res = $this->concatenatePDFs($filesbypage);
    $this->cleanFiles($filesbypage);
    return $res;
  }

  private function cleanFiles($files) {
    foreach ($files as $f) {
      unlink($f);
    }
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
