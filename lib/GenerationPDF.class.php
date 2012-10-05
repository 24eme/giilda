<?php

class GenerationPDF {

  protected $generation = null;
  protected $config = null;
  
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
    $fileres = $this->concatenatePDFs($files);
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

  protected function cleanFiles($files) {
    foreach ($files as $f) {
      unlink($f);
    }
  }

  public function generatePDF() {
    if (!$this->generation) 
      throw new sfException('Object generation should not be null');    
  }

    public function preGeneratePDF() {
        
    }

}
