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

    public function isVrac() {

        return ($this->vrac > 0);
    }

    public function hasDetails() {

        return ($this->details > 0);
    }

    public function getLibelle() {

        return $this->getLibelleDetail()->libelle;
    }

    public function getLibelleLong() {

        return $this->getLibelleDetail()->libelle_long;
    }

    public function getDescription() {

        return $this->getLibelleDetail()->description;
    }

    private function getLibelleDetail() {
        
        return $this->getDocument()->libelle_detail_ligne->get($this->getParent()->getKey())->get($this->getKey());
    }

}
