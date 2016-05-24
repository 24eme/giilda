<?php

class FactureSocieteChoiceForm extends SocieteChoiceForm {

    public function __construct($interpro_id, $defaults = array(), $options = array(), $CSRFSecret = null) {
        if (!count($options)) {
            $options['type_societe'] = array(SocieteClient::TYPE_OPERATEUR, SocieteClient::TYPE_AUTRE);
        }
        parent::__construct($interpro_id, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();
    }

}
