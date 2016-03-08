<?php

class FactureSocieteChoiceForm extends SocieteChoiceForm {

    public function configure() {
        parent::configure();
        $this->configureTypeSociete(array(SocieteClient::TYPE_OPERATEUR));
    }

}
