<?php

class SocieteAllView extends acCouchdbView
{
	const KEY_INTERPRO_ID = 0;
	const KEY_RAISON_SOCIALE = 1;
	const KEY_ID = 2;
        const KEY_TYPESOCIETE = 3;
	const KEY_IDENTIFIANT = 4;
	const KEY_SIRET = 5;
	const KEY_COMMUNE = 6;
	const KEY_CODE_POSTAL = 7;

    public static function getInstance() {
        return acCouchdbManager::getView('societe', 'all', 'Societe');
    }

    public function findByInterpro($interpro) {

    	return $this->client->startkey(array($interpro))
                    		->endkey(array($interpro, array()))
                    		->getView($this->design, $this->view);
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
        $libelle .= ' ('.$datas[self::KEY_IDENTIFIANT];
        if (isset($datas[self::KEY_SIRET]) && $siret = $datas[self::KEY_SIRET]) {
            $libelle .= ' / '.$siret;
        }
        $libelle .= ') ';

    	if (isset($datas[self::KEY_COMMUNE]) && $commune = $datas[self::KEY_COMMUNE])
    	  	$libelle .= ' / '.$commune;

    	if (isset($datas[self::KEY_CODE_POSTAL]) && $code_postal = $datas[self::KEY_CODE_POSTAL])
    	  	$libelle .= ' / '.$code_postal;
        
        return trim($libelle);
    }

}  