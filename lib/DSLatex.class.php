<?php

class DSLatex {

  private $ds = null;
  const MAX_LIGNE_TEMPLATE_ONEPAGE = 35;
  const DS_OUTPUT_TYPE_PDF = 'pdf';
  const DS_OUTPUT_TYPE_LATEX = 'latex';
  

  function __construct(DS $d, $config = null) {
    sfProjectConfiguration::getActive()->loadHelpers("Partial", "Url", "MyHelper");
    $this->ds = $d;
  }

  public function getNbPages() {
    return 1;
  }
  
  public function getLatexFileNameWithoutExtention() {
    return $this->getTEXWorkingDir().$this->ds->identifiant.'_'.$this->ds->periode.'_'.$this->ds->_rev;
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
						      get_partial('ds/generateTex', array('ds' => $this->ds,
                                                                                          'etablissement' => EtablissementClient::getInstance()->find($this->ds->identifiant),
											  'nb_page' => $this->getNbPages()))
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
    $output = array();
    exec($cmdCompileLatex, $output, $ret);
    $output = implode(' ', $output);
    if (!preg_match('/Transcript written/', $output) || preg_match('/Fatal error/', $output)) {
      throw new sfException($output);
    }
    if ($ret) {
      $log = $this->getLatexFileNameWithoutExtention().'.log';
      $grep = preg_grep('/^!/', file_get_contents($log));
      array_unshift($grep, "/!\ Latex error\n");
      array_unshift($grep, "Latex log $log:\n");
      if ($grep){
              throw new sfException(implode(' ', $grep));
      }
    }
    $pdfpath = $this->getLatexFileNameWithoutExtention().'.pdf';
    if (!file_exists($pdfpath)) {
      throw new sfException("pdf not created ($pdfpath): ".$output);
    }
    return $pdfpath;
  }

  private function cleanPDF() {
    $file = $this->getLatexFileNameWithoutExtention();
    @unlink($file.'.aux');
    @unlink($file.'.log');
    @unlink($file.'.pdf');
    @unlink($file.'.tex');
    @unlink($file.'.synctex.gz');
  }

  public function getFactureId() {
    return $this->ds->_id;
  }

  public function getPDFFile() {
    $filename = $this->getLatexDestinationDir().$this->getPublicFileName();
    if(file_exists($filename))
      return $filename;
    $tmpfile = $this->generatePDF();
    if (!file_exists($tmpfile)) {
      throw new sfException("pdf not created :(");
    }
    if (!rename($tmpfile, $filename)) {
      throw new sfException("not possible to rename $tmpfile to $filename");
    }
    $this->cleanPDF();
    return $filename;
  }

  public function getPDFFileContents() {
    return file_get_contents($this->getPDFFile());
  }

  public function getPublicFileName($extention = '.pdf') {
    return 'ds_'.$this->ds->identifiant.'_'.$this->ds->periode.'_page'.$this->getNbPages().'_'.$this->ds->_rev.$extention;
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
    if ($type == self::DS_OUTPUT_TYPE_LATEX)
      return $this->echoLatexWithHTTPHeader();
    return $this->echoPDFWithHTTPHeader();
  }
 
}
