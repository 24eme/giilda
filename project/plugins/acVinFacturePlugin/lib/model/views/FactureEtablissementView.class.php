<?php

class FactureEtablissementView extends acCouchdbView
{

    const KEYS_VERSEMENT_COMPTABLE = 1;
    const KEYS_CLIENT_ID = 2;
    const KEYS_INTERPRO = 3;
    const KEYS_FACTURE_ID = 4;

    const VALUE_DATE_FACTURATION = 0;
    const VALUE_ORIGINES = 1;
    const VALUE_TOTAL_TTC = 2;
    const VALUE_STATUT = 3;
    const VALUE_NUMERO_ARCHIVE = 4;
    const VALUE_NUMERO_IL = 5;
    const VALUE_TOTAL_HT = 6;
    const VALUE_DECLARANT = 7;
    const VALUE_DATE_PAIEMENT = 8;

    const VERSEMENT_TYPE_FACTURE = "FACTURE";
    const VERSEMENT_TYPE_PAIEMENT = "PAIEMENT";
    const VERSEMENT_TYPE_SEPA = "SEPA";
    const VERSEMENT_TYPE_PAYE = "PAYE";

    public static function getInstance() {

        return acCouchdbManager::getView('facture', 'etablissement', 'Facture');
    }


    public function getFactureNonVerseeEnCompta() {

       return acCouchdbManager::getClient()
                    ->startkey(array(self::VERSEMENT_TYPE_FACTURE, 0))
                    ->endkey(array(self::VERSEMENT_TYPE_FACTURE, 0, array()))
                    ->getView($this->design, $this->view)->rows;
    }

    public function getAllFacturesForCompta() {

       return acCouchdbManager::getClient()
                    ->startkey(array(self::VERSEMENT_TYPE_FACTURE))
                    ->endkey(array(self::VERSEMENT_TYPE_FACTURE, array()))
                    ->getView($this->design, $this->view)->rows;
    }

    public function getPaiementNonVerseeEnCompta() {

        return acCouchdbManager::getClient()
                ->startkey(array(self::VERSEMENT_TYPE_PAIEMENT, 0))
                ->endkey(array(self::VERSEMENT_TYPE_PAIEMENT, 0, array()))
                ->getView($this->design, $this->view)->rows;
    }

    public function getPaiementNonExecuteSepa() {

        return acCouchdbManager::getClient()
                ->startkey(array(self::VERSEMENT_TYPE_SEPA, 0))
                ->endkey(array(self::VERSEMENT_TYPE_SEPA, 0, array()))
                ->getView($this->design, $this->view)->rows;
    }

    public function getFactureNonPaye() {

       return acCouchdbManager::getClient()
                    ->startkey(array(self::VERSEMENT_TYPE_PAYE, 0))
                    ->endkey(array(self::VERSEMENT_TYPE_PAYE, 0, array()))
                    ->getView($this->design, $this->view)->rows;
    }

    public function getAllSocietesForCompta() {
        $items = $this->getAllFacturesForCompta();
        $societes = array();
        foreach($items as $item) {
            $societes[$item->key[self::KEYS_CLIENT_ID]] = (object) array('id' => 'SOCIETE-'.$item->key[self::KEYS_CLIENT_ID]);
        }
        return $societes;
    }

    public function findBySociete($societe, $interpro = null) {
            if ($interpro) {
                $rows = acCouchdbManager::getClient()
                        ->startkey(array(self::VERSEMENT_TYPE_FACTURE, 0, $societe->identifiant, $interpro))
                        ->endkey(array(self::VERSEMENT_TYPE_FACTURE, 0, $societe->identifiant, $interpro, array()))
                        ->getView($this->design, $this->view)->rows;
                $factures = array_merge($rows, acCouchdbManager::getClient()
                        ->startkey(array(self::VERSEMENT_TYPE_FACTURE, 1, $societe->identifiant, $interpro))
                        ->endkey(array(self::VERSEMENT_TYPE_FACTURE, 1, $societe->identifiant, $interpro, array()))
                        ->getView($this->design, $this->view)->rows);
            } else {
                $rows = acCouchdbManager::getClient()
                        ->startkey(array(self::VERSEMENT_TYPE_FACTURE, 0, $societe->identifiant))
                        ->endkey(array(self::VERSEMENT_TYPE_FACTURE, 0, $societe->identifiant, array()))
                        ->getView($this->design, $this->view)->rows;
                $factures = array_merge($rows, acCouchdbManager::getClient()
                        ->startkey(array(self::VERSEMENT_TYPE_FACTURE, 1, $societe->identifiant))
                        ->endkey(array(self::VERSEMENT_TYPE_FACTURE, 1, $societe->identifiant, array()))
                        ->getView($this->design, $this->view)->rows);
            }

            $facturesResult = array();
            foreach ($factures as $facture) {
              $facturesResult[$facture->id] = $facture;
            }
            krsort($facturesResult);
            return $facturesResult;

    }

    public function getYearFaturesBySociete($societe, $interpro = null) {
        $factures = acCouchdbManager::getClient()
                ->startkey(array(self::VERSEMENT_TYPE_FACTURE, 1, $societe->identifiant, $interpro, 'FACTURE-'.$societe->identifiant.'-'.(date('Y') - 1).date('md').'00'))
                ->endkey(array(self::VERSEMENT_TYPE_FACTURE, 1, $societe->identifiant, $interpro, 'FACTURE-'.$societe->identifiant.'-ZZZZZZZZZZ'))
                ->getView($this->design, $this->view)->rows;

        $facturesResult = array();
        foreach ($factures as $facture) {
          $facturesResult[$facture->id] = $facture;
        }
        return $facturesResult;
    }

}
