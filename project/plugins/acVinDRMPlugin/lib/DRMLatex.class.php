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
    private $libelles_detail_ligne = null;

    const VRAC_OUTPUT_TYPE_PDF = 'pdf';
    const VRAC_OUTPUT_TYPE_LATEX = 'latex';
    const NB_PRODUITS_PER_PAGE = 6;

    function __construct(DRM $drm, $config = null) {
        sfProjectConfiguration::getActive()->loadHelpers("Partial", "Url", "MyHelper");
        $this->drm = $drm;
        $configuration = ConfigurationClient::getCurrent();
        $this->libelles_detail_ligne = $configuration->libelle_detail_ligne->get($this->drm->getDetailsConfigKey());
    }

    public function getNbPages() {
        return 1;
    }

    public function getLatexFileNameWithoutExtention() {
        return $this->getTEXWorkingDir() . $this->drm->_id . '_' . $this->drm->_rev;
    }

    public function getLatexFileContents() {
        return html_entity_decode(htmlspecialchars_decode(
                        get_partial('drm_pdf/generateTex', array('drm' => $this->drm,
            'nb_page' => $this->getNbPages(),
            'drmLatex' => $this))
                        , HTML_ENTITIES));
    }

    public function getPublicFileName($extention = '.pdf') {
        return 'drm_' . $this->drm->_id . '_' . $this->drm->_rev . $extention;
    }

    public function getMvtsEnteesForPdf() {
        $entrees = array();

        foreach ($this->libelles_detail_ligne->entrees as $key => $entree) {
            $entreeObj = new stdClass();
            $entreeObj->libelle = $entree->libelle;
            $entreeObj->key = $key;
            $entrees[] = $entreeObj;
        }
        return $entrees;
    }

    public function getMvtsSortiesForPdf() {
        $sorties = array();
        foreach ($this->libelles_detail_ligne->sorties as $key => $sortie) {
            $sortieObj = new stdClass();
            $sortieObj->libelle = $sortie->libelle;
            $sortieObj->key = $key;
            $sorties[] = $sortieObj;
        }
        return $sorties;
    }

}
