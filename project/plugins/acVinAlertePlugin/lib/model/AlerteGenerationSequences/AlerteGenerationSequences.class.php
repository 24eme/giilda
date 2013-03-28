<?php
/**
 * Model for AlerteGenerationSequences
 *
 */

class AlerteGenerationSequences extends BaseAlerteGenerationSequences {
    
    protected function constructId() {
        $this->_id = AlerteGenerationSequencesClient::getInstance()->buildId($this->type_alerte);
    }
    
    public function getLastRevision() {
        return $this->revisions->getLast();
    }
}