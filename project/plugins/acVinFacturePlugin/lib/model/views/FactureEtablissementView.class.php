<?php

class FactureEtablissementView extends acCouchdbView
{
    const KEYS_CLIENT_ID = 1;
    const KEYS_VERSEMENT_COMPTABLE = 0;
    const KEYS_FACTURE_ID = 2;

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
                    ->startkey(array(self::VERSEMENT_TYPE_FACTURE, 0))
                    ->endkey(array(self::VERSEMENT_TYPE_FACTURE, 0, array()))
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

    public function findBySociete($societe) {
            $rows = acCouchdbManager::getClient()
                    ->startkey(array(self::VERSEMENT_TYPE_FACTURE, 0, $societe->identifiant))
                    ->endkey(array(self::VERSEMENT_TYPE_FACTURE, 0, $societe->identifiant, array()))
                    ->getView($this->design, $this->view)->rows;
            $factures = array_merge($rows, acCouchdbManager::getClient()
                    ->startkey(array(self::VERSEMENT_TYPE_FACTURE, 1, $societe->identifiant))
                    ->endkey(array(self::VERSEMENT_TYPE_FACTURE, 1, $societe->identifiant, array()))
                    ->getView($this->design, $this->view)->rows);

            $facturesResult = array();
            foreach ($factures as $facture) {
              $facturesResult[$facture->id] = $facture;
            }
            krsort($facturesResult);
            return $facturesResult;

    }

    public function getYearFaturesBySociete($societe) {
        $factures = acCouchdbManager::getClient()
                ->startkey(array(self::VERSEMENT_TYPE_FACTURE, 1, $societe->identifiant, 'FACTURE-'.$societe->identifiant.'-'.(date('Y') - 1).date('md').'00'))
                ->endkey(array(self::VERSEMENT_TYPE_FACTURE, 1, $societe->identifiant, 'FACTURE-'.$societe->identifiant.'-ZZZZZZZZZZ'))
                ->getView($this->design, $this->view)->rows;

        $facturesResult = array();
        foreach ($factures as $facture) {
          $facturesResult[$facture->id] = $facture;
        }
        return $facturesResult;
    }

}
