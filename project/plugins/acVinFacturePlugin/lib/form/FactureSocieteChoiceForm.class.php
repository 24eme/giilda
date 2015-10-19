<?php

class FactureSocieteChoiceForm extends SocieteChoiceForm {

    public function configure() {
        parent::configure();
        $this->configureTypeSociete(array(SocieteClient::SUB_TYPE_VITICULTEUR, SocieteClient::SUB_TYPE_NEGOCIANT));
    }

    public function setDefaults($defaults) {
        parent::setDefaults($defaults);
        if (array_key_exists('identifiant', $defaults)) {
//            $societe = SocieteClient::getInstance()->find($defaults['identifiant']);
            //$this->setDefault('identifiant', 'SOCIETE-' . $defaults['identifiant'] . ',' . $societe->raison_sociale . ' ' . $societe->identifiant . ' / ' . $societe->siege->commune . ' ' . $societe->siege->code_postal . ' (Société)');
        }
    }

}
