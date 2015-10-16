<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracSoussigneForm
 * @author mathurin
 */
class VracConditionForm extends acCouchdbObjectForm {
	
    protected $isTeledeclarationMode;

    public function __construct(Vrac $vrac, $isTeledeclarationMode = false, $options = array(), $CSRFSecret = null) {
        $this->isTeledeclarationMode = $isTeledeclarationMode;
        parent::__construct($vrac, $options, $CSRFSecret);
    }

    public function configure() {
    	$anneesContrat = array(1 => "Année 1", 2 => "Année 2", 3 => "Année 3");
        $this->setWidget('delai_paiement', new bsWidgetFormChoice(array('choices' => $this->getDelaiPaiement())));
        $this->setWidget('moyen_paiement', new bsWidgetFormChoice(array('choices' => $this->getMoyenPaiement())));
        $this->setWidget('date_limite_retiraison', new bsWidgetFormInputDate());
        $this->setWidget('date_debut_retiraison', new bsWidgetFormInputDate());
        $this->setWidget('taux_repartition', new bsWidgetFormChoice(array('choices' => $this->getCvoRepartition())));
        $this->setWidget('conditions_particulieres', new bsWidgetFormTextarea());
        $this->setWidget('tva', new bsWidgetFormChoice(array('choices' => $this->getTva(), 'expanded' => true)));
        
        $this->setWidget('pluriannuel', new bsWidgetFormInputCheckbox());
        $this->setWidget('clause_reserve_propriete', new bsWidgetFormInputCheckbox());
        $this->setWidget('autorisation_nom_vin', new bsWidgetFormInputCheckbox());
        $this->setWidget('autorisation_nom_producteur', new bsWidgetFormInputCheckbox());
        $this->setWidget('taux_courtage', new bsWidgetFormInput());
        
        $this->setWidget('preparation_vin', new bsWidgetFormChoice(array('choices' => $this->getActeursPreparationVin(), 'expanded' => true)));
        $this->setWidget('embouteillage', new bsWidgetFormChoice(array('choices' => $this->getActeursEmbouteillage(), 'expanded' => true)));
        $this->setWidget('conditionnement_crd', new bsWidgetFormChoice(array('choices' => $this->getConditionnementsCRD(), 'expanded' => true)));

        $this->setWidget('annee_contrat', new bsWidgetFormChoice(array('choices' => $anneesContrat, 'expanded' => true)));
        $this->setWidget('seuil_revision', new bsWidgetFormInput());
        $this->setWidget('acompte', new bsWidgetFormInput());
        $this->setWidget('pourcentage_variation', new bsWidgetFormInput());
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

        $this->setValidators(array(
            'delai_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getDelaiPaiement()))),
            'moyen_paiement' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getMoyenPaiement()))),
            'tva' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTva()))),
            'conditions_particulieres' => new sfValidatorString(array('required' => false)),
        	'taux_repartition' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCvoRepartition()))),
        	'date_limite_retiraison' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true)),
        	'date_debut_retiraison' => new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true)),
        	'pluriannuel' => new sfValidatorBoolean(array('required' => false)),
        	'clause_reserve_propriete' => new sfValidatorBoolean(array('required' => false)),
        	'autorisation_nom_vin' => new sfValidatorBoolean(array('required' => false)),
        	'autorisation_nom_producteur' => new sfValidatorBoolean(array('required' => false)),
        	'taux_courtage' => new sfValidatorNumber(array('required' => false)),
            'preparation_vin' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getActeursPreparationVin()))),
            'embouteillage' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getActeursEmbouteillage()))),
            'conditionnement_crd' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getConditionnementsCRD()))),
        	'annee_contrat' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($anneesContrat))),
        	'seuil_revision' => new sfValidatorNumber(array('required' => false)),
        	'acompte' => new sfValidatorNumber(array('required' => false)),
        	'pourcentage_variation' => new sfValidatorNumber(array('required' => false)),
        	'reference_contrat' => new sfValidatorString(array('required' => false)),
            'cahier_charge' => new sfValidatorBoolean(array('required' => false)),
        ));
        
        $this->useFields(VracConfiguration::getInstance()->getChamps('condition'));
        

        if (!in_array($this->getObject()->type_transaction, array(VracClient::TYPE_TRANSACTION_VIN_VRAC, VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE))) {
        	unset($this['autorisation_nom_vin'], $this['autorisation_nom_producteur']);
        }
		if ($this->getObject()->type_transaction != VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE) {
			unset($this['preparation_vin'], $this['embouteillage'], $this['conditionnement_crd']);
		}
        $this->widgetSchema->setNameFormat('vrac[%s]');
    }

    public function getDelaiPaiement() {
        return VracConfiguration::getInstance()->getDelaisPaiement();
    }

    public function getMoyenPaiement() {
        return VracConfiguration::getInstance()->getMoyensPaiement();
    }

    public function getCvoRepartition() {
        return VracConfiguration::getInstance()->getRepartitionCvo();
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
        	$this->setDefault('preparation_vin', 'VENDEUR');
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
    }

}
