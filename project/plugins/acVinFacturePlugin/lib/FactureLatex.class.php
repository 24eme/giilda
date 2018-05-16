<?php

class FactureLatex extends GenericLatex {

  private $facture = null;

  const MAX_LIGNES_PERPAGE = 27;

  function __construct(Facture $f, $config = null) {
    sfProjectConfiguration::getActive()->loadHelpers("Partial", "Url", "MyHelper");
    $this->facture = $f;
  }


  public function getNbLignes() {
    return $this->facture->getNbLignesAndDetails();
  }

  public function getNbPages() {
    return floor($this->getNbLignes() / self::MAX_LIGNES_PERPAGE) + 1;
  }

  private function getFileNameWithoutExtention() {
    return  'facture_'.$this->facture->identifiant.'_'.str_replace('/', '-', $this->facture->numero_interloire).'_'.$this->facture->numero_facture.'_'.$this->facture->_rev;
  }

  public function getLatexFileNameWithoutExtention() {
    return $this->getTEXWorkingDir().$this->getFileNameWithoutExtention();
  }


  public function getLatexFileContents() {
    return html_entity_decode(htmlspecialchars_decode(

		get_partial("facture/pdf_generique", array('facture' => $this->facture,
						'total_pages' => $this->getNbPages(),
                        'lines_per_page' => self::MAX_LIGNES_PERPAGE,
                        'page_nb' => 1
                        ))
						      , HTML_ENTITIES));
  }


  public function getFactureId() {
    return $this->facture->_id;
  }

  public function getPublicFileName($extention = '.pdf') {
    return $this->getFileNameWithoutExtention().$extention;
  }

}
