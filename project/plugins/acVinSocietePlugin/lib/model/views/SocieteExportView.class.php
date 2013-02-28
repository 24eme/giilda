<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class newPHPClass
 * @author mathurin
 */
class SocieteExportView extends acCouchdbView
{
	const KEY_INTERPRO_ID = 0;
	const KEY_STATUT = 1;
	const KEY_TYPESOCIETE = 2;
	const KEY_ID = 3;
	const KEY_IDENTIFIANT = 4;
        
	const VALUE_CODE_COMPTABLE_CLIENT = 0;
        const VALUE_CODE_COMPTABLE_FOURNISSEUR = 1;
        const VALUE_RAISON_SOCIALE = 2;        
        const VALUE_RAISON_SOCIALE_ABREGEE = 3;
        const VALUE_COOPERATIVE = 4;
        const VALUE_SIRET = 5;
        const VALUE_CODE_NAF = 6;
        const VALUE_NO_TVA_INTRACOM = 7;
        const VALUE_ENSEIGNES = 8;
        const VALUE_ADRESSE = 9;
        const VALUE_CODE_POSTAL = 10;
        const VALUE_COMMUNE = 11;
        const VALUE_PAYS = 12;

    public static function getInstance() {
        return acCouchdbManager::getView('societe', 'export', 'Societe');
    }

    public function findByInterpro($interpro) {
      return $this->client->startkey(array($interpro))
	->endkey(array($interpro, array()))
	->getView($this->design, $this->view)->rows;
      }

    public function findByInterproAndStatut($interpro, $statut, $typesocietes = array()) {
	return $this->findByInterproAndStatutVIEW($interpro, $statut, $typesocietes);
    }

    private function findByInterproAndStatutVIEW($interpro, $statut, $typesocietes = array()) {
      if (!count($typesocietes)) {
	if ($statut) {
	  return $this->client->startkey(array($interpro, $statut))
	    ->endkey(array($interpro, $statut, array()))
	    ->getView($this->design, $this->view)->rows;
	}
	return $this->client->startkey(array($interpro))
	  ->endkey(array($interpro, array()))
	  ->getView($this->design, $this->view)->rows;
      }
      $societes = array();
      foreach($typesocietes as $ts) {
	$societes = array_merge($societes, $this->client->startkey(array($interpro, $statut, $ts))
				->endkey(array($interpro, $statut, $ts, array()))
				->getView($this->design, $this->view)->rows);
      }
      return $societes;
    }
}  