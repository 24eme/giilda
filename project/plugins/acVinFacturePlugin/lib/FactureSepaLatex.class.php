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
class FactureSepaLatex extends GenericLatex {

    private $societe = null;

    function __construct(Societe $societe, $config = null) {
        sfProjectConfiguration::getActive()->loadHelpers("Partial", "Url", "MyHelper");
        $this->societe = $societe;
    }

    public function getLatexFileNameWithoutExtention() {
        return $this->getTEXWorkingDir() . $this->societe->_id . '_' . $this->societe->_rev;
    }

    public function getLatexFileContents() {
        return html_entity_decode(htmlspecialchars_decode(
                        get_partial('facture/pdf_sepa', array('societe' => $this->societe))
                        , HTML_ENTITIES));
    }

    public function getPublicFileName($extention = '.pdf') {
        return 'sepa_' . $this->societe->identifiant . '_' . $this->societe->_rev . $extention;
    }


}
