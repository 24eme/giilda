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
    if (!count($pdffiles)) {
      return null;
    }
    $fileres = rand().".pdf";
    file_put_contents("/tmp/$fileres.sh", '/usr/bin/pdftk "'.implode('" "', $pdffiles).'" cat output "'.$fileres.'"');
    $str = system('bash /tmp/'.$fileres.'.sh 2>&1');
    if ($str) {
        throw new sfException('pdftk returned an error: '.$str);
    }
    return $fileres;
  }

  function generateAPDFForAPageId($pdf, $pageid) {
    if (!count($pdf))
      return null;
    $fileres = rand().".pdf";
    exec('pdftk "'.$pdf.'" cat '.intval($pageid).' output "'.$fileres.'"');
    return $fileres;    
  }

  function concatenatePDFsForAPageId($pdfs, $pageid) {
    $files = array();
    foreach ($pdfs as $pdf) {
      $f = $this->generateAPDFForAPageId($pdf, $pageid);
      if ($f) $files[] = $f;
    }
    $fileres = $this->concatenatePDFs($files);
    $this->cleanFiles($files);
    return $filesres;
  }

  private function generatePDFFiles($pdfs) {
    $files = array();
    foreach ($pdfs as $pdf) {
      $files[] = $pdf->getPDFFile();
    }
    return $files;
  }
  

  private function publishPDFFile($originpdf, $filename) {
    $publishname = "/generation/$filename.pdf";
    $publishrealdirname =  "web".$publishname;
    if (!file_exists($originpdf)) 
      throw new sfException("Origin $originpdf doesn't exist");
    if (!rename($originpdf, $publishrealdirname))
      throw new sfException("cannot write $publishrealdirname [rename($originpdf, $publishrealdirname)]");
    return urlencode($publishname);
  }

  function generatePDFAndConcatenateThem($pdfs) {
    return $this->concatenatePDFs($this->generatePDFFiles($pdfs));
  }

  function generatePDFGroupByPageNumberAndConcatenateThem($pdfs, $pagenumber) {
    $files = $this->generatePDFFiles($pdfs);
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
      if (!isset($pdfs[$pdf->getNbPages()]))
	$pdfs[$pdf->getNbPages()] = array();
      array_push($pdfs[$pdf->getNbPages()], $pdf);
    }
    $pages = array();
    foreach ($pdfs as $page => $pdfspage) {
      if (isset($this->options['page'.$page.'perpage']) && $this->options['page'.$page.'perpage']) {
	$origin = $this->generatePDFGroupByPageNumberAndConcatenateThem($pdfspage);
	if ($origin)
	  $this->generation->add('fichiers')->add($origin, $this->generation->date_emission.'-'.$page), 
						$this->getDocumentName().' de '.$page.' page(s) trié par numéro de page');
      }else{
        $origin = $this->generatePDFAndConcatenateThem($pdfs);
	if ($origin)
	  $this->generation->add('fichiers')->add($this->publishPDFFile($origin, $this->generation->date_emission.'-'.$page), 
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
