<?php

class AlertesDateForm extends acCouchdbObjectForm {


    public function __construct($alerte_date, $options = array(), $CSRFSecret = null) {
        parent::__construct($alerte_date, $options, $CSRFSecret);
        if(!$alerte_date->date) $this->defaults['date'] = date('Y-m-d');
    }

    public function configure() {
        parent::configure();
         $this->setWidget('date', new bsWidgetFormInput());

        $this->widgetSchema->setLabel('date', 'Date :');

        $this->setValidator('date', new sfValidatorString(array('required' => false)));

        $this->widgetSchema->setNameFormat('alerteDate[%s]');
    }

}
