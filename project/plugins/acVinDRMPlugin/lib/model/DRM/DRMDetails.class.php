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
        return $this->getDocument()->getConfig()->declaration->get("detail");
    }

    public function getProduit($labels = array()) {
        $slug = $this->slugifyLabels($labels);
        if (!$this->exist($slug)) {

            return false;
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

    public function addProduit($labels = array()) {
        $detail = $this->add($this->slugifyLabels($labels));
        $detail->labels = $labels;
        $detail->code_inao = $this->getParent()->getConfig()->getCodeDouane();
        foreach ($this->getConfigDetails() as $detailConfigCat => $detailConfig) {
            foreach ($detailConfig as $detailConfigKey => $detailConfigNode) {
                $detail->getOrAdd($detailConfigCat)->getOrAdd($detailConfigKey,null);
                if($detailConfigNode->hasDetails()) {
                    $detail->getOrAdd($detailConfigCat)->getOrAdd($detailConfigKey."_details", null);
                }
            }
        }

        return $detail;
    }

    protected function slugifyLabels($labels) {

        return KeyInflector::slugify($this->getLabelKeyFromArray($labels));
    }

    protected function getLabelKeyFromArray($labels) {
        $key = null;
        if ($labels && is_array($labels) && count($labels) > 0) {
            sort($labels);
            $key = implode('-', $labels);
        }

        return ($key) ? $key : DRM::DEFAULT_KEY;
    }

}
