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

    public function findByInterpro($interpro) {
      return $this->client->startkey(array($interpro))
	->endkey(array($interpro, array()))
	->getView($this->design, $this->view)->rows;
      }

    public function findByInterproAndStatut($interpro, $statut, $typesocietes = array(), $q = null, $limit = null) {
       try {
				 return $this->findByInterproAndStatutELASTIC($interpro, $statut, $typesocietes, $q, $limit);
       }catch(Exception $e) {
				 return $this->findByInterproAndStatutVIEW($interpro, $statut, $typesocietes);
       }
    }

    private function findByInterproAndStatutELASTIC($interpro, $statut, $typesocietes, $q, $limit) {
      $query = array();
      foreach (explode(' ', $q) as $s) {
	$query[] = "*$q*";
      }
      if ($statut) {
	$query[] = "statut:$statut";
      }
      if (count($typesocietes)) {
	$tq = '';
	foreach($typesocietes as $ts) {
	  if ($tq) {
	    $tq .= ' OR ';
	  }
	  $tq .= 'type_societe:'.$ts;
	}
	$query[] = '('.$tq.')';
      }
      $q = implode(' ', $query);

      $index = acElasticaManager::getType('Societe');
      $elasticaQueryString = new acElasticaQueryQueryString();
      $elasticaQueryString->setDefaultOperator('AND');
      $elasticaQueryString->setQuery($q);

      // Create the actual search object with some data.
      $q = new acElasticaQuery();
     	if ($limit){
				$q->setLimit($limit);
		 	}else{
				$q->setLimit(null);
			}
			$q->setQuery($elasticaQueryString);
      //Search on the index.
      $res = $index->search($q);
      $viewres = $this->elasticRes2View($res);

      return $viewres;
    }

    private function elasticRes2View($results) {
      $res = array();
      foreach ($results->getResults() as $er) {
				$r = $er->getData();
				$e = new stdClass();
				$e->id = $r['_id'];
				$e->key = array($r['interpro'], $r['statut'], $r['type_societe'], $r['_id'], $r['raison_sociale'], $r['identifiant'], $r['siret'], $r['siege']['commune'], $r['siege']['code_postal']);
				$e->value = null;
				$res[] = $e;
      }
      return $res;
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

    public function findByTypeAndRaisonSociale($type,$raison_sociale) {
        $interpro = 'INTERPRO-inter-loire';
        return $this->client->startkey(array($interpro,  SocieteClient::STATUT_ACTIF, $type, 'SOCIETE-000000', $raison_sociale))
                            ->endkey(array($interpro, SocieteClient::STATUT_ACTIF, $type, 'SOCIETE-999999', $raison_sociale, array()))
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

        public function findBySociete($identifiant) {

        $societe = $this->client->find($identifiant, acCouchdbClient::HYDRATE_JSON);

        if(!$societe) {
            return null;
        }

        return $this->client->startkey(array($societe->interpro, $societe->statut, $societe->type_societe, $societe->_id))
                            ->endkey(array($societe->interpro, $societe->statut, $societe->type_societe, $societe->_id, array()))
			    ->getView($this->design, $this->view)->rows;

    }

}
