<?php

class AlerteClient extends acCouchdbClient {

    const DRM_MANQUANTE = "DRM_MANQUANTE";
    const DRA_MANQUANTE = "DRA_MANQUANTE";
//    const VRAC_NON_SOLDES = "VRAC_NON_SOLDE";
//    const VRAC_PRIX_DEFINITIFS = "VRAC_PRIX_DEFINITIFS";
//    const VRAC_ATTENTE_ORIGINAL = "VRAC_ATTENTE_ORIGINAL";
//    const DRM_STOCK_NEGATIF = "DRM_STOCK_NEGATIF";
//    const VRAC_SANS_SV12 = "VRAC_SANS_SV12";
//    const SV12_MANQUANTE = "SV12_MANQUANTE";
//    const DS_NON_VALIDEE = "DS_NON_VALIDEE";
//    const ECART_DS_DRM_JUILLET = "ECART_DS_DRM_JUILLET";
//    const ECART_DS_DRM_AOUT = "ECART_DS_DRM_AOUT";
//    const ECART_DREV_DRM = "ECART_DREV_DRM";
//    const ECART_DS_VISU_STOCK = "ECART_DS_VISU_STOCK";

    public static $alertes_libelles = array(
        self::DRM_MANQUANTE => 'DRM absente',
        self::DRA_MANQUANTE => 'DRA absente',
//        self::VRAC_NON_SOLDES => "Contrat non soldé",
//        self::VRAC_PRIX_DEFINITIFS => "Contrat avec prix définitif non fixé",
//        self::VRAC_ATTENTE_ORIGINAL => "Contrat en attente de l'original",
//        self::DRM_STOCK_NEGATIF => 'DRM avec un stock négatif',
//        self::VRAC_SANS_SV12 => 'Contrats de raisins et de moûts sans SV12',
//        self::SV12_MANQUANTE => 'SV12 absente',
//        self::DS_NON_VALIDEE => 'DS non validée intégralement',
//        self::ECART_DS_DRM_JUILLET => 'Ecart entre ds et drm de juillet',
//        self::ECART_DS_DRM_AOUT => 'Ecart entre ds et drm d\'aout',
//        self::ECART_DREV_DRM => 'Ecart entre la déclaration de revendication et dernière drm',
//        self::ECART_DS_VISU_STOCK => "Ecart entre la DS et la visu Stock"
            );

    const STATUT_NOUVEAU = 'NOUVEAU';
    const STATUT_A_RELANCER = 'A_RELANCER';
    const STATUT_EN_ATTENTE_GENERATION_RELANCE = 'EN_ATTENTE_GENERATION_RELANCE';
    const STATUT_EN_ATTENTE_REPONSE = 'EN_ATTENTE_REPONSE';
    const STATUT_A_RELANCER_AR = 'A_RELANCER_AR';
    const STATUT_EN_ATTENTE_REPONSE_AR = 'EN_ATTENTE_REPONSE_AR';
    const STATUT_FERME = 'FERME';
    const STATUT_EN_SOMMEIL = 'EN_SOMMEIL';
    const MESSAGE_AUTO_FERME = "Changement automatique au statut fermé";
    const MESSAGE_AUTO_RELANCE = "Changement automatique au statut à relancer, le premier courrier est en attente de génération";
    const MESSAGE_AUTO_RELANCE_AR = "Changement automatique au statut à relancer AR, le courrier AR est en attente de génération";
    const MESSAGE_AUTO_EN_ATTENTE = "Changement automatique au statut en attente, le pdf a été généré";

    public static $statutsOpen = array(
        self::STATUT_NOUVEAU,
        self::STATUT_A_RELANCER,
        self::STATUT_EN_ATTENTE_REPONSE,
        self::STATUT_A_RELANCER_AR,
        self::STATUT_EN_ATTENTE_REPONSE_AR);
    

    //public static $statutsRelancable =    array(self::STATUT_NOUVEAU,self::STATUT_EN_ATTENTE_REPONSE,self::STATUT_A_TRAITER,self::STATUT_A_RELANCER);


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
        return array(
            self::STATUT_NOUVEAU => 'Nouveau',
            self::STATUT_EN_SOMMEIL => 'En sommeil',
            
            self::STATUT_A_RELANCER => 'A relancer',
            self::STATUT_EN_ATTENTE_REPONSE => 'En attente de réponse',
            
            self::STATUT_A_RELANCER_AR => 'A relancer AR',
            self::STATUT_EN_ATTENTE_REPONSE_AR => 'En attente de réponse AR',
            
            self::STATUT_FERME => 'Fermée'
            );
    }

    public function updateStatutByAlerteId($new_statut, $new_commentaire, $alerteId) {
        $alerte = $this->find($alerteId);
        $alerte->updateStatut($new_statut, $new_commentaire);
        $alerte->save();
    }

    public function getLibelleFromId($id) {
        return $id;
    }

}
