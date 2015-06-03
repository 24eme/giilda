<?php
/**
 * Model for DRMCrdsRegime
 *
 */

class DRMCrdsRegime extends BaseDRMCrdsRegime {
  public function addCrdRegimeNode($regimeNode) {
        $this->add($regimeNode);
    }
}