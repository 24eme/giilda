<?php

class EtablissementAllView extends acCouchdbView
{
	const KEY_INTERPRO_ID = 0;
	const KEY_FAMILLE = 1;
	const KEY_ETABLISSEMENT_ID = 2;
	const KEY_NOM = 3;
	const KEY_IDENTIFIANT = 4;
	const KEY_RAISON_SOCIALE = 5;
	const KEY_SIRET = 6;
	const KEY_CVI = 7;
	const KEY_COMMUNE = 8;
	const KEY_CODE_POSTAL = 9;

	public static function getInstance() {

        return acCouchdbManager::getView('etablissement', 'all', 'Etablissement');
    }

    public function findByInterpro($interpro) {

    	return $this->client->startkey(array($interpro))
                    		->endkey(array($interpro, array()))
                    		->getView($this->design, $this->view);
    }

    public function findByInterproAndFamilles($interpro, array $familles) {
    	$etablissements = array();
    	foreach($familles as $famille) {
    		$etablissements = array_merge($etablissements, $this->findByInterproAndFamille($interpro, $famille)->rows);
    	}

    	return $etablissements;
    }

    public function findByInterproAndFamille($interpro, $famille) {

    	return $this->client->startkey(array($interpro, $famille))
                    		->endkey(array($interpro, $famille, array()))
                    		->getView($this->design, $this->view);
    }

    public function findByEtablissement($identifiant) {
        $etablissement = $this->client->find($identifiant, acCouchdbClient::HYDRATE_JSON);

        if(!$etablissement) {
            return null;
        }

        return $this->client->startkey(array($etablissement->interpro, $etablissement->famille, $etablissement->_id))
                            ->endkey(array($etablissement->interpro, $etablissement->famille, $etablissement->_id, array()))
                            ->getView($this->design, $this->view);
        
    }

    public static function makeLibelle($datas) {
        $libelle = '';

        if ($nom = $datas[self::KEY_NOM]) {
            $libelle .= $nom;
        }

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

        if (isset($datas[self::KEY_CVI]) && $cvi = $datas[self::KEY_CVI]) {
            $libelle .= ' / '.$cvi;
        }
        $libelle .= ') ';

    	if (isset($datas[self::KEY_FAMILLE]))
    	  	$libelle .= $datas[self::KEY_FAMILLE];

    	if (isset($datas[self::KEY_COMMUNE]))
    	  	$libelle .= ' '.$datas[self::KEY_COMMUNE];

    	if (isset($datas[$datas[self::KEY_CODE_POSTAL]]))
    	  	$libelle .= ' '.$datas[$datas[self::KEY_CODE_POSTAL]];
        $libelle .= " (Etablissement)";
        return trim($libelle);
    }

}  