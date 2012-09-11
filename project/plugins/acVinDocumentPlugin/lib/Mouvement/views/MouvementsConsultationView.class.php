<?php

class MouvementsConsultationView extends acCouchdbView
{
    const KEY_TYPE = 0;
    const KEY_ETABLISSEMENT_IDENTIFIANT = 1;
    const KEY_CAMPAGNE = 2;
    const KEY_ID = 3;
    const KEY_PRODUIT_HASH = 4;
    const KEY_TYPE_HASH = 5;

    const VALUE_PRODUIT_LIBELLE = 0;
    const VALUE_TYPE_LIBELLE = 1;
    const VALUE_VOLUME = 2;
    const VALUE_DETAIL_LIBELLE = 3;
    const VALUE_DATE_VERSION = 4;
    const VALUE_VERSION = 5;

    public static function getInstance() {

        return acCouchdbManager::getView('mouvement', 'consultation');
    }

    public function findByTypeAndEtablissement($type, $id_or_identifiant) {
        $identifiant = EtablissementClient::getInstance()->getIdentifiant($id_or_identifiant);

        return $this->client->startkey(array($type, $identifiant))
                            ->endkey(array($type, $identifiant, array()))
                            ->getView($this->design, $this->view);
    }



    public function findByTypeEtablissementAndPeriode($type, $id_or_identifiant, $periode) {
        $identifiant = EtablissementClient::getInstance()->getIdentifiant($id_or_identifiant);
        return $this->client->startkey(array($type, $identifiant, DRMClient::getInstance()->buildCampagne($periode), $periode))
                            ->endkey(array($type, $identifiant, DRMClient::getInstance()->buildCampagne($periode), $periode, array()))
                            ->getView($this->design, $this->view);
    }

    protected function buildMouvements($rows) {
        $mouvements = array();
        foreach($rows as $row) {
            $mouvements[] = $this->buildMouvement($row);
        }

        return $mouvements;
    }

    protected function buildMouvement($row) {
        $mouvement = new stdClass();
        $mouvement->produit_libelle = $row->value[self::VALUE_PRODUIT_LIBELLE];
        $mouvement->type_libelle = $row->value[self::VALUE_TYPE_LIBELLE];
        $mouvement->volume = $row->value[self::VALUE_VOLUME];
        $mouvement->detail_libelle = $row->value[self::VALUE_DETAIL_LIBELLE];        
        $mouvement->date_version =  $row->value[self::VALUE_DATE_VERSION];
        $mouvement->version = $row->value[self::VALUE_VERSION];
        return $mouvement;
    }

}  