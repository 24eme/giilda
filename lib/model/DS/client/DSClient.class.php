<?php

class DSClient extends acCouchdbClient {

    const STATUT_VALIDE = 'valide';
    const STATUT_VALIDE_PARTIEL = 'valide_partiel'; 
    const STATUT_A_SAISIR = 'a_saisir'; 
    
    public static function getInstance() {
        return acCouchdbManager::getClient("DS");
    }

    public function buildId($identifiant, $periode) {
        return sprintf('DS-%s-%s', $identifiant, $periode);
    }

    public function buildDate($periode) {
        
        return sprintf('%4d-%02d-%02d', $this->getAnnee($periode), $this->getMois($periode), date("t",$this->getMois($periode)));
    }

    public function buildCampagne($periode) {
      
      return ConfigurationClient::getInstance()->buildCampagne($this->buildDate($periode));
    }

    public function buildPeriode($annee, $mois) {

      return sprintf("%04d-%02d", $annee, $mois);
    }

    public function getAnnee($periode) {

      return preg_replace('/([0-9]{4})-([0-9]{2})/', '$1', $periode);
    }

    public function getMois($periode) {

      return preg_replace('/([0-9]{4})-([0-9]{2})/', '$2', $periode);
    }

    public function createDsByEtb($etablissement, $periode) {
        $ds = new DS();
        $ds->date_emission = date('Y-m-d');
        $ds->periode = $periode;
        $ds->campagne = $this->buildCampagne($ds->periode);
        $ds->identifiant = $etablissement->identifiant;
        $ds->storeDeclarant();
        $ds->updateProduits();

        return $ds;
    }

    public function getHistoryByOperateur($etablissement) {

        return DSHistoryView::getInstance()->findByEtablissement($etablissement->identifiant);
    }

    public function findByIdentifiantAndPeriode($identifiant, $periode) {

        return $this->find($this->buildId($identifiant, $periode));
       
    }
    
    public function getLinkLibelleForHistory($statut)
    {
        if($statut == self::STATUT_A_SAISIR) return '> Démarrer la saisie';
        if($statut == self::STATUT_VALIDE_PARTIEL) return '> Consulter/Modifier';
        if($statut == self::STATUT_VALIDE) return '> Consulter';
        return '';
    }
    
    public function getLibelleStatutForHistory($statut)
    {
        if($statut == self::STATUT_A_SAISIR) return 'A saisir';
        if($statut == self::STATUT_VALIDE_PARTIEL) return 'A compléter';
        if($statut == self::STATUT_VALIDE) return 'Validé';
        return '';
    }
}
