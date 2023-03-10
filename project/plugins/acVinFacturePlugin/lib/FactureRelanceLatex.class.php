<?php

class FactureRelanceLatex extends GenericLatex {

  private $factures = array();
  private $filename = null;
  private $infos = null;
  protected $conf = null;

  function __construct(object $infos, $factures, $filename, $interpro = null) {
    sfProjectConfiguration::getActive()->loadHelpers("Partial", "Url", "MyHelper");
    $this->factures = $factures;
    $this->filename = $filename;
    $this->infos = $infos;
    $this->conf = FactureConfiguration::getInstance($interpro);
  }

  private function getFileNameWithoutExtention() {
    return  $this->filename;
  }

  public function getLatexFileNameWithoutExtention() {
    return $this->getTEXWorkingDir().$this->getFileNameWithoutExtention();
  }

  public function getLatexFileContents() {
    return html_entity_decode(htmlspecialchars_decode(get_partial("facture/pdf_relances_generique", array('infos' => $this->infos, 'emetteur' => $this->conf->getEmetteurCvo(), 'factures' => $this->factures, 'factureConfiguration' => $this->conf)), HTML_ENTITIES));
  }

  public function getPublicFileName($extention = '.pdf') {
    return $this->getFileNameWithoutExtention().$extention;
  }

}
