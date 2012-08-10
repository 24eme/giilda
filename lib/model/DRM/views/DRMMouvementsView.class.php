<?php

class DRMMouvementsView extends acCouchdbView
{
    const KEY_ETABLISSEMENT_IDENTIFIANT = 0;
    const KEY_CAMPAGNE = 1;
    const KEY_ID = 2;
    const KEY_PRODUIT_HASH = 3;
    const KEY_TYPE_HASH = 4;

    const VALUE_PRODUIT_LIBELLE = 0;
    const VALUE_TYPE_LIBELLE = 1;
    const VALUE_VOLUME = 2;
    const VALUE_DETAIL_LIBELLE = 3;

    public static function getInstance() {

        return acCouchdbManager::getView('drm', 'mouvements', 'DRM');
    }

    public function findByEtablissement($id_or_identifiant) {
        $identifiant = EtablissementClient::getInstance()->getIdentifiant($id_or_identifiant);

        return $this->client->startkey(array($identifiant))
                            ->endkey(array($identifiant, array()))
                            ->getView($this->design, $this->view);
    }

    public function getMouvements($id_or_identifiant) {
        $rows = $this->findByEtablissement($id_or_identifiant)->rows;

        $mouvements = array();

        foreach($rows as $row) {
            $mouvement = new stdClass();
            $mouvement->produit_libelle = $row->value[self::VALUE_PRODUIT_LIBELLE];
            $mouvement->type_libelle = $row->value[self::VALUE_TYPE_LIBELLE];
            $mouvement->volume = $row->value[self::VALUE_VOLUME];
            $mouvement->detail_libelle = $row->value[self::VALUE_DETAIL_LIBELLE];

            $mouvements[] = $mouvement;
        }

        return $mouvements;
    }

}  