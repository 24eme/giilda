<?php

class ComptabiliteEditionForm extends acCouchdbObjectForm {


    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
       
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {

        $this->getObject()->getOrAdd('identifiants_analytiques')->add("nouvelle");

        $this->embedForm('identifiants_analytiques', new ComptabiliteIdentifiantAnalytiqueEditionForm($this->getObject()->identifiants_analytiques));

        $this->widgetSchema->setNameFormat('comptabilite_edition[%s]');
    }

}
