<?php

class FactureLatex {

  private $facture = null;
  const MAX_LIGNE_TEMPLATE_ONEPAGE = 30;
  const MAX_LIGNE_TEMPLATE_TWOPAGE = 70;
  const MAX_LIGNE_TEMPLATE_PERPAGE = 80;
  const TEMPLATE_ONEPAGE = 'facture1Page';
  const TEMPLATE_TWOPAGE = 'facture2Pages';
  const TEMPLATE_MOREPAGE = 'factureMorePages';
  const FACTURE_OUTPUT_TYPE_PDF = 'pdf';
  const FACTURE_OUTPUT_TYPE_LATEX = 'latex';
  

  function __construct(Facture $f) {
    sfProjectConfiguration::getActive()->loadHelpers("Partial", "Url", "MyHelper");
    $this->facture = $f;
  }

  public function getNbPages() {
    $nbLigne = count($this->facture->echeances) * 3;
    foreach ($this->facture->lignes as $lignesType) {
      $nbLigne += count($lignesType) + 1;
    }
    if ($nbLigne < self::MAX_LIGNE_TEMPLATE_ONEPAGE)
      return 1;
    if ($nbLigne < self::MAX_LIGNE_TEMPLATE_TWOPAGE)
      return 2;
    return ($nbLigne - self::MAX_LIGNE_TEMPLATE_TWOPAGE) / self::MAX_LIGNE_TEMPLATE_PERPAGE;
  }
  
  public function getTemplate() {
    $nbPages = $this->getNbPages();
    if ($nbPages <= 1)
      return self::TEMPLATE_ONEPAGE;
    if ($nbPages <= 2)
      return self::TEMPLATE_TWOPAGE;
    return self::TEMPLATE_MOREPAGE;
  }
  
  
  public function getLatexFileNameWithoutExtention() {
    return $this->getTEXWorkingDir().$this->facture->identifiant.'_'.$this->facture->client_reference.'_'.$this->facture->_rev;
  }

  public function getLatexFileName() {
    return $this->getLatexFileNameWithoutExtention().'.tex';
  }
  
  public function getLatexFile() {
    $fn = $this->getLatexFileName();
    $leFichier = fopen($fn, "w");
    if (!$leFichier) {
      throw new sfException("Cannot write on ".$fn);
    }
    fwrite($leFichier, $this->getLatexFileContents());
    fclose($leFichier);
    $retour = chmod($fn,intval('0660',8));
    return $fn;
  }
  
  public function getLatexFileContents() {
    return html_entity_decode(htmlspecialchars_decode(
						      get_partial('facture/generateTex', array('facture' => $this->facture,
											       'template' => $this->getTemplate(),
											       'total_rows' => self::MAX_LIGNE_TEMPLATE_ONEPAGE))
						      , HTML_ENTITIES));
  }

  private function getLatexDestinationDir() {
    return sfConfig::get('sf_root_dir')."/data/latex/";
  }
  
  private function getTEXWorkingDir() {
    return "/tmp/";
  }

  public function generatePDF() {
    $cmdCompileLatex = '/usr/bin/pdflatex -output-directory="'.$this->getTEXWorkingDir().'" -synctex=1 -interaction=nonstopmode "'.$this->getLatexFile().'" 2>&1';
    exec($cmdCompileLatex, $output, $ret);
    $output = implode(' ', $output);
    if (!preg_match('/Transcript written/', $output)) {
      throw new sfException($output);
    }
    if ($ret) {
      $log = $this->getLatexFileNameWithoutExtention().'.log';
      $grep = preg_grep('/^!/', file_get_contents($log));
      array_unshift($grep, "/!\ Latex error\n");
      array_unshift($grep, "Latex log $log:\n");
      throw new sfException(implode(' ', $grep));
    }
    return $this->getLatexFileNameWithoutExtention().'.pdf';
  }

  private function cleanPDF() {
    $file = $this->getLatexFileNameWithoutExtention();
    unlink($file.'.aux');
    unlink($file.'.log');
    unlink($file.'.pdf');
    unlink($file.'.tex');
    unlink($file.'.synctex.gz');
  }

  public function getPDFFile() {
    $file = $this->getLatexDestinationDir().$this->getPublicFileName();
    if(file_exists($file))
      return $file;
    $tmpfile = $this->generatePDF();
    rename($tmpfile, $file);
    $this->cleanPDF();
    return $file;
  }

  public function getPDFFileContents() {
    return file_get_contents($this->getPDFFile());
  }

  public function getPublicFileName($extention = '.pdf') {
    return 'facture_'.$this->facture->client_reference.'_'.$this->facture->identifiant.'_page'.$this->getNbPages().'_'.$this->facture->_rev.$extention;
  }

  public function echoPDFWithHTTPHeader() {
    $attachement = 'attachment; filename='.$this->getPublicFileName();
    header("content-type: application/pdf\n");
    header("content-length: ".filesize($this->getPDFFile())."\n");
    header("content-disposition: $attachement\n\n");
    echo $this->getPDFFileContents();
  }

  public function echoLatexWithHTTPHeader() {
    $attachement = 'attachment; filename='.$this->getPublicFileName('.tex');
    header("content-type: application/latex\n");
    header("content-length: ".filesize($this->getLatexFile())."\n");
    header("content-disposition: $attachement\n\n");
    echo $this->getLatexFileContents();
  }

  public function echoFactureWithHTTPHeader($type = 'pdf') {
    if ($type == self::FACTURE_OUTPUT_TYPE_LATEX)
      return $this->echoLatexWithHTTPHeader();
    return $this->echoPDFWithHTTPHeader();
  }

}
