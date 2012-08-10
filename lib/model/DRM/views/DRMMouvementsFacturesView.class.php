<?php

class DRMMouvementsFactureView extends acCouchdbView
{
    const KEY_FACTURE = 0;
    const KEY_FACTURABLE = 1;
    const KEY_ETABLISSEMENT_IDENTIFIANT = 2;
    const KEY_PRODUIT_HASH = 3;
    const KEY_TYPE_HASH = 4;

    const VALUE_PRODUIT_LIBELLE = 0;
    const VALUE_TYPE_LIBELLE = 1;
    const VALUE_VOLUME = 2;
    const VALUE_DETAIL_LIBELLE = 3;

    public static function getInstance() {

        return acCouchdbManager::getView('drm', 'mouvements_facture', 'DRM');
    }

}  