<?php
/**
 * Model for DRMFavoris
 *
 */

class DRMFavoris extends BaseDRMFavoris {

    
    public function getOrAddFavori($key,$value) {
        $favori = $this->add($key,$value);
    }
}