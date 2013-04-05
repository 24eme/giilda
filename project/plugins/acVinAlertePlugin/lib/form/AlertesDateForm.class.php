<?php

class AlertesDateForm extends acCouchdbObjectForm {


    public function __construct($alerte_date, $options = array(), $CSRFSecret = null) {
        parent::__construct($alerte_date, $options, $CSRFSecret);
        if(!$alerte_date->debug) $this->defaults['debug'] = 0;
        if(!$alerte_date->date) $this->defaults['date'] = date('Y-m-d');
    }

    public function configure() {
        parent::configure();
        $this->setWidget('debug', new sfWidgetFormChoice(array('choices' => $this->getDebugsChoices(), 'expanded' => true, 'multiple' => false)));
        $this->setWidget('date', new sfWidgetFormInput());

        $this->widgetSchema->setLabel('debug','Debug : ');
        $this->widgetSchema->setLabel('date', 'Date :');

        $this->setValidator('debug', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getDebugsChoices()))));
        $this->setValidator('date', new sfValidatorString(array('required' => false)));

        $this->widgetSchema->setNameFormat('alerteDate[%s]');
    }

    public function getDebugsChoices() {
        return AlerteDateClient::getDebugsChoices();
    }
}