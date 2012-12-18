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

    public function findByInterproAndStatut($interpro, $statut) {

    	return $this->client->startkey(array($interpro, $statut))
                    		->endkey(array($interpro, $statut, array()))
                    		->getView($this->design, $this->view);
    }

    public function findByInterproAndFamilles($interpro, array $familles) {
        $etablissements = array();
        foreach($familles as $famille) {
            $etablissements = array_merge($etablissements, $this->findByInterproAndFamille($interpro, $famille));
        }

        return $etablissements;
    }

    public function findByInterproStatutAndFamilles($interpro, $statut, array $familles) {
    	$etablissements = array();
    	foreach($familles as $famille) {
    		$etablissements = array_merge($etablissements, $statut, $this->findByInterproStatutAndFamille($interpro, $statut, $famille));
    	}

    	return $etablissements;
    }


    public function findByInterproAndFamille($interpro, $famille) {
        $etablissements = array();

        foreach(EtablissementClient::$statuts as $statut => $nom) {
            $etablissements = array_merge($etablissements, $this->findByInterproStatutAndFamille($interpro, $statut, $famille)->rows);
        }

        return $etablissements;
    }

    public function findByInterproStatutAndFamille($interpro, $statut, $famille) {

        return $this->client->startkey(array($interpro, $statut, $famille))
                            ->endkey(array($interpro, $statut, $famille, array()))
                            ->getView($this->design, $this->view);
    }

    public function findByEtablissement($identifiant) {
        $etablissement = $this->client->find($identifiant, acCouchdbClient::HYDRATE_JSON);

        if(!$etablissement) {
            return null;
        }

        return $this->client->startkey(array($etablissement->interpro, $etablissement->statut, $etablissement->famille, $etablissement->id_societe, $etablissement->_id))
                            ->endkey(array($etablissement->interpro, $etablissement->statut, $etablissement->famille, $etablissement->id_societe,$etablissement->_id, array()))
                            ->getView($this->design, $this->view);
        
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