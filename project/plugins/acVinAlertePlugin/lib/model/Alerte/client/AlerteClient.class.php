<?php

class AlerteClient extends acCouchdbClient {

    public static $date = '2012-12-21';
    
    const VRAC_NON_SOLDES = "VRACNONSOLDE";
    const VRAC_PRIX_DEFINITIFS = "VRACPRIXDEFINITIFS";
    const VRAC_ATTENTE_ORIGINAL = "VRACATTENTEORIGINAL";
    const DRM_MANQUANTE = "DRMMANQUANTE";
    
    public static $alertes_libelles = array(self::VRAC_NON_SOLDES => "Contrat non soldé",
                                            self::VRAC_PRIX_DEFINITIFS => "Contrat avec prix définitif non fixé",
                                            self::VRAC_ATTENTE_ORIGINAL => "Contrat en attente de l'original",
                                            self::DRM_MANQUANTE => 'DRM absente');
    
    const STATUT_NOUVEAU = 'NOUVEAU';    
    const STATUT_ENATTENTEREPONSE = 'ENATTENTEREPONSE';
    const STATUT_ATRAITER = 'ATRAITER';
    const STATUT_FERME = 'FERME';  
    const STATUT_ENSOMMEIL = 'ENSOMMEIL';
    const STATUT_ARELANCER = 'ARELANCER';
    
    
    public static $statutsOpen =    array(self::STATUT_NOUVEAU,self::STATUT_ENATTENTEREPONSE,self::STATUT_ATRAITER,self::STATUT_ENSOMMEIL,self::STATUT_ARELANCER);
    public static $statutsRelancable =    array(self::STATUT_NOUVEAU,self::STATUT_ENATTENTEREPONSE,self::STATUT_ATRAITER,self::STATUT_ARELANCER);
    public static $statutsClosed =    array(self::STATUT_FERME);
    
    
    public static function getInstance() {
        return acCouchdbManager::getClient("Alerte");
    }

    public function buildId($type_alerte, $id_document) {
        return sprintf('ALERTE-%s-%s', $type_alerte, $id_document);
    }
    
    public function findByTypeAndIdDocument($type_alerte, $id_document) {
        return $this->find($this->buildId($type_alerte, $id_document));
    }

    public static function getStatutsWithLibelles() {
        return array(self::STATUT_NOUVEAU => 'Nouveau',self::STATUT_ENATTENTEREPONSE => 'En attente de réponse',
            self::STATUT_ATRAITER => 'A traiter', self::STATUT_FERME => 'Fermée',
            self::STATUT_ENSOMMEIL => 'En sommeil', self::STATUT_ARELANCER => 'Alerte à relancer');
    }
    
    public static function getDate()
    {
        return self::$date;
    }
}
