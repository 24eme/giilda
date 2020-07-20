<?php

class SubventionEngagementsForm extends acCouchdbForm {

    public function __construct(acCouchdbDocument $doc, $defaults = array(), $options = array(), $CSRFSecret = null) {

        parent::__construct($doc, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('subvention_engagements[%s]');
    }

    public function save() {

        $this->getDocument()->save();
    }

}
