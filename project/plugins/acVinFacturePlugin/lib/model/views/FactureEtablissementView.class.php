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


    public static function getInstance() {

        return acCouchdbManager::getView('facture', 'etablissement', 'Facture');
    }


    public function getFactureNonVerseeEnCompta() {

       return acCouchdbManager::getClient()
                    ->startkey(array(0))
                    ->endkey(array(0, array()))
                    ->getView($this->design, $this->view)->rows;
    }

    public function getAllFacturesForCompta() {

       return acCouchdbManager::getClient()
                    ->startkey()
                    ->endkey()
                    ->getView($this->design, $this->view)->rows;
    }

    public function findBySociete($societe) {
            $rows = acCouchdbManager::getClient()
                    ->startkey(array(0, $societe->identifiant))
                    ->endkey(array(0, $societe->identifiant, array()))
                    ->getView($this->design, $this->view)->rows;
            $factures = array_merge($rows, acCouchdbManager::getClient()
                    ->startkey(array(1, $societe->identifiant))
                    ->endkey(array(1, $societe->identifiant, array()))
                    ->getView($this->design, $this->view)->rows);

            $facturesResult = array();
            foreach ($factures as $facture) {
              $facturesResult[$facture->id] = $facture;
            }
            krsort($facturesResult);
            return $facturesResult;

    }

}
