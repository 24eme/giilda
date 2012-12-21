<?php

class SocieteAllView extends acCouchdbView
{
	const KEY_INTERPRO_ID = 0;
	const KEY_STATUT = 1;
	const KEY_TYPESOCIETE = 2;
	const KEY_ID = 3;
	const KEY_RAISON_SOCIALE = 4;
	const KEY_IDENTIFIANT = 5;
	const KEY_SIRET = 6;
	const KEY_COMMUNE = 7;
	const KEY_CODE_POSTAL = 8;

    public static function getInstance() {
        return acCouchdbManager::getView('societe', 'all', 'Societe');
    }

    public function findByInterpro($interpro, $statut, $typesocietes = array()) {
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

    public function findByRaisonSociale($raison_sociale) {
        $interpro = 'INTERPRO-inter-loire';
        return $this->client->startkey(array($interpro, $raison_sociale))
                            ->endkey(array($interpro,  $raison_sociale, array()))
                            ->getView($this->design, $this->view)->rows;
        
    }

    public function findByRaisonSocialeAndId($raison_sociale,$id) {
        $interpro = 'INTERPRO-inter-loire';
        return $this->client->startkey(array($interpro, $raison_sociale, $id))
                            ->endkey(array($interpro,  $raison_sociale, $id, array()))
                            ->getView($this->design, $this->view)->rows;
        
    }

    public static function makeLibelle($datas) {
        $libelle = '';
        
        if (isset($datas[self::KEY_RAISON_SOCIALE]) && $rs = $datas[self::KEY_RAISON_SOCIALE]) {
            if ($libelle) {
                $libelle .= ' / ';
            }
            $libelle .= $rs;
        }
        $libelle .= ' '.$datas[self::KEY_IDENTIFIANT];
        if (isset($datas[self::KEY_SIRET]) && $siret = $datas[self::KEY_SIRET]) {
            $libelle .= ' / '.$siret;
        }
        $libelle .= ' ';

    	if (isset($datas[self::KEY_COMMUNE]) && $commune = $datas[self::KEY_COMMUNE])
    	  	$libelle .= ' / '.$commune;

    	if (isset($datas[self::KEY_CODE_POSTAL]) && $code_postal = $datas[self::KEY_CODE_POSTAL])
    	  	$libelle .= ' / '.$code_postal;
        $libelle .= ' (Société)';
        return trim($libelle);
    }

}  