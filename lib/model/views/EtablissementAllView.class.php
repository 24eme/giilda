<?php

class EtablissementAllView extends acCouchdbView
{
	const KEY_INTERPRO_ID = 0;
	const KEY_STATUT = 1;
    const KEY_FAMILLE = 2;
    const KEY_SOCIETE_ID = 3;
    const KEY_ETABLISSEMENT_ID = 4;
	const KEY_NOM = 5;
	const KEY_IDENTIFIANT = 6;
	const KEY_CVI = 7;
    const KEY_REGION = 8;

    const VALUE_ADRESSE = 1;
	const VALUE_COMMUNE = 2;
	const VALUE_CODE_POSTAL = 3;

	public static function getInstance() {

        return acCouchdbManager::getView('etablissement', 'all', 'Etablissement');
    }

    public function findByInterpro($interpro) {

        return $this->client->startkey(array($interpro))
                            ->endkey(array($interpro, array()))
                            ->getView($this->design, $this->view);
    }

    public function findByInterproAndStatut($interpro, $statut, $filter = null) {
      return $this->findByInterproStatutAndFamilles($interpro, $statut, array(), $filter);
    }

    public function findByInterproAndFamilles($interpro, array $familles, $filter = null) {
      return $this->findByInterproStatutsAndFamilles($interpro, null, $familles, $filter);
    }

    public function findByInterproStatutAndFamilles($interpro, $statut, array $familles, $filter = null) {
      return $this->findByInterproStatutsAndFamilles($interpro, array($statut), $familles, $filter);
    }

    public function findByInterproAndFamille($interpro, $famille, $filter = null) {
      return $this->findByInterproStatutsAndFamilles($interpro, array(), array($famille), $filter);
    }

    public function findByInterproStatutsAndFamilles($interpro, array $statuts, array $familles, $filter = null) {
      return $this->findByInterproStatutsAndFamillesVIEW($interpro, $statuts, $familles, $filter) ;
    }

    public function findByInterproStatutsAndFamillesVIEW($interpro, array $statuts, array $familles, $filter = null) {
        $etablissements = array();

	if(!count($statuts)) {
	  $statuts = array_keys(EtablissementClient::$statuts);
	}

	if (count($familles)) {
	  foreach($statuts as $statut) {
	    foreach($familles as $famille) {
	      $etablissements = array_merge($etablissements, $this->findByInterproStatutAndFamille($interpro, $statut, $famille, $filter));
	    }
	  }
	}else{
	  foreach($statuts as $statut) {
	      $etablissements = array_merge($etablissements, $this->findByInterproStatutAndFamille($interpro, $statut, null, $filter));
	  }
	}

        return $etablissements;
    }    

    public function findByInterproStatutAndFamille($interpro, $statut, $famille, $filter = null) {
      return $this->findByInterproStatutAndFamilleVIEW($interpro, $statut, $famille, $filter);
    }

    public function findByInterproStatutAndFamilleVIEW($interpro, $statut, $famille, $filter = null) {
      $keys = array($interpro, $statut);
      if ($famille) {
	$keys[] = $famille;
      }
      $view = $this->client->startkey($keys);
      $keys[] = array();
      $view = $view->endkey($keys);
      $rows = $view->getView($this->design, $this->view)->rows;
      print_r($rows);
      exit;
      return $rows;
    }

    public function findByEtablissement($identifiant) {
        $etablissement = $this->client->find($identifiant, acCouchdbClient::HYDRATE_JSON);

        if(!$etablissement) {
            return null;
        }

        return $this->client->startkey(array($etablissement->interpro, $etablissement->statut, $etablissement->famille, $etablissement->id_societe, $etablissement->_id))
                            ->endkey(array($etablissement->interpro, $etablissement->statut, $etablissement->famille, $etablissement->id_societe,$etablissement->_id, array()))
                            ->getView($this->design, $this->view)->rows;
        
    }

    public static function makeLibelle($row) {
        $libelle = '';

        if ($nom = $row->key[self::KEY_NOM]) {
            $libelle .= $nom;
        }

        $libelle .= ' ('.$row->key[self::KEY_IDENTIFIANT];

        if (isset($row->key[self::KEY_CVI]) && $cvi = $row->key[self::KEY_CVI]) {
            $libelle .= ' / '.$cvi;
        }
        $libelle .= ') ';

    	if (isset($row->key[self::KEY_FAMILLE]))
    	  	$libelle .= $row->key[self::KEY_FAMILLE];

    	if (isset($row->value[self::VALUE_COMMUNE]))
    	  	$libelle .= ' '.$row->value[self::VALUE_COMMUNE];

    	if (isset($row->value[self::VALUE_CODE_POSTAL]))
    	  	$libelle .= ' '.$row->value[self::VALUE_CODE_POSTAL];

        $libelle .= " (Etablissement)";
        
        return trim($libelle);
    }

}  