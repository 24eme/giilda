<?php

class AlerteClient extends acCouchdbClient {

    const VRAC_NON_SOLDES = "VRACNONSOLDE";
    const VRAC_PRIX_DEFINITIF = "VRACPRIXDEFINITIF";
    const VRAC_ATTENTE_ORIGINAL = "VRACATTENTEORIGINAL";
    const DRM_MANQUANTE = "DRMMANQUANTE";
    
    public static $alertes_libelles = array(self::VRAC_NON_SOLDES => "Contrat non soldé",
                                   self::VRAC_PRIX_DEFINITIF => "");
    
    const STATUT_NOUVEAU = 'NOUVEAU';    
    const STATUT_ENCOURS = 'ENCOURS';    
    const STATUT_RESOLU  = 'RESOLU';    
    const STATUT_FERME = 'FERME';  
    const STATUT_FINDECAMPAGNE = 'FINDECAMPAGNE';
    const STATUT_ARELANCER = 'ARELANCER';
    
    public static $statutsOpen =    array(self::STATUT_NOUVEAU,self::STATUT_ENCOURS,self::STATUT_FINDECAMPAGNE,self::STATUT_ARELANCER);
    public static $statutsClosed =    array(self::STATUT_FERME,self::STATUT_RESOLU);
    
    public static function getInstance() {
        return acCouchdbManager::getClient("Alerte");
    }

    public function buildId($type_alerte, $id_document) {
        return sprintf('ALERTE-%s-%s', $type_alerte, $id_document);
    }
    
    public static function getTypesWithLibelles() {
        return array(self::VRAC_NON_SOLDES => 'Contrats non soldés',self::VRAC_PRIX_DEFINITIF => 'Contrats avec prix définitif non fixé',
            self::VRAC_ATTENTE_ORIGINAL => "Contrats en attente de l'original", self::DRM_MANQUANTE => 'DRM absente');
    }

    public static function getStatutsWithLibelles() {
        return array(self::STATUT_NOUVEAU => 'Nouvelle alerte',self::STATUT_ENCOURS => 'Alerte en cours',
            self::STATUT_RESOLU => 'Alerte résolue', self::STATUT_FERME => 'Alerte fermée',
            self::STATUT_FINDECAMPAGNE => 'Alerte de fin de campagne', self::STATUT_ARELANCER => 'Alerte à relancer');
    }
    
}
