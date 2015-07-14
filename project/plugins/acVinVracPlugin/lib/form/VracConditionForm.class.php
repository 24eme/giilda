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
        $this->setWidget('delai_paiement', new bsWidgetFormChoice(array('choices' => $this->getDelaiPaiement())));
        $this->setWidget('moyen_paiement', new bsWidgetFormChoice(array('choices' => $this->getMoyenPaiement())));
        $this->setWidget('date_limite_retiraison', new bsWidgetFormInput());
        $this->setWidget('cvo_repartition', new bsWidgetFormChoice(array('choices' => $this->getCvoRepartition())));
        $this->setWidget('conditions_particulieres', new bsWidgetFormTextarea());
        $this->setWidget('tva', new bsWidgetFormChoice(array('choices' => $this->getTva(), 'expanded' => true)));
        
        $this->setWidget('pluriannuel', new bsWidgetFormInputCheckbox());
        $this->setWidget('clause_reserve_propriete', new bsWidgetFormInputCheckbox());
        $this->setWidget('autorisation_nom_vin', new bsWidgetFormInputCheckbox());
        $this->setWidget('autorisation_nom_producteur', new bsWidgetFormInputCheckbox());

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
        	'cvo_repartition' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCvoRepartition()))),
        	'date_limite_retiraison' => new sfValidatorRegex($dateRegexpOptions, $dateRegexpErrors),
        	'pluriannuel' => new sfValidatorBoolean(array('required' => false)),
        	'clause_reserve_propriete' => new sfValidatorBoolean(array('required' => false)),
        	'autorisation_nom_vin' => new sfValidatorBoolean(array('required' => false)),
        	'autorisation_nom_producteur' => new sfValidatorBoolean(array('required' => false))
        ));
        
        $this->useFields(VracConfiguration::getInstance()->getChamps('condition'));

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

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);        
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
    }

}
