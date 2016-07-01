<?php

/**
 * Model for ConfigurationDetailLigne
 *
 */
class ConfigurationDetailLigne extends BaseConfigurationDetailLigne {

    const DETAILS_VRAC = 'VRAC';
    const DETAILS_EXPORT = 'EXPORT';
    const DETAILS_COOPERATIVE = 'COOPERATIVE';

    public function isReadable() {

        return ($this->readable);
    }

    public function isWritable() {

        return ($this->readable) && ($this->writable);
    }

    public function isVrac() {

        return ($this->hasDetails() && $this->getDetails() == self::DETAILS_VRAC);
    }

    public function hasDetails() {

        return ($this->exist('details') && $this->get('details'));
    }

    public function getLibelle() {
        $detail = $this->getparent()->getParent()->getKey();
        return $this->getLibelleDetail($detail)->libelle;
    }

    public function getLibelleLong() {
        $detail = $this->getparent()->getParent()->getKey();
        return $this->getLibelleDetail($detail)->libelle_long;
    }

    public function getDescription()
    {
        $detail = $this->getparent()->getParent()->getKey();
        return $this->getLibelleDetail($detail)->description;
    }

    private function getLibelleDetail() {
        $detail = $this->getparent()->getParent()->getKey();
        return $this->getDocument()->libelle_detail_ligne->get($detail)->get($this->getParent()->getKey())->get($this->getKey());
    }

    public function isFavoris() {

        return $this->getDocument()->exist("mvts_favoris/".$this->getParent()->getParent()->getKey()."_".$this->getParent()->getKey()."_".$this->getKey());
    }

    public function isWritableForEtablissement($etb) {
        if(($this->getKey() == "retourmarchandisetaxeesacquitte") || ($this->getKey() == "ventefrancebibcrdacquitte") || ($this->getKey() == "ventefrancebouteillecrdacquitte")){
            if(!$etb->exist('crd_regime')){
                return false;
            }
            if(($etb->crd_regime == EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU) || ($etb->crd_regime == EtablissementClient::REGIME_CRD_PERSONNALISE)){
                return false;
            }
        }
         if(($this->getKey() == "retourmarchandisetaxees") || ($this->getKey() == "ventefrancebibcrd") || ($this->getKey() == "ventefrancebouteillecrd")){
            if(!$etb->exist('crd_regime')){
                return true;
            }
            if(($etb->crd_regime != EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU) && ($etb->crd_regime != EtablissementClient::REGIME_CRD_PERSONNALISE)){
                return false;
            }
        }
        return true;
    }

}
