<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracHistoryRechercheForm
 * @author mathurin
 */
class VracHistoryRechercheForm extends sfForm {

    private $societe;
    private $campagne;
    private $etablissement;
    private $statut;
    private $onlyOneEtb;

    public function __construct(Societe $societe, $etablissement, $campagne, $statut, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->societe = $societe;
        $this->campagne = $campagne;
        $this->etablissement = $etablissement;
        $this->statut = $statut;
        $this->onlyOneEtb = !(count($this->getEtablissements()) - 1);
        $defaults['campagne'] = $this->campagne;
        if (!$this->onlyOneEtb) {
            $defaults['etablissement'] = $this->etablissement;
        }
        $defaults['statut'] = $this->statut;

        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidget('campagne', new sfWidgetFormChoice(array('choices' => $this->getCampagnes(), 'expanded' => false)));
        $this->setWidget('statut', new sfWidgetFormChoice(array('choices' => $this->getStatuts(), 'expanded' => false)));


        $this->widgetSchema->setLabel('campagne', 'Campagne');
        $this->widgetSchema->setLabel('statut', 'Statut');

        $this->setValidator('campagne', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCampagnes()))));
        $this->setValidator('statut', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getStatuts()))));

        if (!$this->onlyOneEtb) {
            $this->setWidget('etablissement', new sfWidgetFormChoice(array('choices' => $this->getEtablissements(), 'expanded' => false)));
            $this->widgetSchema->setLabel('etablissement', 'Etablissement');
            $this->setValidator('etablissement', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getEtablissements()))));
        }
    }

    private function getCampagnes() {
        return array_merge(VracClient::getInstance()->listCampagneBySocieteId($this->societe->identifiant));
    }

    private function getEtablissements() {
        $etablissements = $this->societe->getEtablissementsObj();

        $etbArr = array();
        $etbArr['tous'] = 'Tous les établissements';
        foreach ($etablissements as $id => $etbObj) {
            $etbArr[$etbObj->etablissement->identifiant] = $etbObj->etablissement->getDenomination();
        }
        return $etbArr;
    }

    private function getStatuts() {
        $all_statuts = VracClient::$statuts_teledeclaration_sorted;

        $statuts = array();
        $statuts['tous'] = 'Tous les statuts';
        foreach ($all_statuts as $statut) {
            if ($this->societe->isViticulteur() && $statut == VracClient::STATUS_CONTRAT_BROUILLON) {
                continue;
            }
            if ($statut == VracClient::STATUS_CONTRAT_VISE || $statut == VracClient::STATUS_CONTRAT_VALIDE) {
                continue;
            }
            if ($statut == VracClient::STATUS_CONTRAT_SOLDE || $statut == VracClient::STATUS_CONTRAT_NONSOLDE) {
                $statuts["SOLDENONSOLDE"] = VracClient::$statuts_labels_teledeclaration[$statut];
                continue;
            }
            if ($statut == VracClient::STATUS_CONTRAT_ATTENTE_SIGNATURE) {
                if (!$this->societe->isCourtier()) {
                    $statuts[VracClient::STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_MOI] = 'A signer';
                }
                $statuts[VracClient::STATUS_SOUSSIGNECONTRAT_ATTENTE_SIGNATURE_AUTRES] = VracClient::$statuts_labels_teledeclaration[$statut];
                continue;
            }

            $statuts[$statut] = VracClient::$statuts_labels_teledeclaration[$statut];
        }
        return $statuts;
    }

}
