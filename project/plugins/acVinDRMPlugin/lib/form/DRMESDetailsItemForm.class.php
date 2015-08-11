<?php

abstract class DRMESDetailsItemForm extends acCouchdbObjectForm {

    protected $isTeledeclarationMode = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        $this->isTeledeclarationMode = $options['isTeledeclarationMode'];
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {

        $this->setWidget('identifiant', new sfWidgetFormChoice(array('choices' => $this->getIdentifiantChoices()), array('class' => 'autocomplete')));
        $this->setWidget('volume', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off', 'class' => 'num num_float')));

        if ($this->isTeledeclarationMode) {
            $this->setWidget('numero_document', new sfWidgetFormInput());
            $this->setWidget('type_document', new sfWidgetFormChoice(array('choices' => $this->getTypeDocumentsChoices())));
        }

        $this->setValidator('identifiant', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getIdentifiantChoices()))));
        $this->setValidator('volume', new sfValidatorNumber(array('required' => false, 'min' => 0), array('min' => "La saisie d'un nombre négatif est interdite")));
        if ($this->isTeledeclarationMode) {
            $this->setValidator('numero_document', new sfValidatorString(array('required' => false)));
            $this->setValidator('type_document', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypeDocumentsChoices()))));
        }



        $post_validator_class = $this->getPostValidatorClass();
        $this->validatorSchema->setPostValidator(new $post_validator_class());
        $this->widgetSchema->setNameFormat(sprintf("%s[%%s]", $this->getFormName()));
//        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
//        if(!$this->getObject()->date_enlevement) $this->setDefault('date_enlevement', $this->getObject()->getDocument()->getDate());
//
//        $date = new DateTime($this->getDefault('date_enlevement'));
//        $this->setDefault('date_enlevement', $date->format('d/m/Y'));
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $date = new DateTime($this->getObject()->getDocument()->getDate());
        $this->getObject()->date_enlevement = $date->format('Y-m-d');
    }

    public function getProduitDetail() {

        return $this->getObject()->getProduitDetail();
    }

    public function getTypeDocumentsChoices() {
        return DRMClient::$drm_documents_daccompagnement;
    }

    public abstract function getFormName();

    public abstract function getIdentifiantChoices();

    public function getPostValidatorClass() {

        return 'DRMDetailItemValidator';
    }

}
