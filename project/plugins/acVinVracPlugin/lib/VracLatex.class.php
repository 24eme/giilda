<?php

class VracLatex extends GenericLatex {

  private $vrac = null;
  protected $conf = null;
  const VRAC_OUTPUT_TYPE_PDF = 'pdf';
  const VRAC_OUTPUT_TYPE_LATEX = 'latex';


  function __construct(Vrac $v, $config = null) {
    sfProjectConfiguration::getActive()->loadHelpers("Partial", "Url", "MyHelper");
    $this->vrac = $v;
    $this->conf = VracConfiguration::getInstance();
  }

  public function getNbPages() {
    return 1;
  }

  public function getLatexFileNameWithoutExtention() {
    return $this->getTEXWorkingDir().$this->vrac->numero_contrat.'_'.$this->vrac->_rev;
  }


  public function getLatexFileContents() {
    return html_entity_decode(htmlspecialchars_decode(
						      get_partial('vrac/pdf', array('vrac' => $this->vrac,
										  'nb_page' => $this->getNbPages()))
						      , HTML_ENTITIES));
  }

  public function getPublicFileName($extention = '.pdf') {
    return 'contrat_'.$this->vrac->numero_contrat.'_'.$this->vrac->_rev.$extention;
  }

}
