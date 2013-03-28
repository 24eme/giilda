<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteRelanceView
 * @author mathurin
 */
class AlerteRelanceView extends acCouchdbView {

    const KEY_IDENTIFIANT_ETB = 0;
    const KEY_STATUT = 1;
    const KEY_TYPE_RELANCE = 2;
    const KEY_TYPE_ALERTE = 3;
    const KEY_REGION = 4;
    const KEY_CAMPAGNE = 5;
    const KEY_DATE_RELANCE = 6;
    
    const VALUE_ID_DOC = 0;
    const VALUE_NOM_ETB = 1;
    const VALUE_DATE_CREATION = 2;
    const VALUE_DATE_MODIFICATION = 3;    
    const VALUE_LIBELLE_DOCUMENT = 4;
    
    public static function getInstance() {
        return acCouchdbManager::getView('alerte', 'relance', 'Alerte');
    }

    public function getRechercheByEtablissementAndStatut($id_etb,$type_alerte) {
        return acCouchdbManager::getClient()
                        ->startkey(array($id_etb,$type_alerte))
                        ->endkey(array($id_etb,$type_alerte, array()))
                        ->getView($this->design, $this->view)->rows;
    }

    public function getRechercheByEtablissementAndStatutSorted($id_etb,$type_alerte) {
        $alertesView = $this->getRechercheByEtablissementAndStatut($id_etb,$type_alerte);
        return $this->sortAlertesForRelances($alertesView);
    }
    
    public function sortAlertesForRelances($alertesView) {
        $result = array();
        foreach ($alertesView as $alerteView) {
            $type_relance = $alerteView->key[self::KEY_TYPE_RELANCE];
            $type_alerte = $alerteView->key[self::KEY_TYPE_ALERTE];
            if(!array_key_exists($type_relance, $result)){
                $result[$type_relance] = array();
            }
            if(!array_key_exists($type_alerte, $result[$type_relance])){
                $result[$type_relance][$type_alerte] = array();
            }
            $result[$type_relance][$type_alerte][] = $alerteView;
        }
        return $result;
    }
    
}

