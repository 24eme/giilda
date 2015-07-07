<?php

/**
 * Model for DRMDetails
 *
 */
class DRMDetails extends BaseDRMDetails {

    public function getConfigDetails() {
        $detailConfigKey = $this->getDetailsConfigKey();
        return ConfigurationClient::getCurrent()->declaration->details->get($detailConfigKey);
    }

    public function getProduit($labels = array()) {
        $slug = $this->slugifyLabels($labels);
        if (!$this->exist($slug)) {

            return false;
        }

        return $this->get($slug);
    }

    public function addProduit($labels = array()) {
        $detail = $this->add($this->slugifyLabels($labels));
        $detail->labels = $labels;
        foreach ($this->getConfigDetails()->detail as $detailConfigCat => $detailConfig) {
            foreach ($detailConfig as $detailConfigKey => $detailConfigNode) {
                $detail->getOrAdd($detailConfigCat)->getOrAdd($detailConfigKey,null);
                if($detail->hasDetails()) {
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

    public function isProduitNonInterpro() {
        return $this->getParent()->isProduitNonInterpro();
    }

    protected function getDetailsConfigKey() {
        return $this->getDocument()->getDetailsConfigKey();
    }

}
