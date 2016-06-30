<?php

/**
 * Model for DRMNonApurement
 *
 */
class DRMNonApurement extends BaseDRMNonApurement {

    public function addEmptyNonApurement() {
        $this->add(uniqid());
    }

    public function updateNonApurement($key,$numero_document, $date_emission, $numero_accise) {
        if($key != $numero_document){
            $this->remove($key);
        }
        $nonApurementNode = $this->getOrAdd($numero_document);
        $nonApurementNode->numero_document = $numero_document;
        $nonApurementNode->date_emission = $date_emission;
        $nonApurementNode->numero_accise = $numero_accise;
    }

}
