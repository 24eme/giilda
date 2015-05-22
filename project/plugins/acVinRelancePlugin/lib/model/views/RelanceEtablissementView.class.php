<?php

class RelanceEtablissementView extends acCouchdbView
{
    const KEY_IDENTIFIANT = 0;
    const KEY_TYPE_RELANCE = 1;
    const KEY_REFERENCE = 2;
    const KEY_DATE_CREATION = 3;
    
    const VALUE_ORIGINES = 0;
    

    public static function getInstance() {
        return acCouchdbManager::getView('relance', 'etablissement', 'Relance');
    }
    
    

    public function findByEtablissement($etablissement) {  
            return acCouchdbManager::getClient()
                    ->startkey(array($etablissement->identifiant))
                    ->endkey(array($etablissement->identifiant, array()))
                    ->getView($this->design, $this->view)->rows;
            
    }
    
}  
