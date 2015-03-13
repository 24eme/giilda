<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class GenerationRelancePDF
 * @author mathurin
 */
class GenerationRelancePDF extends GenerationPDF {
    
    const MAX_LIGNE_TABLEAUX = 41;

    function __construct(Generation $g, $config = null, $options = null) {
        parent::__construct($g, $config, $options);
    }

    public function preGeneratePDF() {        
        parent::preGeneratePDF();
        $arguments = $this->generation->arguments->toArray();
        $id_gen = $this->generation->_id;

        if (!array_key_exists('date_relance', $arguments)) {
            throw new sfException("Les arguments de la génération $id_gen doivent comprendre une date de relance.");
        }
        if (!array_key_exists('types_relance', $arguments)) {
            throw new sfException("Les arguments de la génération $id_gen doivent comprendre le(s) type(s) de relance à générer.");
        }
        $date_relance = $this->generation->arguments->date_relance;
        $types_relance = explode(',', $this->generation->arguments->types_relance);
        $etablissementsViews = EtablissementAllView::getInstance()->findByInterproStatutAndFamilles('INTERPRO-inter-loire', EtablissementClient::STATUT_ACTIF, array(EtablissementFamilles::FAMILLE_PRODUCTEUR,EtablissementFamilles::FAMILLE_NEGOCIANT), null, -1);

        $cpt = count($this->generation->documents);
        foreach ($etablissementsViews as $etablissement) {
            $alertes_relancables = array();
            $etb_id = $etablissement->key[EtablissementAllView::KEY_IDENTIFIANT];
            $etb = EtablissementClient::getInstance()->find($etb_id);
            if (!$etb)
                throw new sfException($etb_id . " unknown :(");
            $alertes_relancables_sorted = array();
            $alertes_relancables = array();
            foreach ($types_relance as $type_relance) {
                $alertes_relancables = array_merge($alertes_relancables,AlerteRelanceView::getInstance()->getRechercheByEtablissementAndStatutAndTypeRelance($etb_id, AlerteClient::STATUT_A_RELANCER, $type_relance));
                $alertes_relancables = array_merge($alertes_relancables,AlerteRelanceView::getInstance()->getRechercheByEtablissementAndStatutAndTypeRelance($etb_id, AlerteClient::STATUT_A_RELANCER_AR, $type_relance));  
            }
            if(!count($alertes_relancables)){
                continue;
            }
             
            $alertes_relancables_sorted = AlerteRelanceView::getInstance()->sortAlertesForRelances($alertes_relancables);
            if (count($alertes_relancables_sorted)) {
                foreach ($alertes_relancables_sorted as $type_relance => $alertes_relances_type) {
                            $relance = RelanceClient::getInstance()->createDoc($type_relance,$alertes_relances_type, $etb, $date_relance);

                            $relance->save();
                            $this->generation->add('documents')->add($cpt, $relance->_id);  
                            $cpt++;
                }
            }
        }
    }

    protected function generatePDFForADocumentId($relanceId) {
        $relance = RelanceClient::getInstance()->find($relanceId);
        if (!$relance) {
            throw new sfException("La relance $relanceId doesn't exist\n");
        }
        return new RelanceLatex($relance, $this->config);
    }

    protected function getDocumentName() {
        return "Relances";
    }

}
