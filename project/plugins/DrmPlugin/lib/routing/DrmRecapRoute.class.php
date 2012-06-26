<?php

class DrmRecapRoute extends DrmRoute {

    public function getConfigLieu() {
        
        return $this->getDrmLieu()->getConfig();
    }

    public function getDrmLieu() {

        return $this->getObject();
    }

    public function getConfigCertification() {

        return $this->getConfigLieu()->getCertification();
    }

    protected function getObjectForParameters($parameters) {
        $drm_certification = parent::getObjectForParameters($parameters);

        if (!array_key_exists('appellation', $parameters)) {

        	return $drm_certification->getProduits()->getFirst()->getDeclaration();
        }

        return $drm_certification->appellations->get($parameters['appellation']);
    }

    protected function doConvertObjectToArray($object) {
        if ($object->getDefinition()->getHash() == "/declaration/certifications/*/appellations/*") {
            $parameters = parent::doConvertObjectToArray($object->getCertification());
            $parameters['appellation'] = $object->getKey();
        } elseif($object->getDefinition()->getHash() == "/declaration/certifications/*") {
            $parameters = parent::doConvertObjectToArray($object);
        }

        return $parameters;
    }

}