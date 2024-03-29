<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneForm
 * @author mathurin
 */
class VracConditionForm extends VracForm {

    protected $isTeledeclarationMode;

    public function __construct(Vrac $vrac, $isTeledeclarationMode = false, $options = array(), $CSRFSecret = null) {
        $this->isTeledeclarationMode = $isTeledeclarationMode;
        parent::__construct($vrac, $options, $CSRFSecret);
    }

    public function configure() {
        $anneesContrat = array(1 => "Année 1", 2 => "Année 2", 3 => "Année 3");
        $this->setWidget('delai_paiement', new bsWidgetFormChoice(array('choices' => $this->getDelaiPaiement()), array('class' => 'select2')));
        $this->setWidget('moyen_paiement', new bsWidgetFormChoice(array('choices' => $this->getMoyenPaiement()), array('class' => 'select2')));
        $this->setWidget('date_limite_retiraison', new bsWidgetFormInputDate());
        $this->setWidget('date_debut_retiraison', new bsWidgetFormInputDate());
        $this->setWidget('conditions_particulieres', new bsWidgetFormTextarea());
        $this->setWidget('tva', new bsWidgetFormChoice(array('choices' => $this->getTva(), 'expanded' => true)));

        $this->setWidget('pluriannuel', new bsWidgetFormInputCheckbox());
        $this->setWidget('clause_reserve_propriete', new bsWidgetFormInputCheckbox());
        $this->setWidget('autorisation_nom_vin', new bsWidgetFormInputCheckbox());
        $this->setWidget('autorisation_nom_producteur', new bsWidgetFormInputCheckbox());
        $this->setWidget('courtage_taux', new bsWidgetFormInputFloat());
        $this->setWidget('courtage_repartition', new bsWidgetFormChoice(array('choices' => $this->getCourtageRepartition()), array('class' => 'select2')));
        
        $this->setWidget('preparation_vin', new bsWidgetFormChoice(array('choices' => $this->getActeursPreparationVin(), 'expanded' => true)));
        $this->setWidget('embouteillage', new bsWidgetFormChoice(array('choices' => $this->getActeursEmbouteillage(), 'expanded' => true)));
        $this->setWidget('conditionnement_crd', new bsWidgetFormChoice(array('choices' => $this->getConditionnementsCRD(), 'expanded' => true)));

        $this->setWidget('annee_contrat', new bsWidgetFormChoice(array('choices' => $anneesContrat, 'expanded' => true)));
        $this->setWidget('seuil_revision', new bsWidgetFormInputFloat());
        $this->setWidget('acompte', new bsWidgetFormInputFloat());
        $this->setWidget('pourcentage_variation', new bsWidgetFormInputFloat());
        $this->setWidget('reference_contrat', new bsWidgetFormInput());
        $this->setWidget('cahier_charge', new bsWidgetFormInputCheckbox());

        $dateRegexpOptions = array('required' => true,
            'pattern' => "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/",
            'min_length' => 10,
            'max_length' => 10);
        $dateRegexpErrors = array('required' => 'Cette obligatoire',
            'invalid' => 'Date invalide (le format doit être jj/mm/aaaa)',
            'min_length' => 'Date invalide (le format doit être jj/mm/aaaa)',
            'max_length' => 'Date invalide (le format doit être jj/mm/aaaa)');

        $this->setValidator('delai_paiement', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getDelaiPaiement()))));
        $this->setValidator('moyen_paiement', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getMoyenPaiement()))));
        $this->setValidator('tva', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTva()))));
        $this->setValidator('conditions_particulieres', new sfValidatorString(array('required' => false)));
        $this->setValidator('date_limite_retiraison', new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true)));
        $this->setValidator('date_debut_retiraison', new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true)));
        $this->setValidator('pluriannuel', new sfValidatorBoolean(array('required' => false)));
        $this->setValidator('clause_reserve_propriete', new sfValidatorBoolean(array('required' => false)));
        $this->setValidator('autorisation_nom_vin', new sfValidatorBoolean(array('required' => false)));
        $this->setValidator('autorisation_nom_producteur', new sfValidatorBoolean(array('required' => false)));

        $this->setValidator('courtage_taux', new sfValidatorNumber(array('required' => false)));
        $this->setValidator('courtage_repartition', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCourtageRepartition()))));

        $this->setValidator('preparation_vin', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getActeursPreparationVin()))));
        $this->setValidator('embouteillage', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getActeursEmbouteillage()))));
        $this->setValidator('conditionnement_crd', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getConditionnementsCRD()))));
        $this->setValidator('annee_contrat', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($anneesContrat))));
        $this->setValidator('seuil_revision', new sfValidatorNumber(array('required' => false)));
        $this->setValidator('acompte', new sfValidatorNumber(array('required' => false)));
        $this->setValidator('pourcentage_variation', new sfValidatorNumber(array('required' => false)));
        $this->setValidator('reference_contrat', new sfValidatorString(array('required' => false)));
        $this->setValidator('cahier_charge', new sfValidatorBoolean(array('required' => false)));

        
        $this->validatorSchema['date_limite_retiraison']->setMessage('required', 'La date limite de retiraison doit être renseignée.');
        $this->validatorSchema['date_debut_retiraison']->setMessage('required', 'La date de début de retiraison doit être renseignée.');
        
        $this->unsetFields(VracConfiguration::getInstance()->getChampsSupprimes('condition', $this->getObject()->type_transaction));
        
        if (!$this->getObject()->mandataire_exist) {
            unset($this['courtage_taux'], $this['courtage_repartition']);
        }
        $this->widgetSchema->setNameFormat('vrac[%s]');
    }

    public function getDelaiPaiement() {
        return VracConfiguration::getInstance()->getDelaisPaiement();
    }

    public function getMoyenPaiement() {
        $moyensPaiement = VracConfiguration::getInstance()->getMoyensPaiement();
        if($this->getObject()->moyen_paiement && !isset($moyensPaiement[$this->getObject()->moyen_paiement])) {
            $moyensPaiement[$this->getObject()->moyen_paiement] = $this->getObject()->moyen_paiement_libelle;
        }

        return $moyensPaiement;
    }

    public function getCourtageRepartition() {
        return VracConfiguration::getInstance()->getRepartitionCourtage();
    }

    public function getTva() {
        return VracConfiguration::getInstance()->getTva();
    }

    public function getActeursPreparationVin() {
        return VracConfiguration::getInstance()->getActeursPreparationVin();
    }

    public function getActeursEmbouteillage() {
        return VracConfiguration::getInstance()->getActeursEmbouteillage();
    }

    public function getConditionnementsCRD() {
        return VracConfiguration::getInstance()->getConditionnementsCRD();
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if ($this->getObject()->clause_reserve_propriete === null) {
            $this->getObject()->clause_reserve_propriete = 0;
        }
        if (!isset($values['cahier_charge']) || !$values['cahier_charge']) {
            $this->getObject()->cahier_charge = 0;
        }
        if (isset($values['cahier_charge']) && $values['cahier_charge']) {
            $this->getObject()->cahier_charge = 1;
        }        
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $this->setDefault('date_limite_retiraison', $this->getObject()->getDateLimiteRetiraison('d/m/Y'));
        $this->setDefault('date_debut_retiraison', $this->getObject()->getDateDebutRetiraison('d/m/Y'));
        if ($this->getObject()->clause_reserve_propriete === null) {
            $this->setDefault('clause_reserve_propriete', true);
        }
        if (!$this->getObject()->preparation_vin) {
            $this->setDefault('preparation_vin', 'ACHETEUR');
        }
        if (!$this->getObject()->embouteillage) {
            $this->setDefault('embouteillage', 'ACHETEUR');
        }
        if (!$this->getObject()->conditionnement_crd) {
            $this->setDefault('conditionnement_crd', 'NEGOCE_ACHEMINE');
        }
        if (!$this->getObject()->annee_contrat) {
            $this->setDefault('annee_contrat', 1);
        }
        if ($this->getObject()->cahier_charge) {
            $this->setDefault('cahier_charge', true);
        } else {
            $this->setDefault('cahier_charge', false);
        }
        if (!$this->getObject()->tva) {
            $this->setDefault('tva', 'AVEC');
        }
        if ($this->getObject()->categorie_vin != 'GENERIQUE') {
            $this->setDefault('autorisation_nom_vin', true);
            $this->setDefault('autorisation_nom_producteur', true);
        }
        if (is_null($this->getObject()->courtage_taux)) {
            $this->setDefault('courtage_taux', 2);
        }
        if(is_null($this->getObject()->courtage_repartition)){
             $this->setDefault('courtage_repartition','ACHETEUR' );
            
        }
    }

}
