<?php

/**
 * Model for DRMNonAppurement
 *
 */
class DRMNonAppurement extends BaseDRMNonAppurement {

    public function addEmptyNonAppurement() {
        $this->add(uniqid());
    }

    public function updateNonAppurement($key,$numero_document, $date_emission, $numero_accise) {
        if($key != $numero_document){
            $this->remove($key);
        }
        $nonAppurementNode = $this->getOrAdd($numero_document);
        $nonAppurementNode->numero_document = $numero_document;
        $nonAppurementNode->date_emission = $date_emission;
        $nonAppurementNode->numero_accise = $numero_accise;
    }

}
