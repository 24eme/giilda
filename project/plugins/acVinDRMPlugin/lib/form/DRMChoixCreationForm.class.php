<?php

class DRMChoixCreationForm extends sfForm {

    private $periode = null;
    private $identifiant = null;

    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->periode = $options['periode'];
        $this->identifiant = $options['identifiant'];
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidgets(array(
            'type_creation' => new sfWidgetFormChoice(array('choices' => $this->getTypesCreation()))
        ));
 $this->widgetSchema->setLabels(array(
            'type_creation' => 'Type de création : '
        ));
        $this->setValidators(array(
            'type_creation' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getTypesCreation())))
        ));

        $this->widgetSchema->setNameFormat('drmChoixCreation[%s]');
    }

    public function getTypesCreation() {
        return DRMClient::$typesCreationLibelles;
    }

}
