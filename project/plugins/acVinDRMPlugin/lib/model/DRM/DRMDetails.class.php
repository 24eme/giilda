<?php

/**
 * Model for DRMDetails
 *
 */
class DRMDetails extends BaseDRMDetails {

    public function getConfigDetails() {
        if($this->getDocument()->getConfig()->declaration->exist($this->getKey())){
          return $this->getDocument()->getConfig()->declaration->get($this->getKey());
        }
        if($this->getDocument()->getConfig()->declaration->exist('details')){
          return $this->getDocument()->getConfig()->declaration->get("details");
        }
        return $this->getDocument()->getConfig()->declaration->get("detail");
    }

    public function getProduit($denomination_complementaire = null, $tav = null) {

        $slug = DRM::DEFAULT_KEY;
        if($denomination_complementaire || $tav){
          $slug = $this->createSHA1Denom($denomination_complementaire, $tav);
        }
        if (!$this->exist($slug)) {
            return null;
        }
        return $this->get($slug);
    }

    public function getTypeDRM() {
    if($this->getKey() == DRM::DETAILS_KEY_SUSPENDU) {

        return DRMClient::TYPE_DRM_SUSPENDU;
    }

    if($this->getKey() == DRM::DETAILS_KEY_ACQUITTE) {

        return DRMClient::TYPE_DRM_ACQUITTE;
    }
}

public function getTypeDRMLibelle() {
    if($this->getKey() == DRM::DETAILS_KEY_SUSPENDU) {

        return "Suspendu";
    }

    if($this->getKey() == DRM::DETAILS_KEY_ACQUITTE) {

        return "Acquitté";
    }

    return null;
}

public function addProduit($denomination_complementaire = null, $tav = null) {
        $detailDefaultKey = DRM::DEFAULT_KEY;
        $detail = null;
        if($denomination_complementaire || $tav){
          $detail = $this->add($this->createSHA1Denom($denomination_complementaire, $tav));
          $detail->denomination_complementaire = $denomination_complementaire;
        }else{
          $detail = $this->add($detailDefaultKey);
        }
        foreach ($this->getConfigDetails() as $detailConfigCat => $detailConfig) {
            foreach ($detailConfig as $detailConfigKey => $detailConfigNode) {
                $detail->getOrAdd($detailConfigCat)->getOrAdd($detailConfigKey,null);
                if($detailConfigNode->hasDetails()) {
                    $detail->getOrAdd($detailConfigCat)->getOrAdd($detailConfigKey."_details", null);
                }
            }
        }
        if($detail->isCodeDouaneAlcool() ||  $detail->isPremix()){
              $detail->add('tav', $tav);
        }
        return $detail;
    }


    public function createSHA1Denom($denomination_complementaire, $tav = ""){
      $denomSlugified = KeyInflector::slugify($denomination_complementaire.$tav);
      $completeHash = $this->getHash().'/'.$denomSlugified;
      $sha1 = hash("sha1",$completeHash);
      return substr($sha1,0,7);
    }

}
