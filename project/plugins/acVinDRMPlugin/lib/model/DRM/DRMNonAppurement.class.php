<?php

/**
 * Model for DRMNonAppurement
 *
 */
class DRMNonAppurement extends BaseDRMNonAppurement {

    public function addEmptyNonAppurement() {
        $this->add(uniqid());
    }

}
