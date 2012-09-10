<?php

class FactureEtablissementView extends acCouchdbView
{
    const KEYS_CLIENT_ID = 0;
    const KEYS_FACTURE_ID = 1;
    
    const VALUE_DATE_EMISSION = 0;
    const VALUE_ORIGINES = 1;
    const VALUE_TOTAL_TTC = 2;
    const VALUE_STATUT = 3;
    

    public static function getInstance() {

        return acCouchdbManager::getView('facture', 'etablissement', 'Facture');
    }
    
    
    public function findByEtablissement($etablissement) {  
            return acCouchdbManager::getClient()
                    ->startkey(array($etablissement->_id))
                    ->endkey(array($etablissement->_id, array()))
                    ->getView($this->design, $this->view)->rows;
    }
    
}  