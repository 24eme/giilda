<?php

class CSVClient extends acCouchdbClient {

    const TYPE_DRM = "DRM";

    public static function getInstance() {
        return acCouchdbManager::getClient("CSV");
    }

    public function createOrFindDocFromDRM($path, DRM $drm) {
        $csvId = $this->buildId(self::TYPE_DRM, $drm->identifiant, $drm->periode, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT);
        $csvDrm = $this->find($csvId, $hydrate);
        if ($csvDrm) { 
            $csvDrm->storeAttachment($path, 'text/csv', $csvDrm->getFileName());
            return $csvDrm;
        }
        $csvDrm = new CSV();
        $csvDrm->_id = $csvId;
        $csvDrm->identifiant = $drm->identifiant;
        $csvDrm->periode = $drm->periode;
        $csvDrm->storeAttachment($path, 'text/csv', $csvDrm->getFileName());
        $csvDrm->save();
        return $csvDrm;
    }

    public function buildId($type_doc, $identifiant, $periode) {
        return "CSV-" . $type_doc . "-" . $identifiant . "-" . $periode;
    }

}
