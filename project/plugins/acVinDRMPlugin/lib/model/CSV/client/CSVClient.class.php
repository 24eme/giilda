<?php

class CSVClient extends acCouchdbClient {

    const TYPE_DRM = "DRM";
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR = 'ERROR';

    public static $levelErrorsLibelle = array(self::LEVEL_WARNING => 'Warning', self::LEVEL_ERROR => "Error");


    public static function getInstance() {
        return acCouchdbManager::getClient("CSV");
    }

    public function findFromIdentifiantPeriode($identifiant, $periode, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

        return $this->find($this->buildId(self::TYPE_DRM, $identifiant, $periode), $hydrate);
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
