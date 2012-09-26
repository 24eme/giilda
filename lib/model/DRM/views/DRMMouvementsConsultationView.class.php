<?php
class DRMMouvementsConsultationView extends MouvementsConsultationView
{
    public static function getInstance() {

        return acCouchdbManager::getView('mouvement', 'consultation', 'DRM', 'DRMMouvementsConsultationView');
    }

    public function findByEtablissement($id_or_identifiant) {
        
        return $this->findByTypeAndEtablissement('DRM', $id_or_identifiant);
    }

    public function findByEtablissementAndPeriode($id_or_identifiant, $periode) {
        
        return $this->findByTypeEtablissementAndPeriode('DRM', $id_or_identifiant, DRMClient::getInstance()->buildCampagne($periode), $periode);
    }

    public function getMouvementsByEtablissement($id_or_identifiant) {

        return $this->buildMouvements($this->findByEtablissement($id_or_identifiant)->rows);      
    }

    public function getMouvementsByEtablissementAndPeriode($id_or_identifiant, $periode) {
        
        return $this->buildMouvements($this->findByEtablissementAndPeriode($id_or_identifiant, $periode)->rows);       
    }

}  