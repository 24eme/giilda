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

    public function findByInterproAndStatut($interpro, $statut, $q = null, $limit = null) {
      try {
	return $this->findByInterproAndStatutELASTIC($interpro, $statut, $q, $limit);
      }catch(Exception $e) {
	return $this->findByInterproAndStatutAndRaisonSocialeVIEW($interpro, $statut, $q);
      }
    }

    private function findByInterproAndStatutELASTIC($interpro, $statut, $q, $limit) {
      $query = array();
      foreach (explode(' ', $q) as $s) {
	$query[] = "*$q*";
      }
      if ($statut) {
	$query[] = "doc.statut:$statut";
      }

      $q = implode(' ', $query);

      $index = acElasticaManager::getType('SOCIETE');
      $elasticaQueryString = new acElasticaQueryQueryString();
      $elasticaQueryString->setDefaultOperator('AND');
      $elasticaQueryString->setQuery($q);

      // Create the actual search object with some data.
      $q = new acElasticaQuery();
      $q->setQuery($elasticaQueryString);
      if ($limit)
	$q->setLimit($limit);

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
	$e->id = $er->getId();
	$e->key = array($r['doc']['interpro'], $r['doc']['statut'], $r['doc']['type_societe'], $er->getId(), $r['doc']['raison_sociale'], $r['doc']['identifiant'], $r['doc']['siret'], $r['doc']['siege']['commune'], $r['doc']['siege']['code_postal']);
	$e->value = null;
	$res[] = $e;
      }
      return $res;
    }

    private function findByInterproAndStatutAndRaisonSocialeVIEW($interpro, $statut, $raison_sociale = "") {
          $societesViews = array();
		  $societesViews = array_merge($societesViews, $this->client->startkey(array($interpro, $statut))
				->endkey(array($interpro, $statut, array()))
				->getView($this->design, $this->view)->rows);
      $societes = array();
      foreach ($societesViews as $sView) {
          if($sView->key[self::KEY_RAISON_SOCIALE] == $raison_sociale){
              $societes[] = $sView;
          }
      }
      return $societes;
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
        $interpro = 'INTERPRO-declaration';
        return $this->client->startkey(array($interpro,  SocieteClient::STATUT_ACTIF, $type, 'SOCIETE-000000', $raison_sociale))
                            ->endkey(array($interpro, SocieteClient::STATUT_ACTIF, $type, 'SOCIETE-999999', $raison_sociale, array()))
                            ->getView($this->design, $this->view)->rows;

    }

    public function findByRaisonSocialeAndId($raison_sociale,$id) {
        $interpro = 'INTERPRO-declaration';
        return $this->client->startkey(array($interpro, $raison_sociale, $id))
                            ->endkey(array($interpro,  $raison_sociale, $id, array()))
                            ->getView($this->design, $this->view)->rows;

    }

    public static function makeLibelle($datas) {
        $libelle = '🏢 ';

        if (isset($datas[self::KEY_RAISON_SOCIALE]) && $rs = $datas[self::KEY_RAISON_SOCIALE]) {
			$libelle .= Anonymization::hideIfNeeded($rs);
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
