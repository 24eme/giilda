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
  

  private function publishPDFFile($originpdf, $filename) {
    $publishname = "/generation/$filename.pdf";
    $publishrealdirname =  "web".$publishname;
    if (!rename($originpdf, $publishrealdirname))
      throw new sfException("cannot write $publishrealdirname");
    return urlencode($publishname);
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
    $pdfs = array();
    if (!count($this->generation->documents)) {
      $this->preGeneratePDF();
      $this->generation->save();
    }
    $this->generation->setStatut(GenerationClient::GENERATION_STATUT_GENERE);
    foreach ($this->generation->documents as $docid) {
      $pdf = $this->generatePDFForADocumentID($docid);
      if (!isset($factures[$pdf->getNbPages()]))
	$pdfs[$pdf->getNbPages()] = array();
      array_push($pdfs[$pdf->getNbPages()], $pdf);
    }
    $pages = array();
    foreach ($pdfs as $page => $pdfs) {
      if (isset($this->options['page'.$page.'perpage']) && $this->options['page'.$page.'perpage']) {
	$this->generation->add('fichiers')->add($this->publishPDFFile($this->generatePDFGroupByPageNumberAndConcatenateThem($pdfs), $this->generation->date_emission.'-'.$page), 
						$this->getDocumentName().' de '.$page.' page(s) trié par numéro de page');
      }else{
	$this->generation->add('fichiers')->add($this->publishPDFFile($this->generatePDFAndConcatenateThem($pdfs), $this->generation->date_emission.'-'.$page), 
						$this->getDocumentName().' de '.$page.' page(s)');
      }
    }
    $this->cleanFiles($pages);
    $this->generation->save();
  }

  protected function getDocumentName() {
    throw new sfException('should be called from the parent class');
  }
  protected function generatePDFForADocumentID($docid) {
    throw new sfException('should be called from the parent class');
  }

  function preGeneratePDF() {
    
  }

}
