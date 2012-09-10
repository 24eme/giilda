<?php
class DRMMouvementsConsultationView extends MouvementsConsultationView
{
    public static function getInstance() {

        return acCouchdbManager::getView('mouvement', 'consultation', 'DRM', 'DRMMouvementsConsultationView');
    }

    public function findByEtablissement($id_or_identifiant) {
        
        return $this->findByTypeAndEtablissement('DRM', $id_or_identifiant);
    }

    public function findEtablissementAndPeriode($id_or_identifiant, $periode) {
        
        return $this->findByTypeEtablissementAndPeriode('DRM', $id_or_identifiant, $periode);
    }

    public function getMouvementsByEtablissement($id_or_identifiant) {

        return $this->buildMouvements($this->findByTypeAndEtablissement($id_or_identifiant)->rows);      
    }

    public function getMouvementsByEtablissementAndPeriode($id_or_identifiant, $periode) {
        
        return $this->buildMouvements($this->findEtablissementAndPeriode($id_or_identifiant, $periode)->rows);       
    }

}  