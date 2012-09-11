<?php
class SV12MouvementsConsultationView extends MouvementsConsultationView
{
    public static function getInstance() {

        return acCouchdbManager::getView('mouvement', 'consultation', 'SV12', 'SV12MouvementsConsultationView');
    }

    public function findByEtablissement($id_or_identifiant) {
        
        return $this->findByTypeAndEtablissement('SV12', $id_or_identifiant);
    }

    public function findEtablissementAndPeriode($id_or_identifiant, $periode) {
        
        return $this->findByTypeEtablissementAndPeriode('SV12', $id_or_identifiant, $periode);
    }

    public function getMouvementsByEtablissement($id_or_identifiant) {

        return $this->buildMouvements($this->findByTypeAndEtablissement($id_or_identifiant)->rows);      
    }

    public function getMouvementsByEtablissementAndPeriode($id_or_identifiant, $periode) {
        
        return $this->buildMouvements($this->findEtablissementAndPeriode($id_or_identifiant, $periode)->rows);       
    }

}  