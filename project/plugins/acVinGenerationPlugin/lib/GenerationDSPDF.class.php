<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class GenerationDSPDF
 * @author mathurin
 */
class GenerationDSPDF extends GenerationPDF {

    function __construct(Generation $g, $config = null, $options = null) {
        parent::__construct($g, $config, $options);
    }

    public function preGeneratePDF() {
        parent::preGeneratePDF();

        $etablissementsViews = array();
        $operateur_types = array(EtablissementFamilles::FAMILLE_PRODUCTEUR, EtablissementFamilles::FAMILLE_NEGOCIANT);
        if ($this->generation->arguments->exist('operateur_types')) {
            $operateur_types = explode(',', $this->generation->arguments->operateur_types);
        }
        foreach ($operateur_types as $operateur_type) {
            if (EtablissementFamilles::FAMILLE_PRODUCTEUR != $operateur_type && EtablissementFamilles::FAMILLE_NEGOCIANT != $operateur_type)
                throw new sfException("this operateur type $operateur_type isn't a valid operateur type");
            $etablissementsViews = array_merge($etablissementsViews, EtablissementClient::getInstance()->findByFamille($operateur_type, null)->rows);
        }

        $dsClient = DSClient::getInstance();
        $cpt = 0;
        foreach ($etablissementsViews as $etablissement) {
            try {
                $ds = $dsClient->createDsByEtbId($etablissement->key[5], $this->generation->arguments->date_declaration);
                $ds->save();
                $this->generation->documents->add($cpt, $ds->_id);
                $cpt++;
            } catch (sfException $exc) {
                echo $exc->getMessage();
                continue;
            }
        }
    }

    protected function getDocumentName() {
        return "Declarations de stock";
    }

    protected function generatePDFForADocumentID($docid) {
        $ds = DSClient::getInstance()->find($docid);
        if (!$ds) {
            throw new sfException("DS $dsid doesn't exist\n");
            continue;
        }
        return new DSLatex($ds, $this->config);
    }

}
