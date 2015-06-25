<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMLatex
 *
 * @author mathurin
 */
class DRMLatex extends GenericLatex {

  private $drm = null;
  const VRAC_OUTPUT_TYPE_PDF = 'pdf';
  const VRAC_OUTPUT_TYPE_LATEX = 'latex';
  
  const NB_PRODUITS_PER_PAGE = 5;
  

  function __construct(DRM $drm, $config = null) {
    sfProjectConfiguration::getActive()->loadHelpers("Partial", "Url", "MyHelper");
    $this->drm = $drm;
  }

  public function getNbPages() {
    return 1;
  }
  
  public function getLatexFileNameWithoutExtention() {
    return $this->getTEXWorkingDir().$this->drm->_id.'_'.$this->drm->_rev;
  }

  
  public function getLatexFileContents() {
    return html_entity_decode(htmlspecialchars_decode(
						      get_partial('drm_pdf/generateTex', array('drm' => $this->drm,
											  'nb_page' => $this->getNbPages()))
						      , HTML_ENTITIES));
  }

  public function getPublicFileName($extention = '.pdf') {
    return 'drm_'.$this->drm->_id.'_'.$this->drm->_rev.$extention;
  }
  
  public static function getMvtsEnteesForPdf() {
      $entrees = array();
      $configuration = ConfigurationClient::getCurrent();
      foreach ($configuration->libelle_detail_ligne->entrees as $key => $entree) {
          $entreeObj = new stdClass();
          $entreeObj->libelle = $entree;   
          $entreeObj->key =$key;
          $entrees[] = $entreeObj;
      }
      return $entrees;
  }
  
   public static function getMvtsSortiesForPdf() {
      $sorties = array();
      $configuration = ConfigurationClient::getCurrent();
      foreach ($configuration->libelle_detail_ligne->sorties as $key => $sortie) {
          $sortieObj = new stdClass();
          $sortieObj->libelle = $sortie;   
          $sortieObj->key =$key;
          $sorties[] = $sortieObj;
      }
      return $sorties;
  }

}
