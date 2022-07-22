<?php

class FactureRelanceLatex extends GenericLatex {

  private $factures = array();
  private $filename = null;
  private $infos = null;
  private $emetteur = null;
  protected $conf = null;

  function __construct(object $infos, $factures, $filename) {
    sfProjectConfiguration::getActive()->loadHelpers("Partial", "Url", "MyHelper");
    $this->factures = $factures;
    $this->filename = $filename;
    $this->infos = $infos;
    $this->conf = FactureConfiguration::getInstance();
    $this->emetteur = sfConfig::get('app_configuration_facture')['emetteur_cvo'];
  }

  private function getFileNameWithoutExtention() {
    return  $this->filename;
  }

  public function getLatexFileNameWithoutExtention() {
    return $this->getTEXWorkingDir().$this->getFileNameWithoutExtention();
  }

  public function getLatexFileContents() {
    return html_entity_decode(htmlspecialchars_decode(get_partial("facture/pdf_relances_generique", array('infos' => $this->infos, 'emetteur' => $this->emetteur, 'factures' => $this->factures)), HTML_ENTITIES));
  }

  public function getPublicFileName($extention = '.pdf') {
    return $this->getFileNameWithoutExtention().$extention;
  }

}
