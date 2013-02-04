<?php

/**
 * Description of class AlerteHistoryView
 * @author mathurin
 */
class AlerteRechercheView extends acCouchdbView {

    const KEY_IDENTIFIANT_ETB = 0;
    const KEY_REGION = 1;
    const KEY_TYPE_ALERTE = 2;
    const KEY_STATUT = 3;
    const KEY_CAMPAGNE = 4;
    const VALUE_ID_DOC = 0;
    const VALUE_NOM_ETB = 1;
    const VALUE_DATE_CREATION = 2;
    const VALUE_DATE_MODIFICATION = 3;    
    const VALUE_LIBELLE_DOCUMENT = 4;
    
    public static function getInstance() {
        return acCouchdbManager::getView('alerte', 'recherche', 'Alerte');
    }

    public function getRechercheByEtablissement($id_etb) {
        return acCouchdbManager::getClient()
                        ->startkey(array($id_etb))
                        ->endkey(array($id_etb, array()))
                        ->getView($this->design, $this->view)->rows;
    }

}

