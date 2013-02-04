<?php

class AlerteClient extends acCouchdbClient {

    public static $date = '2013-03-21';
    
    const VRAC_NON_SOLDES = "VRAC_NON_SOLDE";
    const VRAC_PRIX_DEFINITIFS = "VRAC_PRIX_DEFINITIFS";
    const VRAC_ATTENTE_ORIGINAL = "VRAC_ATTENTE_ORIGINAL";
    const DRM_MANQUANTE = "DRM_MANQUANTE";
    const SV12_SANS_VRAC = "SV12_SANS_VRAC";
    
    public static $alertes_libelles = array(self::VRAC_NON_SOLDES => "Contrat non soldé",
                                            self::VRAC_PRIX_DEFINITIFS => "Contrat avec prix définitif non fixé",
                                            self::VRAC_ATTENTE_ORIGINAL => "Contrat en attente de l'original",
                                            self::DRM_MANQUANTE => 'DRM absente',
                                            self::SV12_SANS_VRAC => 'SV12 dont le contrat est absent');
    
    const STATUT_NOUVEAU = 'NOUVEAU';    
    const STATUT_EN_ATTENTE_REPONSE = 'EN_ATTENTE_REPONSE';
    const STATUT_A_TRAITER = 'A_TRAITER';
    const STATUT_FERME = 'FERME';  
    const STATUT_EN_SOMMEIL = 'EN_SOMMEIL';
    const STATUT_A_RELANCER = 'A_RELANCER';
    
    
    public static $statutsOpen =    array(self::STATUT_NOUVEAU,self::STATUT_EN_ATTENTE_REPONSE,self::STATUT_A_TRAITER,self::STATUT_EN_SOMMEIL,self::STATUT_A_RELANCER);
    public static $statutsRelancable =    array(self::STATUT_NOUVEAU,self::STATUT_EN_ATTENTE_REPONSE,self::STATUT_A_TRAITER,self::STATUT_A_RELANCER);
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
        return array(self::STATUT_NOUVEAU => 'Nouveau',
                    self::STATUT_EN_ATTENTE_REPONSE => 'En attente de réponse',
                    self::STATUT_A_TRAITER => 'A traiter', 
                    self::STATUT_FERME => 'Fermée',
                    self::STATUT_EN_SOMMEIL => 'En sommeil', 
                    self::STATUT_A_RELANCER => 'Alerte à relancer');
    }
    
    public static function getDate()
    {
        return self::$date;
    }
    
    public function updateStatutByAlerteId($new_statut,$new_commentaire,$alerteId) {
       $alerte = $this->find($alerteId);
       $alerte->updateStatut($new_statut, $new_commentaire, self::getDate());
       $alerte->save();
    }
}
