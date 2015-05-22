<?php

class RelanceClient extends acCouchdbClient {

    const TYPE_RELANCE_DRM_MANQUANTE = 'DRM_MANQUANTE';
    const TYPE_RELANCE_DRA_MANQUANTE = 'DRA_MANQUANTE';
    const TYPE_RELANCE_DRM_MANQUANTE_AR = 'DRM_MANQUANTE_AR';
    const TYPE_RELANCE_DRA_MANQUANTE_AR = 'DRA_MANQUANTE_AR';

    public static function getInstance() {

        return acCouchdbManager::getClient("Relance");
    }

    public static $relances_types_libelles = array(self::TYPE_RELANCE_DRM_MANQUANTE => "DRM manquante(s)",
        self::TYPE_RELANCE_DRA_MANQUANTE => "DRA manquante(s)",
        self::TYPE_RELANCE_DRM_MANQUANTE_AR => "DRM manquante(s) AR",
        self::TYPE_RELANCE_DRA_MANQUANTE_AR => "DRA manquante(s) AR");

    public function buildId($idEtb, $typeRelance, $date) {
        return sprintf('RELANCE-%s-%s-%s', $idEtb, $typeRelance, $date);
    }

    public function findByIdentifiantTypeAndRef($identifiant, $typeRelance, $ref) {
        return $this->find($this->buildId($identifiant, $typeRelance, $ref));
    }

    public function createDoc($relance_type, $alertes, $etablissement, $date_relance = null) {

        $relance = new Relance();
        $relance->storeDatesCampagne($date_relance);
        $relance->constructIds($relance_type, $etablissement);
        $relance->storeEmetteur();
        $relance->storeDeclarant();
        $relance->storeVerifications($alertes);
        return $relance;
    }

    public function getNextRef($idEtb, $typeRelance, $date) {
        $id = '';
        $date = str_replace('-', '', $date);
        $relances = self::getAtDate($idEtb, $typeRelance, $date, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($relances) > 0) {
            $id .= ((double) str_replace('RELANCE-' . $idEtb . '-' . $typeRelance . '-', '', max($relances)) + 1);
        } else {
            $id.= $date . '01';
        }
        return $id;
    }

    public function createRelancesByEtb($alertes_relances, $etablissement) {
        $generation = new Generation();
        $generation->date_emission = date('Y-m-d-H:i');
        $generation->type_document = GenerationClient::TYPE_DOCUMENT_RELANCE;
        $generation->documents = array();
        $generation->somme = 0;
        $cpt = 0;
        foreach ($alertes_relances as $type_relance => $alertes_relances_type) {
            $relance = $this->createDoc($type_relance, $alertes_relances_type, $etablissement);
            $relance->save();
            $generation->add('documents')->add($cpt, $relance->_id);
            $cpt++;
        }

        return $generation;
    }

    public function getAtDate($idClient, $typeRelance, $date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('RELANCE-' . $idClient . '-' . $typeRelance . '-' . $date . '00')->endkey('RELANCE-' . $idClient . '-' . $typeRelance . '-' . $date . '99')->execute($hydrate);
    }

    public function getRelancesTypesFromIds($relanceGenIds) {
        $result = array();
        foreach ($relanceGenIds as $relanceGenId) {
            if (count($result) == 2)
                return $result;
            $relanceType = $this->getRelanceTypeFromId($relanceGenId);
            if (!array_key_exists($relanceType, $result))
                $result[$relanceType] = self::$relances_types_libelles[$relanceType];
        }
        return $result;
    }

    private function getRelanceTypeFromId($relanceGenId) {
        if (preg_match('/^RELANCE-([0-9]{8})-([_A-Z]+)-([0-9]{10})$/', $relanceGenId, $id)) {
            $type = $id[2];
        }
        if (!in_array($type, array_keys(self::$relances_types_libelles)))
            throw new sfException("La relance d'id $relanceGenId possède un type invalide.");
        return $type;
    }

}
