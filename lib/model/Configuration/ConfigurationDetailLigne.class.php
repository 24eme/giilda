<?php
/**
 * Model for ConfigurationDetailLigne
 *
 */

class ConfigurationDetailLigne extends BaseConfigurationDetailLigne {

  public function isReadable() {

    return ($this->readable);
  }

  public function isWritable() {

    return ($this->readable) && ($this->writable);
  }

  public function hasDetails() {

    return ($this->details > 0);
  }

  public function isFacturable() {

    return ($this->facturable > 0);
  }

  public function getLibelle() {

  	return $this->getDocument()->libelle_detail_ligne->get($this->getParent()->getKey())->get($this->getKey());
  }
  
}