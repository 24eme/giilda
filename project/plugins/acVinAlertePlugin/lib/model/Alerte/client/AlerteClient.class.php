<?php

class AlerteClient extends acCouchdbClient {

    const FIRSTCAMPAGNEIMPORT = "2014-2015";
    const DRM_MANQUANTE = "DRM_MANQUANTE";
    const DRA_MANQUANTE = "DRA_MANQUANTE";

    public static $alertes_libelles = array(
        self::DRM_MANQUANTE => 'DRM absente',
        self::DRA_MANQUANTE => 'DRA absente',
    );

    const STATUT_NOUVEAU = 'NOUVEAU';
    const STATUT_A_RELANCER = 'A_RELANCER';
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
        return array_merge(self::getStatutsOperateursWithLibelles(), array(self::STATUT_EN_ATTENTE_REPONSE => 'En attente de réponse'), array(self::STATUT_EN_ATTENTE_REPONSE_AR => 'En attente de réponse AR'), array(self::STATUT_FERME => 'Fermée'));
    }

    public static function getStatutsOperateursWithLibelles() {
        return array(
            self::STATUT_NOUVEAU => 'Nouveau',
            self::STATUT_EN_SOMMEIL => 'En sommeil',
            self::STATUT_A_RELANCER => 'A relancer',
            self::STATUT_A_RELANCER_AR => 'A relancer AR'
        );
    }

    public function updateStatutByAlerteId($new_statut, $new_commentaire, $alerteId, $date_relance = null, $date_relance_ar = null) {
        $alerte = $this->find($alerteId);
        if ($date_relance) {
            $alerte->date_relance = $date_relance;
        }
        if ($date_relance_ar) {
            $alerte->date_relance_ar = $date_relance_ar;
        }
        $alerte->updateStatut($new_statut, $new_commentaire);
        $alerte->save();
    }

    public function getLibelleFromId($id) {
        return $id;
    }

}
