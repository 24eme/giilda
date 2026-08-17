<?php

class FactureLatex extends GenericLatex {

  private $facture = null;
  protected $conf = null;

  const MAX_LIGNES_PERPAGE = 42;
  //Entête première page
  const NB_LIGNES_ENTETE_FIRST_PAGE = 8;
  //bloc total  + TVA
  const NB_LIGNES_REGLEMENT = 7;
  //papillon de règlement
  const NB_LIGNES_PAPILLONS_FIXE = 4;
  const NB_LIGNES_PAPILLONS_PAR_ECHEANCE = 4;

  function __construct(Facture $f, $config = null) {
    sfProjectConfiguration::getActive()->loadHelpers("Partial", "Url", "MyHelper");
    $this->facture = $f;
    $this->interpro = ($this->facture->exist('interpro'))? $this->facture->interpro : null;
    $this->conf = FactureConfiguration::getInstance($this->interpro);
  }

  public function getNbLignesEcheancesPapillon() {
    return count($this->facture->getEcheancesPapillon());
  }

  public function getNbLignesFooter() {
    return self::NB_LIGNES_PAPILLONS_FIXE + self::NB_LIGNES_PAPILLONS_PAR_ECHEANCE * $this->getNbLignesEcheancesPapillon();
  }

  public function getNbLignesReglement() {
      $nb_lignes_msg = $this->facture->getNbLignesMessageReglement();
      if (self::NB_LIGNES_REGLEMENT > $nb_lignes_msg) {
          return self::NB_LIGNES_REGLEMENT;
      }
      return $nb_lignes_msg;
  }

  public function getNbLignes() {
    return $this->facture->getNbLignesAndDetails() + $this->getNbLignesHeaderFirstPage() + $this->getNbLignesReglement() + $this->getNbLignesFooter();
  }

  public function getNbPages() {
    return floor($this->getNbLignes() / self::MAX_LIGNES_PERPAGE) + 1;
  }

  private function getFileNameWithoutExtention() {
    return  'facture_'.$this->facture->identifiant.'_'.str_replace('/', '-', $this->facture->numero_piece_comptable).'_'.$this->facture->numero_facture.'_'.$this->facture->_rev;
  }

  public function getLatexFileNameWithoutExtention() {
    return $this->getTEXWorkingDir().$this->getFileNameWithoutExtention();
  }

  public function getNbLignesHeaderFirstPage() {
      return $this->facture->getNbLignesMessageCommunicationWithDefault() + self::NB_LIGNES_ENTETE_FIRST_PAGE;
  }


  public function getLatexFileContents() {
    $total_lines_without_footer =  $this->facture->getNbLignesAndDetails() + $this->getNbLignesHeaderFirstPage();
    $total_pages_without_footer = floor($total_lines_without_footer / self::MAX_LIGNES_PERPAGE) + 1;
    if ($total_pages_without_footer == $this->getNbPages()) {
      $lines_per_page = FactureLatex::MAX_LIGNES_PERPAGE;
    }else{
      $lines_per_page = FactureLatex::MAX_LIGNES_PERPAGE - ($this->getNbLignes() - $total_lines_without_footer) / ($this->getNbPages() - 1);
    }
    return html_entity_decode(htmlspecialchars_decode(
						      get_partial("facture/pdf_generique", array('facture' => $this->facture,
												'total_pages' => $this->getNbPages(),
                        'total_lines' => $this->getNbLignes(),
                        'lines_per_page' => $lines_per_page,
                        'total_lines_footer' => $this->getNbLignes() - $total_lines_without_footer,
                        'line_nb' => $this->getNbLignesHeaderFirstPage(),
                        'page_nb' => 1,
                        'nb_echeances' => $this->getNbLignesEcheancesPapillon()
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
